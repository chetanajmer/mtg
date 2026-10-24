<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProcessingWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'cartorder_id',
        'current_step',
        'step1_received',
        'step2_verification',
        'step3_packing',
        'step4_shipped',
        'step5_delivered',
        'step1_completed_at',
        'step2_completed_at',
        'step3_completed_at',
        'step4_completed_at',
        'step5_completed_at',
        'courier_name',
        'tracking_number',
        'dispatch_date',
        'delivery_date',
        'notes',
        'completed_by',
    ];

    protected $casts = [
        'step1_received' => 'boolean',
        'step2_verification' => 'boolean',
        'step3_packing' => 'boolean',
        'step4_shipped' => 'boolean',
        'step5_delivered' => 'boolean',
        'step1_completed_at' => 'datetime',
        'step2_completed_at' => 'datetime',
        'step3_completed_at' => 'datetime',
        'step4_completed_at' => 'datetime',
        'step5_completed_at' => 'datetime',
        'dispatch_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function cartorder()
    {
        return $this->belongsTo(\App\Models\Cartorder::class, 'cartorder_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'completed_by');
    }

    public function getProgressPercentageAttribute()
    {
        $completedSteps = 0;
        if ($this->step1_received) $completedSteps++;
        if ($this->step2_verification) $completedSteps++;
        if ($this->step3_packing) $completedSteps++;
        if ($this->step4_shipped) $completedSteps++;
        if ($this->step5_delivered) $completedSteps++;

        return ($completedSteps / 5) * 100;
    }

    public function getStatusTextAttribute()
    {
        if ($this->step5_delivered) return 'Delivered';
        if ($this->step4_shipped) return 'Shipped';
        if ($this->step3_packing) return 'Packed';
        if ($this->step2_verification) return 'Verified';
        if ($this->step1_received) return 'Received';
        return 'Pending';
    }
}
