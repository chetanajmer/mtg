<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One Budget Planner result shown to a customer (admin: Planner Log). */
class PlannerLog extends Model
{
    const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = [
        'preferences' => 'array',
        'bundles' => 'array',
        'ai_written' => 'boolean',
    ];
}
