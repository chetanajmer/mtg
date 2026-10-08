<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storeuser;
use App\Models\Cartorder;
use App\Models\General_setting;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Mail;
use Illuminate\Support\Str;
use App\Models\Footerlevel1;
use App\Models\Contact;

class UsersController extends Controller
{
    public function storelogin(Request $request)
    {

    	//$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
    	return view('frontend.login');
    }

    public function dostorelogin(Request $request)
    {

      $email=$request->get('email');
      $password=$request->get('password');
      $checkuser=DB::table('storeusers')->select('*')->where('email', $email)
      ->where('password',$password)->first();
      
      if(!empty($checkuser))
      {
        //print_r($checkuser->id); die;  
        $userid=$checkuser->id; 
        $useremail=$checkuser->email;
        session()->put('userid',$userid);
        session()->put('username',$useremail);
        $request->session()->flash('loginsuccess','Login Successfull !');
         return Redirect::to('store/myaccount');
         
      }
      else
      {
          $useremail=DB::table('storeusers')->select('*')->where('email', $email)->first();
          if(!empty($useremail))
          {
              $request->session()->flash('loginerror','Invalid Password');
              return Redirect::back();
          }
          else
          {
              $request->session()->flash('loginerror','You are not Registered !');
              return Redirect::back();
          }  
          $request->session()->flash('loginerror','Invalid Details !');
          return Redirect::back();

       }   
    }

    public function storeregister(Request $request)
    {

    	//$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
    	return view('frontend.register');
    }

    public function dostoreregister(Request $request)
    {
    	//print_r($_POST);die;

    	$email=$request->email;
    	$password=$request->password;

        $checkuser=DB::table('storeusers')->select('*')->where('email', $email)->first();
        
        if(!empty($checkuser))
        {	
         
            $request->session()->flash('error','Email Already Registered !');
            return Redirect::back();
        }
        else
        {
        	$user=new Storeuser;
         	$user->email=$email;
         	$user->fname=$request->firstname;
         	$user->lname=$request->lastname;
         	$user->password=$request->password;
         	$user->offersopted=$request->offers;
         	$user->newsletteropted=$request->newsletter;
         	$user->save();

         	$general_settings=General_setting::where('id','1')->first();

         	$admin_email=$general_settings->mailfromemail;
         	$mail_from=$general_settings->mailname;


         	$details = [
          	'email'=>$email,
          	'password'=>$password,
          	'$admin_email'=>$admin_email,
          	'mail_from'=>$mail_from,
        	 ];

    		Mail::to($email)->send(new \App\Mail\Registrationemail($details));

        }

          $request->session()->flash('success','Registered successfully !');
          return Redirect::to('store/login');
    	
    }


    public function myaccount(Request $request)
    {

    	$userid=session()->get('userid');
    	$roles=session()->get('role');
        $footerlevel1=Footerlevel1::findorFail(1);	
        $contact=Contact::where('id','1')->first();
      if(!empty($roles))
      {
        echo "You are Logged in as Administrator, please logout and login as store User";
      }
      else 
      {  

            if(!empty($userid))
    	      {	
              $items_array=Cartorder::orderbyDesc('id')->where('userid',$userid)->get()->unique('orderid');
            	$userdetails=DB::table('storeusers')->select('*')->where('id', $userid)->first();
        	    return view('frontend.myaccount',compact('userdetails','items_array','footerlevel1','contact'));
            }
            else
            {
        	    return view('frontend.login',compact('footerlevel1','contact'));	
            }
      }      	
    }


    public function dostorelogout(Request $request)
    {
        
      $request->session()->forget('username');
      $request->session()->forget('userid');
      $footerlevel1=Footerlevel1::findorFail(1);	
      $contact=Contact::where('id','1')->first();
      session()->flash('logoutsuccess','Logged Out Successfully!'); 
      return view ('frontend.login',compact('footerlevel1','contact'));
    }


    public function resetpassword(Request $request)
    {
    	return view('frontend.resetpassword');
   	}
   	

    public function doresetpassword(Request $request)
    {
   // print_r($_POST);die;
          

         $email=$request->get('email');

         $checkuser=DB::table('storeusers')->select('*')->where('email', $email)->first();
         if(!empty($checkuser))
         {

            $password=Str::random(10);

              DB::table('storeusers')
                        ->where('email',$email)
                        ->update([
                            'password' => $password,
                          ]);


            /*$data = array(
            'name'=>$email,
            'password'=>$password,);

            Mail::send('resetmail', $data, function($message) use ($email) {
            $message->to($email, 'Sara')->subject('Sara Arabia ');
             $message->from('customersupport@saraarabia.com','Sara Arabia');
             });

            DB::table('customers')
                        ->where('email',$email)
                        ->update([
                            'password' => $password,
                          ]);*/
        

            $request->session()->flash('success','Password Sent to Your Email Address, Please also check Spam Folder ');
            return view('frontend.resetpassword');
         }
         else
         {
            $request->session()->flash('error','Email Not Registered, Please SignUp');
            return view('frontend.resetpassword');
         } 
       
    }

    public function updateuserdetails(Request $request)
    {
return "Details Updated Succesfully !" ;  
        return $request->id;
       DB::table('storeusers')
                        ->where('id',$request->id)
                        ->update([
                            'fname' => $request->fname,
                            'lname' => $request->lname,
                            'mobile' => $request->mobile,
                            'email' => $request->email,
                            'flatno' => $request->flatno,
                            'city' => $request->city,
                            'region' => $request->region,
                            'country' => $request->country,
                            'password' => $request->password,
                            'address' => $request->address,

                          ]);


      return "Details Updated Succesfully !" ;                 

    }

    public function storeuser()
    {
        $items_array=Storeuser::orderbyDesc('id')->get();
        return view('backend.users.storeuser',compact('items_array'));
    }

    

}
