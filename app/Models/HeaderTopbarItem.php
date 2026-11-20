<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Left side of the header's black top bar: language, currency and offer texts (admin: Header Top Bar). */
class HeaderTopbarItem extends Model
{
    const TYPES = ['language' => 'Language menu', 'currency' => 'Currency menu', 'offer' => 'Offer text'];

    protected $fillable = ['type', 'text', 'link', 'is_active', 'ranking'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('id');
    }
}
