<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = ['id','name','subheading','modelno','suppliercode','sku','brand','parentcategory','category','subcategory' ,'childsubcategory','shortdescription' ,'description' ,'specification' ,'price' ,'sprice' ,'quantity' ,'stock' ,'low_stock' ,'weight' ,'ranking' ,'is_active' ,'newarrival' ,'sale' ,'most_selling' ,'video' ,'pdf' ,'thumbnail' ,'thumbnail2' ,'image1' ,'image2' ,'image3', 'image4' ,'image5' ,'image6' ,'image7' ,'image8' ,'bulk_image' ,'metatitle','metakey' ,'metadesc' ,'title' ,'og_type' ,'og_url' ,'twitter_card' ,'twitter_url' ,'tax' ,'slug','choice_options' ,'colors' ,'variations' ,'support_heading1' ,'support_pdf1' ,'support_heading2' ,'support_pdf2' ,'support_heading3' ,'support_pdf3' ,'filters' ,'product_availability' ,'country_origin' ,'unit_size' ,'no_of_pack' ,'packaging_length' ,'packaging_width' ,'packaging_height' ,'packaging_weight' ,'packaging_cbm' ,'min_order_quantity' ,'estimated_delivery_time' ,'services' ,'master_product' ,'variant_name','product_warranty' ,'date_sale_price_start' ,'date_sale_price_ends' ,'installation_title' ,'installation_opt_title' ,'installation_opt_price' ,'related_product','refurbished_product','catlogue_no','rating','review_count','reviews_enabled','specifications','moq_unit','tier_pricing_enabled','branding_options','occasion_tags','preferences','ai_tags','base_price','exhibition_events'
    ];


    

        public $timestamps=false;

    /**
     * Unit price for a given order quantity. When tier pricing is enabled,
     * uses the matching quantity slab (product_tier_prices); otherwise the
     * sale price when it is lower, otherwise the normal price. This matches
     * what the product page shows.
     */
    public function priceForQuantity($quantity)
    {
        $quantity = max(1, (int) $quantity);

        $tier = $this->tier_pricing_enabled !== 'yes' ? null : \DB::table('product_tier_prices')
            ->where('product_id', $this->id)
            ->where('price', '>', 0) // a tier without a real price never applies
            ->where('min_qty', '<=', $quantity)
            ->where(function ($query) use ($quantity) {
                $query->whereNull('max_qty')->orWhere('max_qty', '>=', $quantity);
            })
            ->orderByDesc('min_qty')
            ->first();

        if ($tier) {
            return (float) $tier->price;
        }

        return $this->displayPrice();
    }

    /**
     * URL of the product's main image, or null when no image file exists.
     * MidOcean products (brand 42) store full image URLs; others store file
     * names under public/upload/product (same rules as the product page).
     */
    public function imageUrl()
    {
        $candidates = array_filter(array_map('trim', array_merge(
            [(string) $this->thumbnail, (string) $this->image1],
            explode(',', (string) $this->bulk_image)
        )));

        foreach ($candidates as $file) {
            if (preg_match('#^https?://#i', $file)) {
                return $file;
            }
            // Path already relative to public/ (e.g. "web_frontend/assets/images/x.jpg")
            if (strpos($file, '/') !== false &&is_file(public_path(ltrim($file, '/')))) {
                return asset(ltrim($file, '/'));
            }
            foreach (['upload/product/thumbnail/', 'upload/product/'] as $folder) {
                if (is_file(public_path($folder . $file))) {
                    return asset($folder . rawurlencode($file));
                }
            }
        }

        return null;
    }

    /**
     * Smallest quantity a customer may order (at least 1).
     */
    public function minOrderQuantity()
    {
        return max(1, (int) $this->min_order_quantity);
    }

    /**
     * Price shown to customers when no tier applies: the Base Price, or for
     * products without one, the sale price when lower, else the price field.
     */
    public function displayPrice()
    {
        if (!empty($this->base_price) && $this->base_price > 0) {
            return (float) $this->base_price;
        }

        if (!empty($this->sprice) && $this->sprice > 0 && $this->sprice < $this->price) {
            return (float) $this->sprice;
        }

        return (float) ($this->price ?? 0);
    }

}
