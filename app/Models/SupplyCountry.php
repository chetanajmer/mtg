<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyCountry extends Model
{
    protected $fillable = ['code', 'name', 'is_active', 'ranking'];

    protected $casts = ['is_active' => 'boolean'];

    public function flagUrl()
    {
        return 'https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/flags/1x1/' . $this->code . '.svg';
    }

    /**
     * The list in the order it should be shown.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('name');
    }
}
