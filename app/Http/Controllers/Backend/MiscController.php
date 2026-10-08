<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Categorybanner;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Category_setting;
use App\Models\Parentcategory;
use App\Models\Brand;
use App\Models\Tmporder;
use App\Models\Cartorder;
use App\Models\Storeuser;
use App\Models\Shipping;
use App\Models\Orderid;;
use App\Models\Product_setting;
use App\Models\Weightshipping;
use App\Models\Contact;
use App\Models\About;
use App\Models\Wishlist;
use App\Models\Color;
use App\Models\Subscribe;
use App\Models\Footerlevel1;
use App\Models\Corporatepage;
use App\Models\User_reward;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use DB;
use Mail;

class MiscController extends Controller
{
    
    public function postcontact(Request $request)
    {

        $name=$request->first_name;
        $phone=$request->phone;
        $email=$request->email_address;
        $subject=$request->contact_subject;
        $msg=$request->message;

        $data=array('name'=>$name,'phone'=>$phone,'email'=>$email,'subject'=>$subject,'msg'=>$msg);
        $user['to']=$request->email;
        $user['from']="richa@silverpixelz.com";
       
        Mail::send('mail/usercontactmail', $data, function($message) use ($user) {
        
         $message->to($user['to'], 'Seven Wonders')->subject
            ('Contact Information');
         $message->from($user['from'],'Seven Wonders');
         });

        Mail::send('mail/admincontactmail', $data, function($message) use ($user){
         $message->to($user['from'], 'Seven Wonders')->subject
            ('Contact Information');
         $message->from($user['from'],'Seven Wonders');
        });

        return "Your Message has been sent, we will get back to you soon !";
       
    }
   

   
    
}