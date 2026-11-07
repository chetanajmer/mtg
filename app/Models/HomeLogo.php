<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeLogo extends Model
{
    const TYPES = ['brand' => 'Our Brands', 'client' => 'Our Clientele'];
    const UPLOAD_FOLDER = 'upload/home-logos';

    protected $fillable = ['type', 'name', 'logo', 'is_active', 'ranking'];

    protected $casts = ['is_active' => 'boolean'];

    public function logoUrl()
    {
        return asset($this->logo);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('name');
    }

    /**
     * Deletes the picture, only if it is one the admin uploaded.
     */
    public function deleteUploadedLogo()
    {
        if (strpos($this->logo, self::UPLOAD_FOLDER . '/') === 0 && is_file(public_path($this->logo))) {
            @unlink(public_path($this->logo));
        }
    }
}
