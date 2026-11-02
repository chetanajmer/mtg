<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStepState extends Model
{
    public const LABELS = [
        'not_started' => 'Not started',
        'in_progress' => 'In progress',
        'waiting_customer' => 'Waiting for customer',
        'changes_requested' => 'Changes requested',
        'done' => 'Done',
    ];

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'started_at' => 'datetime', 'completed_at' => 'datetime'];

    public static function label($state)
    {
        return self::LABELS[$state] ?? ucfirst(str_replace('_', ' ', (string) $state));
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
