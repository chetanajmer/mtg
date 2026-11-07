<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A Budget Planner AI choice saved by its inputs, reused for the same inputs. */
class PlannerRecommendation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'preferences' => 'array',
        'plan' => 'array',
        'last_reused_at' => 'datetime',
    ];

    /**
     * The same inputs always give the same key: custom occasions ignore case and extra spaces,
     * preferences ignore their order.
     */
    public static function keyFor($occasion, $custom, $team, $budget, array $preferences, $round)
    {
        $preferences = array_values(array_unique($preferences));
        sort($preferences);
        return sha1(json_encode([
            $occasion, mb_strtolower(trim(preg_replace('/\s+/u', ' ', (string) $custom))), $team, $budget, $preferences, (int) $round,
        ]));
    }
}
