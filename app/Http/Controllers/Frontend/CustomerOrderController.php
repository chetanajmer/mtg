<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Customer side of order tracking. Three ways in, one page:
 *  - account: logged-in customer from My Orders (can approve / rate)
 *  - link:    signed link from an email, no login (can answer that one step)
 *  - track:   order number + email lookup on /track-order (read only)
 */
class CustomerOrderController extends Controller
{
    /** /orders/{orderid} */
    public function show($orderid)
    {
        $order = $this->ownOrder($orderid);
        $actions = $shares = [];
        foreach ($this->openActions($order) as $step => $open) {
            foreach ($open as $action) {
                $url = route('customer.orders.act', [$order->orderid, $step, $action]);
                $action === 'share' ? $shares[$step] = $url : $actions[$step] = $url;
            }
        }
        return $this->page($order, 'account', $actions, compact('shares'));
    }

    /** POST /orders/{orderid}/steps/{step}/{action} */
    public function act(Request $request, OrderWorkflow $workflow, $orderid, $step, $action)
    {
        $order = $this->ownOrder($orderid);
        return $this->answer($request, $workflow, $order, (int) $step, $action, session('userid'),
            route('customer.orders.show', $order->orderid));
    }

    /** GET|POST /order-response/{orderid}/{step}/{round}?signature=... (email link) */
    public function respond(Request $request, OrderWorkflow $workflow, $orderid, $step, $round)
    {
        if (!$request->hasValidSignature()) {
            return response()->view('web_frontend.order-link-expired', [], 403);
        }
        $order = Order::with(['items', 'stepStates', 'events'])->where('orderid', $orderid)->firstOrFail();
        $step = (int) $step;
        $open = $this->openActions($order)[$step] ?? [];
        $action = collect($open)->first(function ($a) { return $a !== 'share'; });

        if ($request->isMethod('post')) {
            if ($request->input('do') === 'share') {
                return $this->answer($request, $workflow, $order, $step, 'share', null, $request->fullUrl());
            }
            $request->merge(['round' => (int) $round]);
            // Nothing open (already answered): the service explains why
            $action = $action ?: key(OrderWorkflow::CUSTOMER_ACTIONS[OrderWorkflow::type($step)] ?? abort(404));
            return $this->answer($request, $workflow, $order, $step, $action, null, $request->fullUrl());
        }

        $current = (int) (optional($order->state($step))->data['round'] ?? 0);
        $actions = $action && ($action === 'rate' || $current === (int) $round) ? [$step => $request->fullUrl()] : [];
        $shares = in_array('share', $open, true) ? [$step => $request->fullUrl()] : [];
        $stale = $action === 'decision' && $current !== (int) $round;
        return $this->page($order, 'link', $actions, compact('stale', 'shares'));
    }

    /** GET /track-order */
    public function trackForm()
    {
        return view('web_frontend.order-tracking');
    }

    /** POST /track-order: order number + the email on the order */
    public function trackLookup(Request $request)
    {
        $data = $request->validate([
            'orderid' => 'required|string|max:30',
            'email' => 'required|email|max:191',
        ], [], ['orderid' => 'order number']);
        $number = preg_replace('/\D/', '', $data['orderid']);
        $order = $number === '' ? null : Order::where('orderid', (int) $number)
            ->whereRaw('LOWER(customer_email) = ?', [mb_strtolower(trim($data['email']))])->first();
        if (!$order) {
            return redirect()->route('track.form')->withInput()->withErrors(['orderid' => 'We could not find an order with that number and email. Check both and try again.']);
        }
        $request->session()->push('tracked_orders', (int) $order->id);
        return redirect()->route('track.show', $order->orderid);
    }

    /** GET /track-order/{orderid}: only after a lookup in this browser session */
    public function trackShow($orderid)
    {
        $order = Order::with(['items', 'stepStates', 'events'])->where('orderid', $orderid)->first();
        if (!$order || !in_array((int) $order->id, array_map('intval', session('tracked_orders', [])), true)) {
            return redirect()->route('track.form')->with('info', 'Enter your order number and email to see this order.');
        }
        return $this->page($order, 'track', []);
    }

    private function page(Order $order, $mode, array $actions, array $extra = [])
    {
        return view('web_frontend.order-progress', array_merge([
            'order' => $order,
            'mode' => $mode,
            'actions' => $actions,
            'steps' => OrderWorkflow::steps(),
            'stale' => false,
            'shares' => [],
        ], $extra));
    }

    /** Steps the customer can act on right now: [step => [actions]]. */
    private function openActions(Order $order)
    {
        $open = [];
        foreach (OrderWorkflow::steps() as $step => $def) {
            foreach (OrderWorkflow::CUSTOMER_ACTIONS[$def['type']] ?? [] as $action => $needed) {
                if (in_array(optional($order->state($step))->state, $needed, true)) {
                    $open[$step][] = $action;
                }
            }
        }
        return $open;
    }

    private function answer(Request $request, OrderWorkflow $workflow, Order $order, $step, $action, $customerId, $back)
    {
        $decision = $request->input('decision');
        try {
            if ($action === 'decision' && in_array($decision, ['changes', 'rejected'], true) && !trim((string) $request->input('message'))) {
                throw ValidationException::withMessages(['message' => $decision === 'rejected'
                    ? 'Please tell us why you are rejecting this version.' : 'Please tell us what you would like changed.']);
            }
            if ($action === 'rate' && !$request->filled('rating')) {
                throw ValidationException::withMessages(['rating' => 'Please choose a rating from 1 to 5 stars.']);
            }
            $workflow->runAsCustomer($order, $step, $action, $request, $customerId);
        } catch (ValidationException $e) {
            // Each form shows its own errors: 'share' box or the decision/rating box
            return redirect($back . ($action === 'share' ? '#share' : '#respond'))->withErrors($e->errors())->withInput()->with('form', $action);
        }

        $done = [
            'rate' => 'Thank you for your rating!',
            'share' => 'Thank you, we have received your design / idea. Our team will take it into account.',
        ][$action] ?? [
            'approved' => 'Thank you, version approved. We will move on to the next step.',
            'rejected' => 'We have recorded that you rejected this version. Our team will contact you and prepare a new one.',
        ][$decision] ?? 'Thank you, we have your comments and will send a new version.';
        return redirect($back)->with('success', $done);
    }

    /** The logged-in customer's order: placed by them or sent to their email. */
    private function ownOrder($orderid)
    {
        $userid = session('userid');
        if (!$userid) {
            abort(redirect()->route('corporate.login')->with('info', 'Please login to view your order'));
        }
        $email = optional(DB::table('storeusers')->where('id', $userid)->first())->email;
        return Order::with(['items', 'stepStates', 'events'])->where('orderid', $orderid)
            ->where(function ($q) use ($userid, $email) {
                $q->where('storeuser_id', $userid);
                if ($email) {
                    $q->orWhere('customer_email', $email);
                }
            })->firstOrFail();
    }
}
