<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\Parentcategory;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Discount_coupon;
use DB;
use Image;

class CouponController extends Controller
{
  public function list()
  {
    $items_array=Coupon::where('is_active','online')->paginate(10); 
    return view('backend.coupon.list-coupons',compact('items_array'));  
  }

  public function index()
  {
    $parentcategories=Parentcategory::where('is_active','online')->get();
    $categories=Category::where('is_active','online')->get();
    $subcategories=Subcategory::where('is_active','online')->get();
    $products=Product::where('is_active','online')->get();
    return view('backend.coupon.add-coupons',compact('parentcategories','categories','subcategories','products'));
  }

  public function add(Request $request)
  {
    // echo "<pre>";print_r($_POST);die; 
    $item=new Coupon;
    $item->coupon_code=$request->coupon_code;
    $item->coupon_type=$request->coupon_type;
    $item->coupon_amount=$request->coupon_amount;
    $item->coupon_max_amount=$request->coupon_max_amount;
    $item->limits=$request->limit;
    $item->expiry_date=$request->expiry_date;
    $item->free_delivery=$request->free_delivery;
    $item->coupon_based=$request->active_coupon;

    if(!empty($request->cat))
    {
      $item->category=implode(",",$request->cat);
    }
    
    $item->is_active=$request->is_active;
    $createslug=$this->slug($request->coupon_code);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
    $item->save();
    $request->session()->flash('success','Added Successfully !');
    return Redirect::to('list-coupon');
  }

  public function edit($slug)
  {
    $parentcategories=Parentcategory::where('is_active','online')->get();
    $categories=Category::where('is_active','online')->get();
    $subcategories=Subcategory::where('is_active','online')->get();
    $products=Product::where('is_active','online')->get();
    $items =DB::Table('coupons')->select('*')->where('slug',$slug)->first();
    return view('backend.coupon.edit-coupons',compact('items','categories','products','parentcategories','subcategories'));
  }

  public function updatecoupon(Request $request)
  {
    // echo "<pre>";print_r($_POST);die;
    $slug=$request->slug;
    $id=$request->get('id');
    if(!empty($request->cat))
    {
      $category=implode(",",$request->cat);
      DB::table('coupons')->where('slug',$slug)->update(['category' => $category]);
    }
        
    DB::table('coupons')
    ->where('slug',$slug)
    ->update(['coupon_code' => $request->coupon_code,
              'coupon_amount' => $request->coupon_amount,
              'coupon_max_amount' => $request->coupon_max_amount,
              'is_active' => $request->is_active,
              'coupon_type' => $request->coupon_type,
              'limits' => $request->limits,
              'expiry_date' => $request->expiry_date, 
              'slug' => $request->slug,
              'coupon_based' =>$request->active_coupon,
         ]);        
    $request->session()->flash('success','Saved Successfully!');
    return Redirect::to('list-coupon');  
  }

  public function deletecoupon(Request $request)
  {
    $slug=$request->slug;
    DB::table('coupons')->where('slug',$slug)->delete();
    session()->flash('success','Deleted Successfully!');
    return Redirect::to('list-coupon'); 
  }

  public function getinputvalue(Request $request)
  {
    $inputvalue=$request->inputvalue;
    if($inputvalue=='parentcategory')
    {
      $data=Parentcategory::where('is_active','online')->select(\DB::raw("catname as name,id"))->get();
      $labelname='Parent Categories';
    }

    if($inputvalue=='category')
    {
      $data=Category::where('is_active','online')->select(\DB::raw("catname as name,id"))->get();
      $labelname='Categories';
    }

    if($inputvalue=='subcategory')
    {
      $data=Subcategory::where('is_active','online')->select(\DB::raw("catname as name,id"))->get();
      $labelname='Sub Categories';
    }

    if($inputvalue=='product')
    {
      $data=Product::where('is_active','online')->get();
      $labelname='Products';
    }

    if($inputvalue=='none')
    {
      $data='';
      $labelname='';
    }
    return response()->json(['data' =>  $data ,'labelname'=>$labelname]);

  }
    public function coupon_code(Request $request)
    {
        $coupon_name=$request->coupon_code;
        $sessionid=session()->get('sessionid');
        $userid=session()->get('userid');
        if(!empty($userid))
        {
              $coupon=DB::Table('coupons')->select('*')->where('coupon_code',$coupon_name)->first();
              if(!empty($coupon))
              {
                $coupon_products=$coupon->products;
                $coupon_category=$coupon->category;
                $coupon_limit=$coupon->limits;
                $coupon_date=$coupon->expiry_date;
                $date = date("Y-m-d");
                if($date>$coupon_date)
                {
                   return response()->json(['status'=>'error','msg'=>"Sorry, this coupon is not valid."]);
                }
                else
                {
                  if(!empty($coupon_products))
                  {
                     $product=explode(',',$coupon_products);
                     $check_products=\App\Models\product::whereIn('modelno',$product)->get();
                     foreach($check_products as $items)
                     {
                       $modelno[]=$items->modelno;
                     }
                  }

                  if(!empty($coupon_category))
                  {
                    $category=explode(',',$coupon_category);
                    $check_products=\App\Models\product::whereIn('category',$category)->get();
                    foreach($check_products as $items)
                     {
                       $modelno[]=$items->modelno;
                     }
                  }

                   $modelno=implode(',', $modelno);
                   $product_model=explode(',',$modelno);
                  
                   $tmporders=\App\Models\tmporder::where('sessionid',$sessionid)->whereIn('modelno',$product_model)->get();
                   
                    if(count($tmporders)>0)
                    {
                      $check_coupon=DB::Table('discount_coupon')->select('*')->where('coupon',$coupon_name)->where('userid',$userid)->where('orderid','!=','')->get();

                        if(count($check_coupon)>=$coupon_limit)
                        {
                          return response()->json(['status'=>'error','msg'=>"Coupon Limit Exceeded"]);
                        }
                        else
                        {
                          if(count($check_coupon)>0)
                          {
                            return response()->json(['status'=>'error','msg'=>"Coupon Code Already Applied"]);
                          }
                          else
                          {
                            $check_coupon_exists=DB::Table('discount_coupon')->select('*')->where('coupon',$coupon_name)->where('userid',$userid)->where('sessionid',$sessionid)->get();
                            if(count($check_coupon_exists)>0)
                            {
                                 DB::table('Discount_coupon')
                              ->where('sessionid',$sessionid)
                              ->update(['coupon' => $coupon_name]);
                              return response()->json(['status'=>'success','msg'=>"Coupon Code Applied Successfully"]);
                            }
                            else
                            {
                             
                                $discount_coupon=new Discount_coupon;
                                $discount_coupon->userid=$userid;
                                $discount_coupon->sessionid=$sessionid;
                                $discount_coupon->coupon=$coupon_name;
                                $discount_coupon->coupon_limit=1;
                                $discount_coupon->save();
                                return response()->json(['status'=>'success','msg'=>"Coupon Code Applied Successfully"]);
                            }
                            
                          }
                          
                        }
                        
                    }
                    else
                      {
                        return response()->json(['status'=>'error','msg'=>"Sorry, this coupon is not applicable to selected products."]);
                      }
                  
                }
              }
              else
              {
                return response()->json(['status'=>'error','msg'=>"Sorry, this coupon is not valid."]);
              }
          
          
            
        }
        else
        {
            return response()->json(['status'=>'error','msg'=>"Please Login"]);
        }

            
    }

    public function remove_coupon_code(Request $request)
    {
        $coupon_id='';
        $sessionid=session()->get('sessionid');
        DB::delete('delete from discount_coupon where sessionid = ?',[$sessionid]);
             $msg="Coupon Code Remove Successfully.";
             return $msg;
    }

    public function slug($string)
    {
        $string = str_replace(' ', '-', $string);
        $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
        return preg_replace('/-+/', '-', $string);    
    }

}
