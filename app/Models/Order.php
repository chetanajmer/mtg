<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One customer order (one order number). Its products are cartorders rows.
 */
class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['step_started_at' => 'datetime', 'total' => 'float'];

    /** A Request a Quote: no catalogue product, and staff set the price at step 1. */
    public function isCustom()
    {
        return $this->items->contains(function ($item) {
            return $item->modelno === Cartorder::CUSTOM_MODEL && !$item->productid;
        });
    }

    /** The order value for display. A custom order has none until staff confirm it. */
    public function totalLabel()
    {
        return $this->total > 0 ? 'AED ' . number_format($this->total, 2) : 'Price to be confirmed';
    }

    /**
     * Staff confirmed the order's total, including VAT. Line prices follow it in
     * proportion, so invoices and the payment steps add up to the agreed total.
     */
    public function applyConfirmedTotal($total)
    {
        $total = round((float) $total, 2);
        if (abs($total - (float) $this->total) < 0.005) {
            return;
        }

        $vat = (float) config('app.cart_tax_rate', 0.05);
        $subtotal = round($total / (1 + $vat), 2);
        $items = $this->items;
        $lineValue = function ($item) { return (float) $item->price * (int) $item->quantity; };
        $oldSubtotal = $items->sum($lineValue);

        \Illuminate\Support\Facades\DB::transaction(function () use ($total, $subtotal, $vat, $items, $lineValue, $oldSubtotal) {
            $this->update(['total' => $total]);

            $left = $subtotal;
            foreach ($items->values() as $i => $item) {
                $line = $i === $items->count() - 1
                    ? $left
                    : round($oldSubtotal > 0 ? $subtotal * $lineValue($item) / $oldSubtotal : $subtotal / $items->count(), 2);
                $left = round($left - $line, 2);
                $item->price = round($line / max(1, (int) $item->quantity), 2);
                $item->subtotal = $line;
                $item->ordertotal = $total;
                $item->save();
            }

            $enquiry = Enquiry::where('orderid', $this->orderid)->first();
            if ($enquiry) {
                $lines = json_decode((string) $enquiry->cart_items, true) ?: [];
                foreach ($items->values() as $i => $item) {
                    if (isset($lines[$i])) {
                        $lines[$i]['price'] = (float) $item->price;
                    }
                }
                $enquiry->subtotal = $subtotal;
                $enquiry->tax = round($total - $subtotal, 2);
                $enquiry->total = $total;
                $enquiry->cart_items = json_encode($lines);
                $enquiry->save();
            }
        });
    }
    public function items()
    {
        return $this->hasMany(Cartorder::class, 'order_id')->orderBy('id');
    }

    public function stepStates()
    {
        return $this->hasMany(OrderStepState::class)->orderBy('step');
    }

    public function events()
    {
        return $this->hasMany(OrderEvent::class)->orderByDesc('created_at')->orderByDesc('id');
    }

    public function invoices()
    {
        return $this->hasMany(OrderInvoice::class)->orderBy('id');
    }

    public function state($step)
    {
        return $this->stepStates->firstWhere('step', (int) $step);
    }

    /** e.g. "Mockup Design & Approval · Waiting for customer" */
    public function statusLabel()
    {
        $title = config('order_steps.' . $this->current_step . '.title');
        return $this->step_state === 'done' && $this->current_step == 10
            ? 'Completed'
            : $title . ' · ' . OrderStepState::label($this->step_state);
    }
}
