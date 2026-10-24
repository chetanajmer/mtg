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
}
