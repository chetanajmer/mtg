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
class StoreCategoryController extends Controller
{
    public function category($slug)
    {
          
          $categorydata=Category::where('slug',$slug)->first();
          $catid=$categorydata->id;
          $brand=$categorydata->brand_id;

        
          $subcategories=Subcategory::where('catid',$catid)->where('is_active','online')->get();
          $catslug=$categorydata->slug;
          $catname=$categorydata->catname;
       

          $products=DB::table('products')->where('is_active','online')->where('brand',$brand);


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
         
       
          $footerlevel1=Footerlevel1::findorFail(1);  
          $contact=Contact::where('id','1')->first();
          $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);

          $checkbox_brands=Brand::where('is_active','online')->where('id',$brand)->get();
          $checkbox_categories=Category::where('is_active','online')->where('brand_id',$brand)->get();
          return view('frontend.category',compact('brands','catslug','catid','catname','subcategories','footerlevel1','contact','checkbox_brands','checkbox_categories','products'));
    }


    public function listview($slug)
    {
          
          $categorydata=Category::where('slug',$slug)->first();
          $catid=$categorydata->id;
          $brand=$categorydata->brand_id;

          $subcategories=Subcategory::where('catid',$catid)->where('is_active','online')->get();
          $catslug=$categorydata->slug;
          $catname=$categorydata->catname;
       

          $products=DB::table('products')->where('is_active','online')->where('category',$catid);
          
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
         
          $footerlevel1=Footerlevel1::findorFail(1);  
          $contact=Contact::where('id','1')->first();
          $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
          $checkbox_brands=Brand::where('is_active','online')->get();
          $checkbox_categories=Category::where('is_active','online')->where('brand_id',$brand)->get();
          return view('frontend.categorylist',compact('catslug','catid','catname','subcategories','footerlevel1','contact','brands','checkbox_categories','checkbox_brands','products'));
    }



    public function subcategory($slug)
    {
          $subcategorydata=SubCategory::where('slug',$slug)->first();
          $subcatid=$subcategorydata->id;
          $subcatslug=$slug;

          $products=DB::table('products')->where('is_active','online')->where('subcategory',$subcatid);

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

          $checkbox_brands=Brand::where('is_active','online')->get();
          $checkbox_categories=Category::where('is_active','online')->get();
          $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
          return view('frontend.subcategory',compact('subcatid','subcatslug','products','brands','checkbox_categories','checkbox_brands'));
    }

    public function subcategorylist($slug)
    {
          $subcategorydata=SubCategory::where('slug',$slug)->first();
          $subcatid=$subcategorydata->id;
          $subcatslug=$slug;

          $products=DB::table('products')->where('is_active','online')->where('subcategory',$subcatid);
          if(!empty($_GET['subcategory']))
          {
                     
                      $subcategory=$_GET['subcategory'];
                      $ids=explode('-',$_GET['subcategory']);
                     // $ids=implode(',',$ids);

                      $products->select('*')
                      ->whereIn('subcategory',$ids);
          }

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

          $checkbox_brands=Brand::where('is_active','online')->get();
          $checkbox_categories=Category::where('is_active','online')->get();
          $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
          return view('frontend.subcategorylist',compact('subcatid','subcatslug','products','brands','checkbox_categories','checkbox_brands'));
    }
}
