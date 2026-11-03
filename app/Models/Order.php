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
