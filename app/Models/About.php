<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    public $timestamps=false;

    protected $casts = ['content' => 'array'];

    /**
     * Text and image of the About Us page: what staff saved in the admin,
     * field by field over the defaults in config/about_page.php.
     */
    public static function pageContent()
    {
        $defaults = config('about_page');
        $saved = (array) optional(self::find(1))->content;
        $content = $defaults;
        foreach ($defaults as $key => $default) {
            if (!array_key_exists($key, $saved)) {
                continue;
            }
            if (is_array($default)) {
                foreach ($default as $i => $item) {
                    $value = $saved[$key][$i] ?? null;
                    if (is_array($item) && is_array($value)) {
                        $content[$key][$i] = array_merge($item, array_filter($value, function ($v) { return $v !== null && $v !== ''; }));
                    } elseif (is_string($value) && $value !== '') {
                        $content[$key][$i] = $value;
                    }
                }
            } elseif ($key === 'hero_image' || (is_string($saved[$key]) && $saved[$key] !== '')) {
                $content[$key] = $saved[$key];
            }
        }
        return $content;
    }
}
