<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One timeline entry. Entries are only ever added, never edited.
 */
class OrderEvent extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'files' => 'array', 'created_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
