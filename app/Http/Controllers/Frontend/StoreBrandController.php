<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Footerlevel1;
use App\Models\Contact;
use DB;
class StoreBrandController extends Controller
{
    public function brand($slug)
    {     
         $branddata=Brand::where('slug',$slug)->first();
         $brandid=$branddata->id;
        

         $products=DB::table('products')->where('is_active','online')->where('brand',$brandid);

         if(!empty($_GET['sort'])){

            $sort_by=$_GET['sort'];
                switch ($sort_by) {
                    case '1':
                        $products->orderByRaw('RAND()');
                        $products=$products->paginate(12)->onEachSide(0);
                        break;

                    case '2':
                        $products->orderBy('id', 'desc');
                        $products=$products->paginate(12)->onEachSide(0);
                        break;
                   
                    default:
                        // code...
                    
                        break;
                }
            }
            else
            {
               $products=$products->paginate(12)->onEachSide(0);
            }

         $checkbox_categories=Category::where('is_active','online')->where('brand_id',$brandid)->get();
         $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
          return view('frontend.brand',compact('brands','products','brandid','checkbox_categories'));
    }


    public function listview($slug)
    {
          
          $branddata=Brand::where('slug',$slug)->first();
         $brandid=$branddata->id;
        

         $products=DB::table('products')->where('is_active','online')->where('brand',$brandid);

         if(!empty($_GET['sort'])){

            $sort_by=$_GET['sort'];
                switch ($sort_by) {
                    case '1':
                        $products->orderBy('id', 'asc');
                        $products=$products->paginate(12)->onEachSide(0);
                        break;

                    case '2':
                        $products->orderBy('id', 'desc');
                        $products=$products->paginate(12)->onEachSide(0);
                        break;
                   
                    default:
                        // code...
                    
                        break;
                }
            }
            else
            {
               $products=$products->paginate(12)->onEachSide(0);
            }

         $checkbox_categories=Category::where('is_active','online')->where('brand_id',$brandid)->get();
         $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
          return view('frontend.brandlist',compact('brands','products','brandid','checkbox_categories'));
    }



    
}
