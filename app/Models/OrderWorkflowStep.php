<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderWorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'cartorder_id',
        'step_number',
        'mockup_files',
        'customer_message',
        'approval_status',
        'approved_date',
        'send_notification',
        'delivery_type',
        'delivery_date',
        'delivered_to',
        'delivery_address',
        'delivery_proof',
        'tracking_type',
        'awb_number',
        'courier_partner',
        'dispatch_date',
        'dispatch_proof',
        'internal_remarks',
        'rating',
    ];

    protected $casts = [
        'mockup_files' => 'array',
        'approved_date' => 'date',
        'send_notification' => 'boolean',
        'delivery_proof' => 'array',
        'dispatch_proof' => 'array',
        'delivery_date' => 'date',
        'dispatch_date' => 'date',
    ];

    public function cartorder()
    {
        return $this->belongsTo(Cartorder::class, 'cartorder_id');
    }
}
