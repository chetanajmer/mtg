<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterSetting extends Model
{
    protected $fillable = ['label', 'is_active', 'ranking', 'hidden_categories', 'options'];

    protected $casts = [
        'is_active' => 'boolean',
        'hidden_categories' => 'array',
        'options' => 'array',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('id');
    }

    // Whether this filter appears on the given category's page
    public function showsOn($categoryId)
    {
        return $this->is_active && !in_array((int) $categoryId, $this->hidden_categories ?: [], true);
    }

    // The address value of a price band, such as under-25, 25-50 or over-100
    public static function bandKey($band)
    {
        $from = $band['from'] + 0;
        if ($band['to'] === null) {
            return 'over-' . $from;
        }

        return $from == 0 ? 'under-' . ($band['to'] + 0) : $from . '-' . ($band['to'] + 0);
    }
}
