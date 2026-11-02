<?php

namespace App\Services;

use App\Mail\OrderUpdateMail;
use App\Models\Admin;
use App\Models\Cartorder;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OrderStepState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

/**
 * Order tracking rules: which actions each step type offers, what each
 * action requires, the order steps must follow, and the timeline/emails
 * every action produces.
 */
class OrderWorkflow
{
    private const FILES = 'file|mimes:jpg,jpeg,png,webp,pdf|max:10240';

    /** Actions per step type. 'to' = state after the action. */
    public const ACTIONS = [
        'review' => [
            'confirm' => ['label' => 'Confirm order', 'to' => 'done', 'customer' => true],
        ],
        'approval' => [
            'send' => ['label' => 'Send to customer', 'to' => 'waiting_customer', 'customer' => true],
            'decision' => ['label' => 'Record customer decision', 'to' => null, 'customer' => true],
        ],
        'payment' => [
            'record' => ['label' => 'Record payment', 'to' => 'done', 'customer' => true],
        ],
        'production' => [
            'start' => ['label' => 'Start production', 'to' => 'in_progress', 'customer' => true],
            'finish' => ['label' => 'Mark production finished', 'to' => 'done', 'customer' => true],
        ],
        'checklist' => [
            'complete' => ['label' => 'Complete quality check', 'to' => 'done', 'customer' => true],
        ],
        'shipment' => [
            'dispatch' => ['label' => 'Mark dispatched', 'to' => 'in_progress', 'customer' => true],
            'deliver' => ['label' => 'Mark delivered', 'to' => 'done', 'customer' => true],
        ],
        'closure' => [
            'close' => ['label' => 'Close project', 'to' => 'done', 'customer' => true],
        ],
        'feedback' => [
            'rate' => ['label' => 'Record rating', 'to' => 'done', 'customer' => false],
        ],
    ];

    public const QC_CHECKS = [
        'quantity' => 'Quantity matches the order',
        'branding' => 'Branding / print is correct',
        'condition' => 'No damaged or defective pieces',
        'packing' => 'Packed and labelled for delivery',
    ];

    /** What customers may do themselves, per step type, and the states the step must be in. */
    public const CUSTOMER_ACTIONS = [
        'approval' => ['decision' => ['waiting_customer'], 'share' => ['in_progress', 'waiting_customer', 'changes_requested']],
        'feedback' => ['rate' => ['in_progress']],
    ];

    /** Customer decisions on a sent version. */
    public const DECISIONS = ['approved' => 'Approved', 'changes' => 'Changes requested', 'rejected' => 'Rejected'];

    /** Files customers may attach to a decision or a shared idea (their own designs / references). */
    public const CUSTOMER_FILE_TYPES = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'svg', 'ai', 'eps', 'zip'];
    public const CUSTOMER_MAX_FILES = 5;

    /** State names as customers see them. */
    public const CUSTOMER_LABELS = [
        'not_started' => 'Upcoming',
        'in_progress' => 'In progress',
        'waiting_customer' => 'Waiting for you',
        'changes_requested' => 'Updating to your feedback',
        'done' => 'Done',
    ];

    /** How long links in customer emails stay valid. */
    public const LINK_DAYS = 30;

    /** Who is acting: null = the logged-in admin (or system). */
    private $actor = null;

    /** Whether the current customer action may store the customer's files. */
    private $customerFiles = false;

    public const PAYMENT_METHODS = ['bank_transfer' => 'Bank transfer', 'cheque' => 'Cheque', 'card' => 'Card', 'cash' => 'Cash', 'other' => 'Other'];

    public static function steps()
    {
        return config('order_steps');
    }

    public static function type($step)
    {
        return config('order_steps.' . (int) $step . '.type');
    }

    /** Amount due for a payment step. */
    public static function amountDue(Order $order, $step)
    {
        return round($order->total * (float) config('order_steps.' . (int) $step . '.share', 0), 2);
    }

    /**
     * A customer approving / requesting changes / rejecting a version,
     * sharing their own design or idea, or rating the order. Same rules as
     * staff, plus: only the actions in CUSTOMER_ACTIONS, only in the states
     * listed there, and no override. Files only on approval steps.
     */
    public function runAsCustomer(Order $order, int $step, string $action, Request $request, $customerId = null)
    {
        $type = self::type($step);
        $needed = self::CUSTOMER_ACTIONS[$type][$action] ?? null;
        if (!$needed) {
            abort(404);
        }
        $state = $order->state($step);
        if (!$state || !in_array($state->state, $needed, true)) {
            throw ValidationException::withMessages(['step' => $type === 'feedback'
                ? ($state && $state->state === 'done' ? 'Thank you, your rating is already recorded.' : 'You can rate the order once it is completed.')
                : ($action === 'share' ? 'This step is closed, so new designs or ideas can no longer be added here. Please contact us instead.'
                    : 'This version is not waiting for your decision any more.')]);
        }
        if ($action === 'decision' && $request->filled('round') && (int) $request->input('round') !== (int) ($state->data['round'] ?? 0)) {
            throw ValidationException::withMessages(['step' => 'A newer version has been sent since this link. Please review the latest version.']);
        }

        $request->request->remove('override');
        $this->actor = ['type' => 'customer', 'id' => $customerId ?: $order->storeuser_id, 'name' => $order->customer_name ?: 'Customer'];
        $this->customerFiles = $type === 'approval';
        try {
            return $this->run($order, $step, $action, $request);
        } finally {
            $this->actor = null;
            $this->customerFiles = false;
        }
    }

    private const CUSTOMER_FILE_MESSAGES = [
        'files.max' => 'You can attach up to 5 files at a time.',
        'files.*.max' => 'Each file must be 10 MB or smaller.',
        'files.*.file' => 'A file could not be uploaded. Please try again.',
    ];

    /** Validation for customer files: allowed types, size, and no script inside SVGs. */
    private function customerFileRules()
    {
        return [
            'files' => 'nullable|array|max:' . self::CUSTOMER_MAX_FILES,
            'files.*' => ['file', 'max:10240', function ($attribute, $file, $fail) {
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, self::CUSTOMER_FILE_TYPES, true)) {
                    return $fail('"' . $file->getClientOriginalName() . '" is not an allowed file type. Use ' . strtoupper(implode(', ', self::CUSTOMER_FILE_TYPES)) . '.');
                }
                if ($ext === 'svg' && preg_match('/<script|<foreignObject|\bon[a-z]+\s*=|javascript:|<!ENTITY/i', (string) file_get_contents($file->getRealPath()))) {
                    $fail('"' . $file->getClientOriginalName() . '" contains scripts and cannot be accepted. Please send it as PDF or PNG.');
                }
            }],
        ];
    }

    /** Signed link for the customer to answer step $step without logging in. */
    public static function responseLink(Order $order, $step)
    {
        $round = (int) (optional($order->state($step))->data['round'] ?? 0);
        return URL::temporarySignedRoute('customer.orders.respond', now()->addDays(self::LINK_DAYS),
            ['orderid' => $order->orderid, 'step' => (int) $step, 'round' => $round]);
    }

    /**
     * Run one action. Throws ValidationException with a readable message
     * when the input or the step order is not right.
     */
    public function run(Order $order, int $step, string $action, Request $request)
    {
        $type = self::type($step);
        if (!$type) {
            abort(404);
        }

        if ($action === 'note') {
            $data = $request->validate([
                'message' => 'required|string|max:5000',
                'visibility' => 'required|in:internal,customer',
                'files.*' => self::FILES,
            ]);
            return $this->record($order, $step, 'note', $data['message'], [], $request, $data['visibility'] === 'customer', null);
        }

        // Customer's own design / idea: added to the timeline, the step does not move
        if ($action === 'share') {
            if (!$this->actor) {
                abort(404);
            }
            $data = $request->validate(array_merge($this->customerFileRules(), [
                'message' => 'nullable|string|max:5000',
            ]), self::CUSTOMER_FILE_MESSAGES);
            $msg = trim((string) ($data['message'] ?? ''));
            $count = count((array) $request->file('files', []));
            if ($msg === '' && !$count) {
                throw ValidationException::withMessages(['message' => 'Add a short description or attach at least one file.']);
            }
            $text = 'Customer shared ' . ($step == 2 ? 'a design idea' : 'a design idea for the sample')
                . ($count ? ' (' . $count . ' file' . ($count > 1 ? 's' : '') . ')' : '') . '.' . ($msg !== '' ? ' ' . $msg : '');
            return $this->record($order, $step, 'idea', $text, ['files' => $count], $request, true, null);
        }

        $definition = self::ACTIONS[$type][$action] ?? abort(404);
        $state = $order->state($step);

        if ($state->state === 'done') {
            throw ValidationException::withMessages(['step' => 'This step is already done.']);
        }
        $this->checkOrder($order, $step, $request);

        $rules = $this->rules($type, $action, $order, $step);
        $data = $request->validate($rules, $this->customerFiles ? self::CUSTOMER_FILE_MESSAGES : []);
        $current = (array) $state->data;
        [$to, $message, $details] = $this->apply($type, $action, $data, $current, $order, $step, $definition['to']);

        return DB::transaction(function () use ($order, $step, $action, $state, $to, $message, $details, $current, $request, $definition) {
            $state->data = array_merge($current, $details);
            if ($to) {
                $state->state = $to;
                $state->started_at = $state->started_at ?: now();
                if ($to === 'done') {
                    $state->completed_at = now();
                }
            }
            $state->save();
            return $this->record($order, $step, $action, $message, $details, $request, $definition['customer'], $to);
        });
    }

    /** Steps must be done in order; a superadmin may override (logged). */
    private function checkOrder(Order $order, int $step, Request $request)
    {
        $open = $order->stepStates->filter(function ($s) use ($step) {
            return $s->step < $step && $s->state !== 'done';
        });
        if ($open->isEmpty()) {
            return;
        }
        $isSuper = !$this->actor && optional(Admin::find(session('admin_id')))->role === 'superadmin';
        if ($isSuper && $request->boolean('override')) {
            return;
        }
        $first = $open->first()->step;
        throw ValidationException::withMessages(['step' => 'Finish step ' . $first . ' (' . config("order_steps.$first.title") . ') first.'
            . ($isSuper ? ' Tick "Override order" to skip ahead.' : '')]);
    }

    private function rules($type, $action, Order $order, $step)
    {
        switch ($type . '.' . $action) {
            case 'review.confirm':
                return ['confirmed_total' => 'required|numeric|min:0', 'message' => 'nullable|string|max:5000'];
            case 'approval.send':
                return ['files' => 'required|array|min:1', 'files.*' => self::FILES, 'message' => 'nullable|string|max:5000'];
            case 'approval.decision':
                $rules = ['decision' => 'required|in:' . implode(',', array_keys(self::DECISIONS)), 'message' => 'required_unless:decision,approved|nullable|string|max:5000'];
                return $this->customerFiles ? array_merge($rules, $this->customerFileRules()) : $rules;
            case 'payment.record':
                return ['amount' => 'required|numeric|min:0.01', 'method' => 'required|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
                    'reference' => 'required|string|max:120', 'paid_on' => 'required|date', 'files.*' => self::FILES, 'message' => 'nullable|string|max:5000'];
            case 'production.start':
                return ['start_date' => 'required|date', 'expected_finish' => 'required|date|after_or_equal:start_date', 'message' => 'nullable|string|max:5000'];
            case 'production.finish':
                return ['finished_on' => 'required|date', 'message' => 'nullable|string|max:5000'];
            case 'checklist.complete':
                return ['checks' => 'required|array', 'checks.*' => 'in:' . implode(',', array_keys(self::QC_CHECKS)),
                    'packed_qty' => 'required|integer|min:1', 'files.*' => self::FILES, 'message' => 'nullable|string|max:5000'];
            case 'shipment.dispatch':
                return ['courier' => 'required|string|max:80', 'tracking_number' => 'required|string|max:80', 'dispatch_date' => 'required|date',
                    'files.*' => self::FILES, 'message' => 'nullable|string|max:5000'];
            case 'shipment.deliver':
                return ['delivered_on' => 'required|date', 'received_by' => 'required|string|max:120', 'files' => 'required|array|min:1',
                    'files.*' => self::FILES, 'message' => 'nullable|string|max:5000'];
            case 'closure.close':
                return ['message' => 'nullable|string|max:5000'];
            case 'feedback.rate':
                return ['rating' => 'required|integer|min:1|max:5', 'message' => 'nullable|string|max:5000'];
        }
        abort(404);
    }

    /** Returns [new state, timeline message, details to store]. */
    private function apply($type, $action, array $in, array $current, Order $order, $step, $to)
    {
        $msg = trim((string) ($in['message'] ?? ''));
        $date = function ($d) { return \Carbon\Carbon::parse($d)->format('d M Y'); };
        switch ($type . '.' . $action) {
            case 'review.confirm':
                return [$to, trim('Order confirmed for AED ' . number_format($in['confirmed_total'], 2) . '. ' . $msg), ['confirmed_total' => (float) $in['confirmed_total']]];
            case 'approval.send':
                $round = (int) ($current['round'] ?? 0) + 1;
                return [$to, trim('Version ' . $round . ' sent for approval. ' . $msg), ['round' => $round]];
            case 'approval.decision':
                if (empty($current['round'])) {
                    throw ValidationException::withMessages(['decision' => 'Send a version to the customer first.']);
                }
                $ok = $in['decision'] === 'approved';
                $said = ['approved' => ' approved by the customer. ', 'changes' => ': customer requested changes. ', 'rejected' => ' rejected by the customer. Reason: '];
                return [$ok ? 'done' : 'changes_requested',
                    trim('Version ' . $current['round'] . $said[$in['decision']] . $msg),
                    ['decision_round_' . $current['round'] => $in['decision'], 'last_decision' => $in['decision']]];
            case 'payment.record':
                $due = self::amountDue($order, $step);
                $paid = (float) $in['amount'];
                $text = 'Payment of AED ' . number_format($paid, 2) . ' received on ' . $date($in['paid_on']) . ' (' . self::PAYMENT_METHODS[$in['method']] . ', ref ' . $in['reference'] . ').';
                if ($due > 0 && $paid + 0.009 < $due) {
                    $text .= ' Due was AED ' . number_format($due, 2) . '.';
                }
                return [$to, trim($text . ' ' . $msg), ['amount' => $paid, 'method' => $in['method'], 'reference' => $in['reference'], 'paid_on' => $in['paid_on'], 'due' => $due]];
            case 'production.start':
                return [$to, trim('Production started on ' . $date($in['start_date']) . ', expected to finish ' . $date($in['expected_finish']) . '. ' . $msg),
                    ['start_date' => $in['start_date'], 'expected_finish' => $in['expected_finish']]];
            case 'production.finish':
                if (empty($current['start_date'])) {
                    throw ValidationException::withMessages(['finished_on' => 'Start production first.']);
                }
                return [$to, trim('Production finished on ' . $date($in['finished_on']) . '. ' . $msg), ['finished_on' => $in['finished_on']]];
            case 'checklist.complete':
                $missing = array_diff(array_keys(self::QC_CHECKS), $in['checks']);
                if ($missing) {
                    throw ValidationException::withMessages(['checks' => 'Tick every check before completing: ' . implode(', ', array_map(function ($k) { return self::QC_CHECKS[$k]; }, $missing)) . '.']);
                }
                return [$to, trim('Quality check passed; ' . (int) $in['packed_qty'] . ' pieces packed. ' . $msg), ['packed_qty' => (int) $in['packed_qty'], 'checks' => $in['checks']]];
            case 'shipment.dispatch':
                return [$to, trim('Dispatched on ' . $date($in['dispatch_date']) . ' with ' . $in['courier'] . ', tracking number ' . $in['tracking_number'] . '. ' . $msg),
                    ['courier' => $in['courier'], 'tracking_number' => $in['tracking_number'], 'dispatch_date' => $in['dispatch_date']]];
            case 'shipment.deliver':
                if (empty($current['tracking_number'])) {
                    throw ValidationException::withMessages(['delivered_on' => 'Mark the order dispatched first.']);
                }
                return [$to, trim('Delivered on ' . $date($in['delivered_on']) . ', received by ' . $in['received_by'] . '. ' . $msg),
                    ['delivered_on' => $in['delivered_on'], 'received_by' => $in['received_by']]];
            case 'closure.close':
                return [$to, trim('Project closed. ' . $msg), []];
            case 'feedback.rate':
                return [$to, trim('Customer rated the order ' . (int) $in['rating'] . '/5. ' . $msg), ['rating' => (int) $in['rating']]];
        }
        abort(404);
    }

    /** Store files, add the timeline entry, move the order forward, email the customer. */
    private function record(Order $order, $step, $type, $message, array $details, Request $request, $customerVisible, $to)
    {
        $files = [];
        // Customers upload only on approval steps (decision / shared idea), into their own folder
        foreach ($this->actor && !$this->customerFiles ? [] : (array) $request->file('files', []) as $file) {
            $files[] = $this->actor
                // Keep the real extension (.ai/.eps would otherwise be renamed from their MIME type)
                ? $file->storeAs('order-files/' . $order->orderid . '/customer', \Illuminate\Support\Str::random(40) . '.' . strtolower($file->getClientOriginalExtension()), 'public')
                : $file->store('order-files/' . $order->orderid, 'public');
        }
        $admin = $this->actor ? null : Admin::find(session('admin_id'));
        $actor = $this->actor ?: ['type' => $admin ? 'admin' : 'system', 'id' => optional($admin)->id,
            'name' => $admin ? ($admin->name ?: $admin->email) : 'System'];
        $byCustomer = $actor['type'] === 'customer';
        $event = OrderEvent::create([
            'order_id' => $order->id,
            'step' => $step,
            'type' => $type,
            'visibility' => ($customerVisible || $byCustomer) ? 'customer' : 'internal',
            'actor_type' => $actor['type'],
            'actor_id' => $actor['id'],
            'actor_name' => $actor['name'],
            'message' => $message,
            'data' => $details ?: null,
            'files' => $files ?: null,
            'created_at' => now(),
        ]);
        if (!$byCustomer && $request->boolean('override')) {
            OrderEvent::create(['order_id' => $order->id, 'step' => $step, 'type' => 'override', 'visibility' => 'internal',
                'actor_type' => 'admin', 'actor_id' => optional($admin)->id, 'actor_name' => optional($admin)->email,
                'message' => 'Step order overridden by a superadmin.', 'created_at' => now()]);
        }

        if ($to) {
            $this->advance($order->fresh('stepStates'));
        }
        if ($byCustomer) {
            $this->notifyStaff($order->fresh(), $step, $message, $type === 'idea' ? 'idea' : ($details['last_decision'] ?? $type));
        } elseif ($customerVisible) {
            $this->notify($order->fresh('stepStates'), $step, $type, $message);
        }
        return $event;
    }

    /** Current step = first step not done; the next step starts automatically. */
    public function advance(Order $order)
    {
        $next = $order->stepStates->firstWhere('state', '!=', 'done');
        if ($next && $next->state === 'not_started') {
            $next->update(['state' => 'in_progress', 'started_at' => now()]);
        }
        $currentStep = $next ? $next->step : 10;
        $currentState = $next ? $next->state : 'done';
        if ($order->current_step != $currentStep) {
            $order->step_started_at = now();
        }
        $order->current_step = $currentStep;
        $order->step_state = $currentState;
        $order->save();

        // Keep the old status column in step for Store Enquiries and My Orders
        Cartorder::where('order_id', $order->id)->update(['orderstatus' => $order->statusLabel()]);
    }

    /** Email the customer about a staff action, with a button for what they can do next. */
    private function notify(Order $order, $step, $action, $message)
    {
        if (empty($order->customer_email)) {
            return;
        }
        $button = null;
        if ($action === 'send') {
            $button = ['label' => 'Review version ' . (int) ($order->state($step)->data['round'] ?? 1), 'url' => self::responseLink($order, $step)];
        } elseif ($action === 'close' && optional($order->state(10))->state === 'in_progress') {
            $button = ['label' => 'Rate your order', 'url' => self::responseLink($order, 10)];
        }
        $this->mail($order->customer_email, 'Update on your order #' . $order->orderid . ' - More Than Giftz', [
            'order' => (object) ['orderid' => $order->orderid, 'id' => $order->id, 'customer_name' => $order->customer_name],
            'stepTitle' => config("order_steps.$step.customer"),
            'note' => $message,
            'button' => $button,
            'orderUrl' => url('/orders/' . $order->orderid),
        ], $order);
    }

    /** Tell staff the customer answered (approved, asked for changes, rejected, shared an idea, rated). */
    private function notifyStaff(Order $order, $step, $message, $what = null)
    {
        $to = staff_email();
        if (empty($to)) {
            Log::warning('No staff email set; staff were not told about the customer response on order #' . $order->orderid);
            return;
        }
        $lead = ['rejected' => 'Customer REJECTED a version', 'idea' => 'Customer shared a design idea', 'changes' => 'Customer requested changes'][$what] ?? 'Customer response';
        $this->mail($to, $lead . ' on order #' . $order->orderid . ': ' . config("order_steps.$step.title"), [
            'order' => (object) ['orderid' => $order->orderid, 'id' => $order->id, 'customer_name' => 'team'],
            'stepTitle' => config("order_steps.$step.title"),
            'note' => ($order->customer_name ?: 'The customer') . ': ' . $message,
            'button' => ['label' => 'Open the order', 'url' => url('/admin/orders/' . $order->id . '?step=' . (int) $step)],
            'orderUrl' => null,
        ], $order);
    }

    /** Queued when QUEUE_CONNECTION is not sync; a mail problem never stops the action. */
    private function mail($to, $subject, array $data, Order $order)
    {
        try {
            Mail::to($to)->queue(new OrderUpdateMail($subject, $data));
        } catch (\Throwable $e) {
            Log::error('Order update email failed for order #' . $order->orderid . ': ' . $e->getMessage());
        }
    }

    /** Create the order, its 10 step states and the first timeline entry. */
    public static function createForCartorders($orderid, $rows)
    {
        $first = $rows->first();
        $order = Order::create([
            'orderid' => $orderid, 'storeuser_id' => $first->userid, 'customer_name' => $first->customer_name,
            'customer_email' => $first->customer_email, 'customer_phone' => $first->customer_phone,
            'customer_company' => $first->customer_company, 'total' => (float) $first->ordertotal,
            'current_step' => 1, 'step_state' => 'in_progress', 'step_started_at' => now(),
        ]);
        foreach (range(1, 10) as $step) {
            OrderStepState::create(['order_id' => $order->id, 'step' => $step,
                'state' => $step === 1 ? 'in_progress' : 'not_started', 'started_at' => $step === 1 ? now() : null]);
        }
        Cartorder::whereIn('id', $rows->pluck('id'))->update(['order_id' => $order->id]);
        OrderEvent::create(['order_id' => $order->id, 'step' => 1, 'type' => 'created', 'visibility' => 'customer',
            'actor_type' => 'customer', 'actor_id' => $first->userid, 'actor_name' => $first->customer_name,
            'message' => 'Enquiry received with ' . $rows->count() . ' product(s).' . ($first->additional_info ? ' Customer note: ' . $first->additional_info : ''),
            'created_at' => now()]);
        return $order;
    }
}
