<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Admin;
use App\Models\Midocean_product;
use App\Models\Searchproduct;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Carbon\Carbon;
use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;

class AdminController extends Controller
{
  public function adminusers(Request $request)
  {
    $items_array=Admin::where('role','!=','superadmin')->get();
    // echo "<pre>";print_r($items_array);die;
    return view('backend.users.list',compact('items_array'));
  }

  public function add(Request $request)
  {
    $admins=Admin::where('email',$request->email)->first();
    if(!empty($admins))
    {
      $msg="Email Id Already Exists ! ";
    }  
    else
    {
        $item=new Admin;
       $item->name=$request->name;
       $item->email=$request->email;
       $item->password=$request->password;
       $item->role=$request->role;
       $item->brand_status=$request->brand_status;
       $item->parentcategory_status=$request->parentcategory_status;
       $item->category_status=$request->category_status;
       $item->product_status=$request->product_status;
       $item->order_status=$request->order_status;
       $item->slider_status=$request->slider_status;
       $item->subscriber_status=$request->subscriber_status;
       $item->banner_status=$request->banner_status;
       $item->config_status=$request->config_status;
       $item->save();

       $msg="Successfully Added !";
      }  

       

       
       return $msg;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }

    public function find(Request $request)
    {

      
    	$items=Admin::where('id',$request->id)->first();
       
       //$msg="Successfully Added !";
       return $items;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }


    public function update(Request $request)
    {
        // echo "<pre>";print_r($_POST);die;
        $id=(int)$request->id;

        DB::table('admins')
        ->where('id',$id)
        ->update(['name' => $request->name,
                    'password' => $request->password,
                    'email' => $request->email,
                    'role' => $request->role,
                    'brand_status' =>$request->brand_status,
                    'parentcategory_status' => $request->parentcategory_status,
                    'category_status' =>$request->category_status,
                    'product_status' =>$request->product_status,
                    'order_status' =>$request->order_status,
                    'slider_status' =>$request->slider_status,
                    'subscriber_status' =>$request->subscriber_status,
                    'banner_status' =>$request->banner_status,
                    'config_status' =>$request->config_status
                             ]);  

       $msg="Updated Successfully !";
       return $msg;                    


    }   


    public function delete(Request $request)
    {
      $id=(int)$request->id;
      Admin::where('id', $id)->delete();
      $msg="Deleted !";
      return $msg;                    
    }  


    public function dashboard(Request $request)
    {
      $user=LaravelGoogleAnalytics::getTotalUsersByDate(Period::days(7));
      $country=LaravelGoogleAnalytics::getTotalUsersByCountry(Period::days(7));
      $newandreturningusers=LaravelGoogleAnalytics::getTotalNewAndReturningUsers(Period::days(7));
      $most_views_page=LaravelGoogleAnalytics::getMostViewsByPage(Period::days(7), $count =20);
      
      if(!empty( $newandreturningusers[0]['totalUsers']))
      {
        $newuser= $newandreturningusers[0]['totalUsers'];  
      }
      else
      {
        $newuser=0;
      }
      if(!empty( $newandreturningusers[1]['totalUsers']))
      {
        $returninguser= $newandreturningusers[1]['totalUsers'];
      }
      else
      {
        $returninguser=0;
      }
      return view('backend.dashboard.dashboard',compact('user','newuser','returninguser','country','most_views_page'));
    }



    public function login(Request $request)
    {

      return view('backend.dashboard.login');
    }

    public function doadminlogin(Request $request)
    {
      $admins=Admin::where('email',$request->email)->where('password',$request->password)->first();
      if(!empty($admins))
      {

            Session::put('username', $admins->email);
            Session::put('userid', $admins->id);
            Session::put('role', $admins->role);
            Session::put('pcat', $admins->parentcategory);
            return Redirect::to('dashboard');
      } 
      else
      {
            $request->session()->flash('error','Invalid Details !'); 
            return Redirect::to('admin');
      }  
     // return view('backend.dashboard.login');
    }

    public function adminlogout(Request $request)
    {
         session()->forget('username');
          session()->forget('userid');
           session()->forget('role');
           $request->session()->flash('success','Logged Out  !'); 
           return Redirect::to('admin');
    }

    public function search_monthly(Request $request)
    {
      $search=Searchproduct::whereMonth('created_at', Carbon::now()->month)->get()->unique('search_name')->paginate(10);
      return view('backend.dashboard.search',compact('search'));
    }

     public function search_yearly(Request $request)
    {
      $search=Searchproduct::whereYear('created_at', Carbon::now()->year)->get()->unique('search_name')->paginate(10);
      return view('backend.dashboard.search',compact('search'));
    }
}    
