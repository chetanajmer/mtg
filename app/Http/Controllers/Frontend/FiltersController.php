<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Categorybanner;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Tmporder;
use App\Models\Cartorder;
use App\Models\Storeuser;
use App\Models\Shipping;
use App\Models\Orderid;;
use App\Models\Product_setting;
use App\Models\Weightshipping;
use App\Models\Contact;
use App\Models\About;
use App\Models\Color;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Footerlevel1;
use DB;

class FiltersController extends Controller
{
      public function searchbar(Request $request)
      {
            $filter="searchbar";
           
            $searchdata=$request->get('search_val');

            $brands=Brand::where('is_active','online')->where('brandname',$searchdata)->get();

            $categories=Category::where('is_active','online')->where('catname',$searchdata)->get();

            $subcategories=Subcategory::where('is_active','online')->where('catname',$searchdata)->get();

            foreach($brands as $val)
            {
              $brandid=$val->id;
            }

            foreach($categories as $val)
            {
              $catid=$val->id;
            }

            foreach($subcategories as $val)
            {
              $subcatid=$val->id;
            }


            if(!empty($brandid))
            {
                 $query = Product::where('brand',$brandid)->get()->take(3);
            }
            elseif(!empty($catid))
            {
                 $query = Product::where('category',$catid)->get()->take(3);
            }
            elseif(!empty($subcatid))
            {
              $query = Product::where('subcategory',$subcatid)->get()->take(3);
            }
            else
            {
                 $query = Product::where('name','LIKE',"%{$searchdata}%")->orWhere('modelno','LIKE',"%{$searchdata}%")->get()->take(3);
            }
             
            $search_products = $query;
            return response()->json(['data' =>$search_products]);
      }
      

      public function search_bar(Request $request)
      {
            $filter="searchbar";
           
            $searchdata=$request->get('search_val');

            $brands=Brand::where('is_active','online')->where('brandname',$searchdata)->get();

            $categories=Category::where('is_active','online')->where('catname',$searchdata)->get();

            $subcategories=Subcategory::where('is_active','online')->where('catname',$searchdata)->get();

            foreach($brands as $val)
            {
              $brandid=$val->id;
            }

            foreach($categories as $val)
            {
              $catid=$val->id;
            }

            foreach($subcategories as $val)
            {
              $subcatid=$val->id;
            }


            if(!empty($brandid))
            {
                 $query = Product::where('brand',$brandid)->Paginate(12)->onEachSide(0);
            }
            elseif(!empty($catid))
            {
                 $query = Product::where('category',$catid)->Paginate(12)->onEachSide(0);
            }
            elseif(!empty($subcatid))
            {
              $query = Product::where('subcategory',$subcatid)->Paginate(12)->onEachSide(0);
            }
            else
            {
              $query = Product::where('name','LIKE',"%{$searchdata}%")->orWhere('modelno','LIKE',"%{$searchdata}%")->Paginate(12)->onEachSide(0);
            }
             
            $products = $query;

            $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
           
            return view('frontend.search',compact('products','brands','searchdata'));
      }

      public function searching($search_val)
      {
            $filter="searchbar";
           
            $searchdata=$search_val;

            $brands=Brand::where('is_active','online')->where('brandname',$searchdata)->get();

            $categories=Category::where('is_active','online')->where('catname',$searchdata)->get();

            $subcategories=Subcategory::where('is_active','online')->where('catname',$searchdata)->get();

            foreach($brands as $val)
            {
              $brandid=$val->id;
            }

            foreach($categories as $val)
            {
              $catid=$val->id;
            }

            foreach($subcategories as $val)
            {
              $subcatid=$val->id;
            }


            if(!empty($brandid))
            {
                 $query = Product::where('brand',$brandid)->Paginate(12)->onEachSide(0);
            }
            elseif(!empty($catid))
            {
                 $query = Product::where('category',$catid)->Paginate(12)->onEachSide(0);
            }
            elseif(!empty($subcatid))
            {
              $query = Product::where('subcategory',$subcatid)->Paginate(12)->onEachSide(0);
            }
            else
            {
              $query = Product::where('name','LIKE',"%{$searchdata}%")->orWhere('modelno','LIKE',"%{$searchdata}%")->Paginate(12)->onEachSide(0);
            }
             
            $products = $query;

            $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
           
            return view('frontend.search',compact('products','brands','searchdata'));
      }

      public function attfilter(Request $request)
      {
         
          $data=$request->all();
         
          $subcategoryUrl="";
          if(!empty($data['subcategoryfilter']))
          {
            
            foreach($data['subcategoryfilter'] as $subcategory)
            {
              
              if(empty($subcategoryUrl))
              {
                $subcategoryUrl="&subcategory=".$subcategory;
              }
              else 
              {
                $subcategoryUrl .= "-".$subcategory;
              }

            }

          }

          $sortUrl="";
          if(!empty($data['sort']))
          {
            $sort=$data['sort'];         
            $sortUrl="&sort=".$sort;
                  
          }
          
          $finalUrl="/".$data['url']."?".$subcategoryUrl.$sortUrl;
          return redirect::to($finalUrl);  

      } 
}