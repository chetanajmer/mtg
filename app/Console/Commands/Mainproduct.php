<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Midocean_product;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Color;
class Mainproduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $products=DB::table('products')->where('brand','42')->orderBy('id')->chunk(200, function ($products) {
      foreach($products as $prod)
      {
        $checkproduct=DB::table('midocean_products')->where('suppliercode',$prod->suppliercode)->first();
        if(!empty($checkproduct))
        {
          $name=$checkproduct->name;
          $shortdescription=$checkproduct->shortdescription;
          $description=$checkproduct->description;
          $bulk_image=$checkproduct->bulk_image;
          $is_active=$checkproduct->is_active;
          $newarrival=$checkproduct->newarrival;
          $featured=$checkproduct->featured;
          $sale=$checkproduct->sale;
          $most_selling=$checkproduct->most_selling;
          $slug=$checkproduct->slug;
          $bulk_image=$checkproduct->bulk_image;
          $subcategory=$checkproduct->subcategory;
          $category=$checkproduct->category;
          $colors=$checkproduct->colors;
          $img=explode(',',$checkproduct->bulk_image);
          $thumb=$img[0];
          if(!empty($img[1]))
          {
            $thumb2=$img[1];
          }
          else
          {
            $thumb2=$img[0];
          }
          DB::table('products')
            ->where('suppliercode',$checkproduct->suppliercode)
            ->update([
                    'name' => $name,
                    'shortdescription' => $shortdescription,
                    'description' =>$description,
                    'bulk_image' => $bulk_image,
                    'is_active' =>$is_active,
                    'newarrival' =>$newarrival,
                    'featured' =>$featured,
                    'sale' => $sale,
                    'most_selling' => $most_selling,
                    'slug' => $slug,
                    'thumbnail' => $thumb,
                    'thumbnail2' => $thumb2,
                    'subcategory' =>$subcategory,
                    'category' =>$category,
                    'colors' =>$colors,
                    'updated_at' =>date('Y-m-d H:i:s'),
                ]);
            } 
            else
            {
                DB::table('products')->where('suppliercode',$prod->suppliercode)->delete();
                // echo $prod->suppliercode.'<br>';
            }
  }  
    });
    }
}
