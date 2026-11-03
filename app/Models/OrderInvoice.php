<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An invoice issued from the order workflow. "data" holds the frozen copy of
 * what the invoice shows (company, customer, lines, totals, payment).
 */
class OrderInvoice extends Model
{
    public const TYPES = ['advance' => 'Advance Payment Invoice', 'balance' => 'Balance Payment Invoice'];

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'amount' => 'float', 'issued_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function title()
    {
        return self::TYPES[$this->type] ?? 'Invoice';
    }
}
