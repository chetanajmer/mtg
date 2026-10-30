<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartorder extends Model
{
    use HasFactory;

    public function workflow()
    {
        return $this->hasOne(\App\Models\OrderProcessingWorkflow::class, 'cartorder_id');
    }

    public function workflowSteps()
    {
        return $this->hasMany(\App\Models\OrderWorkflowStep::class, 'cartorder_id');
    }

    public function getWorkflowStep($stepNumber)
    {
        return $this->workflowSteps()->where('step_number', $stepNumber)->first();
    }
}
