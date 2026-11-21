<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A figure in the Annual Corporate Membership hero, e.g. "500+ Corporate Clients".
 */
class MembershipStat extends Model
{
    protected $fillable = ['value', 'label', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];
}
