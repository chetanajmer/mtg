<?php

namespace App\Services;

use App\Mail\OrderUpdateMail;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OrderInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Invoices issued from the order workflow by staff, once the payment step
 * they confirm is recorded as done: the advance (step 3) and the balance
 * (step 7). One of each per order.
 */
class OrderInvoices
{
    public const ADVANCE_STEP = 3;
    public const BALANCE_STEP = 7;

    /** type => [step, number prefix, what the customer paid] */
    public const TYPES = [
        'advance' => ['step' => self::ADVANCE_STEP, 'prefix' => 'MTG-ADV-', 'label' => 'advance payment'],
        'balance' => ['step' => self::BALANCE_STEP, 'prefix' => 'MTG-BAL-', 'label' => 'balance payment'],
    ];

    /** Type of invoice a step issues, or null. */
    public static function typeForStep($step)
    {
        foreach (self::TYPES as $type => $def) {
            if ($def['step'] === (int) $step) {
                return $type;
            }
        }
        return null;
    }

    public function issueAdvance(Order $order, array $actor)
    {
        return $this->issue($order, 'advance', $actor);
    }

    /** Issue the invoice for a payment step; returns the existing one if already issued. */
    public function issue(Order $order, $type, array $actor)
    {
        if (!isset(self::TYPES[$type])) {
            abort(404);
        }
        $def = self::TYPES[$type];
        $order->loadMissing(['items', 'stepStates']);
        if ($existing = OrderInvoice::where('order_id', $order->id)->where('type', $type)->first()) {
            return $existing;
        }

        $state = $order->state($def['step']);
        $payment = (array) optional($state)->data;
        if (optional($state)->state !== 'done' || empty($payment['amount'])) {
            throw ValidationException::withMessages(['invoice' => 'Record the ' . $def['label'] . ' first; the invoice confirms a payment that has been received.']);
        }

        $invoice = DB::transaction(function () use ($order, $type, $def, $payment, $actor) {
            $prefix = $def['prefix'] . now()->year . '-';
            // Lock this year's numbers so two staff members can't get the same one
            $last = OrderInvoice::where('number', 'like', $prefix . '%')->lockForUpdate()->orderByDesc('number')->value('number');
            $number = $prefix . str_pad($last ? (int) substr($last, strlen($prefix)) + 1 : 1, 4, '0', STR_PAD_LEFT);

            return OrderInvoice::create([
                'order_id' => $order->id,
                'step' => $def['step'],
                'type' => $type,
                'number' => $number,
                'amount' => (float) $payment['amount'],
                'data' => $this->snapshot($order, $type, $payment),
                'issued_by' => $actor['name'] ?? null,
                'issued_at' => now(),
            ]);
        });

        $message = ucfirst($def['label']) . ' invoice ' . $invoice->number . ' issued: AED ' . number_format($invoice->amount, 2) . ' received with thanks.'
            . ($type === 'balance' && $invoice->data['outstanding'] <= 0 ? ' Your order is now paid in full.' : '');
        OrderEvent::create([
            'order_id' => $order->id, 'step' => $def['step'], 'type' => 'invoice', 'visibility' => 'customer',
            'actor_type' => $actor['type'] ?? 'admin', 'actor_id' => $actor['id'] ?? null, 'actor_name' => $actor['name'] ?? null,
            'message' => $message, 'data' => ['invoice_id' => $invoice->id, 'number' => $invoice->number], 'created_at' => now(),
        ]);
        $this->email($order, $invoice, $message);

        return $invoice;
    }

    /** Everything the invoice prints, frozen at the moment it is issued. */
    private function snapshot(Order $order, $type, array $payment)
    {
        $first = $order->items->first();
        $lines = $order->items->map(function ($item) {
            $unit = (float) $item->price;
            return ['name' => $item->name, 'modelno' => $item->modelno, 'quantity' => (int) $item->quantity, 'unit' => round($unit, 2), 'total' => round($unit * (int) $item->quantity, 2)];
        })->values()->all();
        $subtotal = round(array_sum(array_column($lines, 'total')), 2);
        $total = round((float) $order->total, 2);
        $received = round((float) $payment['amount'], 2);
        $step = self::TYPES[$type]['step'];

        $data = [
            'company' => config('company'),
            'customer' => [
                'name' => $order->customer_name,
                'company' => $order->customer_company,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'address' => trim(implode(', ', array_filter([optional($first)->customer_address, optional($first)->customer_city, optional($first)->customer_country]))),
            ],
            'order' => ['orderid' => $order->orderid, 'date' => optional($order->created_at)->format('Y-m-d')],
            'lines' => $lines,
            'subtotal' => $subtotal,
            'vat_rate' => (float) config('app.cart_tax_rate', 0.05),
            'vat' => round($total - $subtotal, 2),
            'total' => $total,
            'due' => round((float) ($payment['due'] ?? OrderWorkflow::amountDue($order, $step)), 2),
            'payment' => $this->payment($payment),
        ];

        if ($type === 'balance') {
            // The advance from step 3 counts towards the total too
            $advance = (array) optional($order->state(self::ADVANCE_STEP))->data;
            $data['advance'] = $this->payment($advance);
            $data['outstanding'] = round(max(0, $total - $data['advance']['amount'] - $received), 2);
        } else {
            $data['balance'] = round(max(0, $total - $received), 2);
        }

        return $data;
    }

    private function payment(array $p)
    {
        return [
            'amount' => round((float) ($p['amount'] ?? 0), 2),
            'method' => OrderWorkflow::PAYMENT_METHODS[$p['method'] ?? ''] ?? ($p['method'] ?? ''),
            'reference' => $p['reference'] ?? '',
            'paid_on' => $p['paid_on'] ?? null,
        ];
    }

    private function email(Order $order, OrderInvoice $invoice, $message)
    {
        if (empty($order->customer_email)) {
            return;
        }
        try {
            Mail::to($order->customer_email)->queue(new OrderUpdateMail('Your ' . self::TYPES[$invoice->type]['label'] . ' invoice ' . $invoice->number . ' - order #' . $order->orderid, [
                'order' => (object) ['orderid' => $order->orderid, 'id' => $order->id, 'customer_name' => $order->customer_name],
                'stepTitle' => config('order_steps.' . $invoice->step . '.customer'),
                'note' => $message,
                'button' => ['label' => 'View invoice', 'url' => route('customer.orders.invoice', [$order->orderid, $invoice->number])],
                'orderUrl' => url('/orders/' . $order->orderid),
            ]));
        } catch (\Throwable $e) {
            Log::error('Invoice email failed for order #' . $order->orderid . ': ' . $e->getMessage());
        }
    }
}
