<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tmporder;
use App\Models\Cartorder;
use App\Models\Storeuser;
use App\Models\Shipping;
use App\Models\Orderid;;
use App\Models\Product_setting;
use App\Models\Weightshipping;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Mail;
use Carbon\carbon;



class OrderController extends Controller
{

    public function abandoned_order(Request $request)
    {
      $items_array=Tmporder::whereNull('ordercomplete')->orderbyDesc('id')->get();
      return view('backend.orders.abandoned_list',compact('items_array'));
    }

    public function abandoned_orderdetails($id)
    {
      $orders=Tmporder::where('id',$id)->get();
      return view('backend.orders.abandoned_orderdetails',compact('orders'));

    }

    public function send_abandoned_mail(Request $request)
    {
    	$userid=$request->id;
    	$getusermail=Storeuser::where('id',$userid)->first();
    	$useremail=$getusermail->email;

    	$details = array(
          'email'=>$useremail,
      );

        // $user['to']=$useremail;
        // $user['from']="richa@silverpixelz.com";
        // Mail::send('mail/abandoned_cart_mail', $data, function($message) use ($user) {
        
        //  $message->to($user['to'], 'Seven Wonder')->subject
        //     ('Abandoned Cart');
        //  $message->from($user['from'],'Seven Wonder');
        //  });

			if(Mail::to('richa@silverpixelz.com')->send(new \App\Mail\Abandonedcartmail($details)))
			{
				echo "email sent";
			}
			else
			{
				echo "email error";
			}	   
    }

}




