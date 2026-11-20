<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A banner strip under the header for one page (admin: Banners > Page Banners). */
class PageBanner extends Model
{
    const UPLOAD_FOLDER = 'upload/page-banners';

    protected $fillable = ['page', 'image', 'alt', 'link', 'is_active', 'ranking'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('id');
    }

    public function imageUrl()
    {
        return asset($this->image);
    }

    public function deleteUploadedImage()
    {
        if (strpos($this->image, self::UPLOAD_FOLDER . '/') === 0 && is_file(public_path($this->image))) {
            @unlink(public_path($this->image));
        }
    }

    /** Which page the current address is, from config/page_banners.php; 'default' when none matches. */
    public static function pageFor($request)
    {
        foreach (config('page_banners.pages') as $key => $page) {
            if ($page['paths'] && $request->is(...$page['paths'])) {
                return $key;
            }
        }
        return 'default';
    }

    /**
     * The banner for this visit: one of the page's switched-on banners (or the Default ones), never the same
     * one twice in a row for this visitor when there is more than one. Null shows no strip.
     */
    public static function pick($request)
    {
        $page = self::pageFor($request);
        $banners = self::where('page', $page)->where('is_active', true)->get();
        if ($banners->isEmpty() && $page !== 'default') {
            $page = 'default';
            $banners = self::where('page', $page)->where('is_active', true)->get();
        }
        if ($banners->isEmpty()) {
            return null;
        }

        $sessionKey = 'page_banner_last.' . $page;
        $last = $request->hasSession() ? $request->session()->get($sessionKey) : null;
        $choices = $banners->count() > 1 ? $banners->where('id', '!=', $last) : $banners;
        $banner = $choices->random();
        if ($request->hasSession()) {
            $request->session()->put($sessionKey, $banner->id);
        }
        return $banner;
    }
}
