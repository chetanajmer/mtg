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
     * Unit price for a given order quantity. Uses the product's quantity
     * slabs (product_tier_prices) when one matches, otherwise the sale price
     * when it is lower, otherwise the normal price. This matches what the
     * product page shows.
     */
    public function priceForQuantity($quantity)
    {
        $quantity = max(1, (int) $quantity);

        $tier = \DB::table('product_tier_prices')
            ->where('product_id', $this->id)
            ->where('min_qty', '<=', $quantity)
            ->where(function ($query) use ($quantity) {
                $query->whereNull('max_qty')->orWhere('max_qty', '>=', $quantity);
            })
            ->orderByDesc('min_qty')
            ->first();

        if ($tier) {
            return (float) $tier->price;
        }

        if (!empty($this->sprice) && $this->sprice > 0 && $this->sprice < $this->price) {
            return (float) $this->sprice;
        }

        return (float) ($this->price ?? 0);
    }

}
