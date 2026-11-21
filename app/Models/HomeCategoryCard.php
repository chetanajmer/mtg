<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A card under Search by Category on the homepage (admin: Setup & Config > Home Category Cards). */
class HomeCategoryCard extends Model
{
    const UPLOAD_FOLDER = 'upload/home-category-cards';
    const MAX_LINKS = 4;

    protected $fillable = ['category_id', 'title', 'image', 'subcategory_ids', 'is_active', 'ranking'];

    protected $casts = ['subcategory_ids' => 'array', 'is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ranking')->orderBy('id');
    }

    public function imageUrl()
    {
        return asset($this->image);
    }

    /** Deletes the picture, only if it is one the admin uploaded. */
    public function deleteUploadedImage()
    {
        if (strpos($this->image, self::UPLOAD_FOLDER . '/') === 0 && is_file(public_path($this->image))) {
            @unlink(public_path($this->image));
        }
    }

    /**
     * The cards the homepage shows: switched on, with a category that is online. Each card's links are
     * the chosen subcategories that are still online, or, if none were chosen, the category's first four.
     * @return array of ['title', 'image', 'url', 'links' => [['label', 'url']]]
     */
    public static function forHome()
    {
        $cards = self::where('is_active', true)->ordered()
            ->whereHas('category', function ($q) { $q->where('is_active', 'online'); })
            ->with('category')->get();
        $subcategories = Subcategory::where('is_active', 'online')->whereIn('catid', $cards->pluck('category_id')->all())
            ->orderBy('ranking')->orderBy('id')->get(['id', 'catid', 'catname', 'slug'])->groupBy('catid');

        return $cards->map(function ($card) use ($subcategories) {
            $own = $subcategories->get($card->category_id, collect());
            $chosen = array_map('intval', $card->subcategory_ids ?: []);
            $links = $chosen
                ? $own->whereIn('id', $chosen)->sortBy(function ($sub) use ($chosen) { return array_search($sub->id, $chosen); })
                : $own;
            return [
                'title' => $card->title ?: $card->category->catname,
                'image' => $card->imageUrl(),
                'url' => url('category/' . $card->category->slug),
                'links' => $links->take(self::MAX_LINKS)->map(function ($sub) {
                    return ['label' => $sub->catname, 'url' => url('subcategory/' . $sub->slug)];
                })->values()->all(),
            ];
        })->all();
    }
}
