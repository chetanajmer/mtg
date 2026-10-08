<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = ['id','name','modelno','sku','brand','parentcategory','category','subcategory' ,'childsubcategory','shortdescription' ,'description' ,'specification' ,'price' ,'sprice' ,'quantity' ,'stock' ,'low_stock' ,'weight' ,'ranking' ,'is_active' ,'newarrival' ,'sale' ,'most_selling' ,'video' ,'pdf' ,'thumbnail' ,'image1' ,'image2' ,'image3', 'image4' ,'image5' ,'image6' ,'metatitle','metakey' ,'metadesc' ,'tax' ,'slug','master_product' ,'variant_name','product_warranty' ,'date_sale_price_start' ,'date_sale_price_ends' ,'installation_title' ,'installation_opt_title' ,'installation_opt_price' ,'related_product','refurbished_product'
    ];


    

        public $timestamps=false;

}
