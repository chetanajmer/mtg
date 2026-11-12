<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A plan card on the Annual Corporate Membership page (Admin > Annual Membership).
 */
class MembershipPlan extends Model
{
    /** Medal styles the page has CSS for: value => label */
    public const MEDALS = ['silver' => 'Silver', 'gold' => 'Gold', 'platinum' => 'Platinum'];

    protected $fillable = [
        'name', 'tagline', 'description', 'medal', 'price', 'price_period',
        'is_popular', 'badge_text', 'features', 'button_text', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** CSS class of the medal on the page */
    public function medalClass()
    {
        return ['silver' => 'silver', 'gold' => 'goldm', 'platinum' => 'plat'][$this->medal] ?? 'silver';
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
