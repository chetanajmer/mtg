<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_name',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description'
    ];

    // The file's place is not shown to the page; it opens through file_url, which checks the owner
    protected $hidden = ['file_path'];
    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return route('branding-assets.file', $this->id);
    }

    /**
     * The stored file: private storage (storage/app/...), or the public folder for a file uploaded before
     * assets were made private and not moved yet. Null if missing.
     */
    public function absolutePath()
    {
        foreach ([storage_path('app/' . $this->file_path), storage_path('app/public/' . $this->file_path)] as $path) {
            if ($this->file_path && is_file($path)) {
                return $path;
            }
        }
        return null;
    }

    public function deleteFile()
    {
        foreach ([storage_path('app/' . $this->file_path), storage_path('app/public/' . $this->file_path)] as $path) {
            if ($this->file_path && is_file($path)) {
                @unlink($path);
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Storeuser::class, 'user_id');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }
}
