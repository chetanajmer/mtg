<?php

namespace App\Models;

use App\Services\GiftPlannerAi;
use Illuminate\Database\Eloquent\Model;

/** A Budget Planner bundle picture made by the AI, reused for the same occasion and products. */
class PlannerImage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'product_names' => 'array',
        'last_used_at' => 'datetime',
    ];

    public function path()
    {
        return public_path(GiftPlannerAi::IMAGE_FOLDER . '/' . $this->file);
    }

    public function url()
    {
        return asset(GiftPlannerAi::IMAGE_FOLDER . '/' . $this->file);
    }
}
