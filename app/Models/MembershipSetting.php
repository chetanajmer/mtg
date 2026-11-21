<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Headings of the "Membership Plans" section (one row).
 */
class MembershipSetting extends Model
{
    protected $fillable = ['plans_eyebrow', 'plans_title', 'plans_subtitle'];

    /** The single settings row, created with the default texts if missing */
    public static function current()
    {
        return static::first() ?: static::create([
            'plans_eyebrow' => 'MEMBERSHIP PLANS',
            'plans_title' => 'Choose the Right Plan for Your Business',
            'plans_subtitle' => 'More gifting. More savings. More possibilities.',
        ]);
    }
}
