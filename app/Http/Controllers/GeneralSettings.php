<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\General_setting;
use App\Models\Seo_setting;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product_setting;
use App\Models\Reward_setting;
use App\Models\Footerlevel1;
use App\Models\Myproduct;
use App\Models\Product;
use App\Models\Corporatepage;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Image;
use Socialite;
use App\Models\Storeuser;
use Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;

class GeneralSettings extends Controller
{
        
    public function cms(){

        $items=General_setting::where('id','1')->first();
        return view('settings.settings',compact('items'));
    }



    public function updatecms(Request $request){

        DB::table('general_settings')
        ->where('id','1')
        ->update(['site_name' => $request->site_name,
                    'cmsshortname' => $request->cmsshortname,
                    'admincolor' => $request->admincolor,
            

         ]);        
        return Redirect::back();
    }


    public function seosettings(Request $request){

        $items=Seo_setting::where('id','1')->first();
        return view('settings.seo-settings',compact('items'));
    }

    public function updateseosettings(Request $request){

        DB::table('seo_settings')
        ->where('id','1')
        ->update(['metatitle' => $request->metatitle,
                    'metakey' => $request->metakey,
                    'metadesc' => $request->metadesc,
                    'sitetagline' => $request->sitetagline,
                    'headerscript' => $request->headerscript,
                    'bodyscript' => $request->bodyscript,
             ]);      


        $request->session()->flash('success','Seo Settings Updated Successfully !');    
        return Redirect::back();


    }


    public function header(Request $request){

        $items=Header_setting::where('id','1')->first();
        return view('settings.header',compact('items'));
    }

    

    public function updateheader(Request $request){


        //print_r($_POST);die;

        $topbarstatus=$request->topbarstatus;
        if($topbarstatus=="on")
        {
            $topbarstatus="on";
        }
        else 
        {
            $topbarstatus="off";
        }    

        DB::table('header_settings')
        ->where('id','1')
        ->update(['topbarstatus' => $topbarstatus,
                    'topbarcolor' => $request->topbarcolor,
                    'topbarmsg' => $request->topbarmsg,
                    'headerphone' => $request->headerphone,
                    'headeremail' => $request->headeremail,
             ]);      


        $image1=$request->image1;
        $image2=$request->image2;
      

        if(!empty($image1))
        {
           
            $imageName1 = 'favicon'.rand(100,1000).'.'.$request->image1->extension();  
            $request->image1->move(public_path('upload/favicon/'), $imageName1);
            //$item->image1=$imageName1;

             DB::table('header_settings')
            ->where('id','1')
            ->update(['favicon' => $imageName1]);
            
        }    

        if(!empty($image2))
        {
            $imageName2 = 'logo'.rand(100,1000).'.'.$request->image2->extension();  
            $request->image2->move(public_path('upload/logo/'), $imageName2);
            //$item->image2=$imageName2;

             DB::table('header_settings')
            ->where('id','1')
            ->update(['headerlogo' => $imageName2]);
            
        } 

        $request->session()->flash('success','Header Settings Updated Successfully !');    
        return Redirect::back();
    }




    public function footerlevel1(Request $request){

        $items=Footerlevel1::where('id','1')->first();
        return view('settings.footerlevel1',compact('items'));
    }

    public function updatefooterlevel1(Request $request){

        
        DB::table('footerlevel1s')
        ->where('id','1')
        ->update(['h1' => $request->h1,
                    'h2' => $request->h2,
                    'h3' => $request->h3,
                    'h4' => $request->h4,
                    'h1desc' => $request->h1desc,
                    'h2desc' => $request->h2desc,
                    'h3desc' => $request->h3desc,
                    'h4desc' => $request->h4desc,
                  
             ]);


        $image1=$request->image1;
        $image2=$request->image2;
        $image3=$request->image3;
        $image4=$request->image4;
         $randomstr=Str::random(4);
        if(!empty($image1))
        {
           
            $imageName1 = "footerl1"."-".$randomstr.'-1'.'.'.$request->image1->extension();  
            $image_resize = Image::make($image1->getRealPath());              
            $image_resize->resize(50,45);
            $image_resize->save(public_path('upload/extra/' .$imageName1));

            DB::table('footerlevel1s')
            ->where('id','1')
            ->update(['image1' => $imageName1]);
            
        }    

        if(!empty($image2))
        {
            
            $imageName2 = "footerl1"."-".$randomstr.'-2'.'.'.$request->image2->extension();  
           
            $image_resize = Image::make($image2->getRealPath());              
            $image_resize->resize(50,45);
            $image_resize->save(public_path('upload/extra/' .$imageName2));

             DB::table('footerlevel1s')
            ->where('id','1')
            ->update(['image2' => $imageName2]);
            
        } 

        if(!empty($image3))
        {
            $imageName3 = "footerl1"."-".$randomstr.'-3'.'.'.$request->image3->extension();  
         
            $image_resize = Image::make($image3->getRealPath());              
            $image_resize->resize(50,45);
            $image_resize->save(public_path('upload/extra/' .$imageName3));

             DB::table('footerlevel1s')
             ->where('id','1')
            ->update(['image3' => $imageName3]);
           // $item->save();
        }

        if(!empty($image4))
        {
            $imageName4 = "footerl1"."-".$randomstr.'-3'.'.'.$request->image4->extension();  
         
            $image_resize = Image::make($image4->getRealPath());              
            $image_resize->resize(50,45);
            $image_resize->save(public_path('upload/extra/' .$imageName4));

             DB::table('footerlevel1s')
             ->where('id','1')
            ->update(['image4' => $imageName4]);
           // $item->save();
        }

        $request->session()->flash('success','Footer Settings Updated Successfully !');    
        return Redirect::back();   
    }

    public function fsection1(Request $request){

        $items=Footer_setting::where('id','1')->first();
        return view('settings.footer-section1',compact('items'));
    }


    public function updatesection1(Request $request){

        DB::table('footer_settings')
        ->where('id','1')
        ->update(['footerabout' => $request->footerabout,
                    'footerphone' => $request->footerphone,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'youtube' => $request->youtube,
                    'twitter' => $request->twitter,
                    'linkedin' => $request->linkedin,
             ]);  


        $image1=$request->image1;
        if(!empty($image1))
        {
           
            $imageName1 = 'footerlogo'.rand(100,1000).'.'.$request->image1->extension();  
            $request->image1->move(public_path('upload/logo/'), $imageName1);
            //$item->image1=$imageName1;

             DB::table('footer_settings')
            ->where('id','1')
            ->update(['footerlogo' => $imageName1]);
            
        }     

        $request->session()->flash('success','Footer Settings Updated Successfully !');    
        return Redirect::back();   
    }


    public function fsection2(Request $request){

        $items=Footer_setting::where('id','1')->first();
        return view('settings.footer-section2',compact('items'));
    }


    public function updatesection2(Request $request){

        DB::table('footer_settings')
        ->where('id','1')
        ->update(['customlinkheading' => $request->customlinkheading,
                    'title1' => $request->title1,
                    'title2' => $request->title2,
                    'title3' => $request->title3,
                    'title4' => $request->title4,
                    'title5' => $request->title5,
                    'title6' => $request->title6,
                    'title1link' => $request->title1link,
                    'title2link' => $request->title2link,
                    'title3link' => $request->title3link,
                    'title4link' => $request->title4link,
                    'title5link' => $request->title5link,
                    'title6link' => $request->title6link,
                    

             ]); 

       $request->session()->flash('success','Footer Settings Updated Successfully !');    
        return Redirect::back();    
    }          

    public function fsection3(Request $request){

        $items=Footer_setting::where('id','1')->first();
        return view('settings.footer-section3',compact('items'));
    }


    public function updatesection3(Request $request){

        DB::table('footer_settings')
        ->where('id','1')
        ->update(['newsletterheading' => $request->newsletterheading,
                    'newsletterabout' => $request->newsletterabout,
                    
                    

             ]); 

       $request->session()->flash('success','Footer Settings Updated Successfully !');    
        return Redirect::back();    
    } 


    public function homepage(Request $request){

        $items=Homepage_setting::where('id','1')->first();
        return view('settings.homepage',compact('items'));
    }

    public function homepageupdate(Request $request){
        


        $salestatus=$request->salestatus;
        if($salestatus=="on")
        {
            $salestatus="on";
        }
        else 
        {
            $salestatus="off";
        }    

        DB::table('homepage_settings')
        ->where('id','1')
        ->update(['salestatus' => $salestatus,
                    'newarrivalcolor' => $request->newarrivalcolor,
                    'newarrivalproductlimit' => $request->newarrivalproductlimit,
                    'salecolor' => $request->salecolor,
                    'saleproductlimit' => $request->saleproductlimit,
                    'currencysymbol' => $request->currencysymbol,
                    'shippingtype' => $request->shippingtype,
                    'themecolor' => $request->themecolor,
                    'themesecondarycolor' => $request->themesecondarycolor,
                    'saleimageopacity' => $request->saleimageopacity,
                    'newarrivalimageopacity' => $request->newarrivalimageopacity,
                    'whatsappno' => $request->whatsappno,
                    'vat' => $request->vat,
                    'weighttype' => $request->weighttype,
             ]);      

        $image1=$request->salebackground;
        $image2=$request->newarrivalbackground;
       
         $randomstr=Str::random(4);
        if(!empty($image1))
        {
           
            $imageName1 = "salebanner"."-".$randomstr.'-1'.'.'.$request->salebackground->extension();  
            $image_resize = Image::make($image1->getRealPath());              
            $image_resize->resize(1920,500);
            $image_resize->save(public_path('upload/extra/' .$imageName1));

            DB::table('homepage_settings')
            ->where('id','1')
            ->update(['salebackground' => $imageName1]);
            
        }    

        if(!empty($image2))
        {
            
            $imageName2 = "newarrivalbanner"."-".$randomstr.'-2'.'.'.$request->newarrivalbackground->extension();  
           
            $image_resize = Image::make($image2->getRealPath());              
            $image_resize->resize(1920,500);
            $image_resize->save(public_path('upload/extra/' .$imageName2));

             DB::table('homepage_settings')
            ->where('id','1')
            ->update(['newarrivalbackground' => $imageName2]);
            
        } 

       
        $request->session()->flash('success','Homepage Settings Updated Successfully !');    
        return Redirect::back();    
    } 


    public function superadmin(Request $request){

        $role=session()->get('role');
        if($role=="superadmin")
        {
            $items=Product_setting::where('id','1')->first();
            return view('settings.superadmin',compact('items'));
        }    

        return Redirect::to('/dashboard');  
    }


    public function updatesuperadmin(Request $request){

        DB::table('product_settings')
        ->where('id','1')
        ->update(['productlimit' => $request->productlimit,
                    'startsfrom' => $request->startsfrom,
                    'categorylimit' => $request->categorylimit,
                    'categorylimithome' => $request->categorylimithome,
                    'themecolor' => $request->themecolor,
                    'enquiry' => $request->enquiry,
                    'reward'=>$request->rewardstatus,
             ]); 

       $request->session()->flash('success','Super Admin Settings Updated Successfully !');    
        return Redirect::back();    
    } 



    public function addprivacy(Request $request){

        $items=Corporatepage::where('id','1')->first();
        return view('settings.addprivacy',compact('items'));
    }


    public function updateprivacy(Request $request){

       DB::table('corporatepages')
        ->where('id','1')
        ->update(['privacy' => $request->privacy,
                 
             ]); 

       $request->session()->flash('success','Updated Successfully !');    
        return Redirect::back();  
    }

    public function addreturn(Request $request){

       $items=Corporatepage::where('id','1')->first();
        return view('settings.addreturn',compact('items'));
    }


    public function updatereturn(Request $request){

       DB::table('corporatepages')
        ->where('id','1')
        ->update(['returnpolicy' => $request->returnpolicy,
                 
             ]); 

       $request->session()->flash('success','Updated Successfully !');    
        return Redirect::back();  
    }



     public function addshippment(Request $request){

       $items=Corporatepage::where('id','1')->first();
        return view('settings.addshippment',compact('items'));
    }


    public function updateshippment(Request $request){

        //echo $request->shippment;die;

       DB::table('corporatepages')
        ->where('id','1')
        ->update(['shippment' => $request->shippment,
                 
             ]); 

       $request->session()->flash('success','Updated Successfully !');    
        return Redirect::back();  
    }


    public function env_key_update(Request $request)
    {

        //echo"<pre>";print_r($_POST);die;
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $request->session()->flash('success','Updated Successfully !');    
        return Redirect::back(); 
    }


    

    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $type . '=' . env($type), $type . '=' . $val, file_get_contents($path)
            ));
        }
    }

    /*public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"'.trim($val).'"';
            if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                file_put_contents($path, str_replace(
                    $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                ));
            }
            else{
                file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            }
        }
    }*/

    public function redirectToGoogle()
    {
      return Socialite::driver('google')->redirect();
    }

    public function GoogleCallback()
    {
      $user=Socialite::driver('google')->user();
      // echo"<pre>";print_r($user);die;
      $checkuser=Storeuser::where('google_id',$user->id)->first();
      if($checkuser)
      {
        DB::table('storeusers')->where('google_id',$user->id)->update(['google_token' => $user->token]);
        return redirect('extract1');  
      }
      else
      {
        $password=Str::random(15);
        $storeuser=new Storeuser;
        $storeuser->fname=$user->name;
        $storeuser->email=$user->email;
        $storeuser->google_id=$user->id;
        $storeuser->google_token=$user->token;
        $storeuser->password=$password;
        $storeuser->save();


        // sent mail
        $detail = array(
          'password' =>$password,
          
        );
        $user['to']=$user->email;
        $user['from']="richa@silverpixelz.com";
        Mail::send('mail/password',  $detail, function($message) use ($user) {
                $message->to($user['to'], 'SeveWonders')->subject
                ('New Password');
                $message->from($user['from'],'SevenWonders');
            });

        
        return redirect('extract1');
      }  
    }

    public function redirectToFacebook()
    {
      return Socialite::driver('facebook')->redirect();
    }

    public function FacebookCallback()
    {
      $user=Socialite::driver('facebook')->user();
      // echo "<pre>";print_r($user);die;
      $checkuser=Storeuser::where('facebook_id',$user->id)->first();
      if($checkuser)
      {
        DB::table('storeusers')->where('facebook_id',$user->id)->update(['facebook_token' => $user->token]);
        return redirect('extract1');
      }
      else
      {
       
        $storeuser=new Storeuser;
        $storeuser->fname=$user->name;
        $storeuser->email=$user->email;
        $storeuser->facebook_id=$user->id;
        $storeuser->facebook_token=$user->token;
        $storeuser->save();
        return redirect('extract1');
      }
    }

    public function extract(Request $request)
    {
      // $from="01";
      // $to="12";
      // $test=LaravelGoogleAnalytics::dateRanges(Period::months(3))
      // ->metrics('totalUsers')
      //  ->dimensions('date')
      //   ->whereMetricBetween('totalUsers', $from, $to)
      //       ->orderByDimension('date')
      //       ->keepEmptyRows(true)
      //       ->get()
      //       ->table;
      // echo "<pre>";print_r($test);die;
    
      
        // $product=DB::table('products')->select('*')->get();
        // foreach($product as $prod)
        // {
        //   $parentcategory[]=$prod->parentcategory;
        //   $category[]=$prod->category;
        //   $subcategory[]=$prod->subcategory;
        // }
        // echo "<pre>"; print_r($parentcategory);die;
      
            // $myproducts=DB::table('myproducts')->select('*')->get();
            // foreach($myproducts as $myprod)
            // {
            //     $sku=$myprod->sku;
            //     $shortdescription=$myprod->short_description;
            //     DB::table('products')->where('sku',$sku)->update(['shortdescription' => $shortdescription]);
            // }
            // $products=DB::table('products')->select('*')->get();
            
          
            // remove space
            // foreach($products as $prod)
            // {
            //     $id=$prod->id;
            //     $image3=$prod->image3;
            //     $result= ltrim($image3);
            //     DB::table('products')->where('id',$id)->update(['image3' => $result]);
            // }
            // echo "<pre>";print_r($image2);die;

            // for category

            // foreach($myproducts as $prod)
            // {
            //     $category=$prod->categories;
            //     $sku=$prod->sku;

            //     if (strpos($category, ',') !== false) 
            //     {
                   
            //        if(strpos($category,'>')!==false)
            //        {    
            //             $cat=explode(',',$category);

            //             // array index 0
            //             if(!empty($cat[0]))
            //             {
            //                 $cat0=$cat[0];
            //                 if(strpos($cat0,'>')!==false)
            //                 {
            //                    $array_0=explode('>',$cat0);
            //                    // echo "<pre>";print_r($array_0);
            //                    if(!empty($array_0[0]))
            //                     {
            //                         DB::table('products')->where('sku',$sku)->update(['parentcategory' => $array_0[0]]);
            //                     }
            //                    if(!empty($array_0[1]))
            //                    {
            //                         DB::table('products')->where('sku',$sku)->update(['category' => $array_0[1]]);
            //                    }
            //                    if(!empty($array_0[2]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['subcategory' => $array_0[2]]);
            //                    }
            //                    if(!empty($array_0[3]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['childsubcategory' => $array_0[3]]);
            //                    }

            //                 }
            //                 else
            //                 {
            //                     // echo "<pre>";print_r($cat0);
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $cat0]);
            //                 }

            //             }

            //             // array index 1
            //             if(!empty($cat[1]))
            //             {
            //                 $cat1=$cat[1];
            //                 if(strpos($cat1,'>')!==false)
            //                 {
            //                    $array_1=explode('>',$cat1);
            //                    // echo "<pre>";print_r($array_0);
            //                    if(!empty($array_1[0]))
            //                     {
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $array_1[0]]);
            //                     }
            //                    if(!empty($array_1[1]))
            //                    {
            //                         DB::table('products')->where('sku',$sku)->update(['category' => $array_1[1]]);
            //                    }
            //                    if(!empty($array_1[2]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['subcategory' => $array_1[2]]);
            //                    }
            //                    if(!empty($array_1[3]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['childsubcategory' => $array_1[3]]);
            //                    }

            //                 }
            //                 else
            //                 {
            //                     // echo "<pre>";print_r($cat0);
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $cat1]);
            //                 }
   
            //             }


            //             // array index 2
            //             if(!empty($cat[2]))
            //             {
            //                 $cat2=$cat[2];
            //                 if(strpos($cat2,'>')!==false)
            //                 {
            //                    $array_2=explode('>',$cat2);
            //                    // echo "<pre>";print_r($array_0);
            //                    if(!empty($array_2[0]))
            //                     {
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $array_2[0]]);
            //                     }
            //                    if(!empty($array_2[1]))
            //                    {
            //                         DB::table('products')->where('sku',$sku)->update(['category' => $array_2[1]]);
            //                    }
            //                    if(!empty($array_2[2]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['subcategory' => $array_2[2]]);
            //                    }
            //                    if(!empty($array_2[3]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['childsubcategory' => $array_2[3]]);
            //                    }

            //                 }
            //                 else
            //                 {
            //                     // echo "<pre>";print_r($cat0);
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $cat2]);
            //                 }
   
            //             }

            //             // array index 3
            //             if(!empty($cat[3]))
            //             {
            //                 $cat3=$cat[3];
            //                 if(strpos($cat3,'>')!==false)
            //                 {
            //                    $array_3=explode('>',$cat3);
            //                    // echo "<pre>";print_r($array_0);
            //                    if(!empty($array_3[0]))
            //                     {
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $array_3[0]]);
            //                     }
            //                    if(!empty($array_3[1]))
            //                    {
            //                         DB::table('products')->where('sku',$sku)->update(['category' => $array_3[1]]);
            //                    }
            //                    if(!empty($array_3[2]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['subcategory' => $array_3[2]]);
            //                    }
            //                    if(!empty($array_3[3]))
            //                    {
            //                          DB::table('products')->where('sku',$sku)->update(['childsubcategory' => $array_3[3]]);
            //                    }

            //                 }
            //                 else
            //                 {
            //                     // echo "<pre>";print_r($cat0);
            //                     DB::table('products')->where('sku',$sku)->update(['parentcategory' => $cat3]);
            //                 }
   
            //             }
                        
                       
            //        }
            //        else
            //        {
            //               DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['parentcategory' => $category,
                                     
            //                      ]); 
            //        }
                  
                    
            //     }
                // else
                // {
                //      echo "<pre>";print_r($category);
                // }
                
            //     else
            //     {
            //         if (strpos($category, '>') !== false) 
            //         {
            //            $parentcat=explode('>',$category);

            //            if(!empty($parentcat[0]))
            //            {
                          
            //                 DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['parentcategory' => $parentcat[0],
                                     
            //                      ]); 
            //            }
                       
            //            if(!empty($parentcat[1]))
            //            {
            //                   DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['category' => $parentcat[1],
                                     
            //                      ]);
            //            }

            //            if(!empty($parentcat[2]))
            //            {
            //                  DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['subcategory' => $parentcat[2],
                                     
            //                      ]);
            //            }
                      
            //            if(!empty($parentcat[3]))
            //            {
            //                   DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['childsubcategory' => $parentcat[3],
                                     
            //                      ]);
            //            }
                      
                    
            //         }
            //         else
            //         {
            //             $parentcat=$prod->categories;
            //             $prod->parentcategory=$parentcat;
            //             DB::table('products')
            //                 ->where('sku',$sku)
            //                 ->update(['parentcategory' => $parentcat,
                                     
            //                      ]); 
            //         }
                    
                    
            //     }
            // }

             
             // echo "<pre>"; print_r($parentcat);
            // echo "<pre>"; print_r($pcat);

            // all records
            // Product::truncate();
            // foreach($myproducts as $prod)
            // {

            //     $name=$prod->name;
            //     $shortdescription=$prod->short_description;
            //     $description=$prod->description;
            //     $price=$prod->regular_price;
            //     $sprice=$prod->sale_price;
            //     $brand=$prod->brands;
            //     $sku=$prod->sku;
            //     $active=$prod->published;
            //     $createslug=$this->slug($prod->name);
            //     $rand_num=rand(100,1000);
            //     $slug=$createslug."-".$rand_num;
            //     $warranty=$prod->meta_product_warranty;
            //     $date_sale_price_start=$prod->date_sale_price_start;
            //     $date_sale_price_ends=$prod->date_sale_price_ends;
                
               
            //     $product=new Product;
                
            //     if (strpos($prod->images, ',') !== false) {
                    
            //         $image=explode(',',$prod->images);
            //         $product->thumbnail=$image[0];
            //         $product->image1=$image[0];
            //         if(!empty($image[1]))
            //         {
            //             $product->image2=$image[1];
            //         }
            //         if(!empty($image[2]))
            //         {
            //              $product->image3=$image[2];
            //         }

            //         if(!empty($image[3]))
            //         {
            //              $product->image3=$image[3];
            //         }

            //         if(!empty($image[4]))
            //         {
            //              $product->image4=$image[4];
            //         }

            //         if(!empty($image[5]))
            //         {
            //              $product->image5=$image[5];
            //         }
                   
            //         if(!empty($image[6]))
            //         {
            //              $product->image6=$image[6];
            //         }

            //     }
                
            //     else
            //     {
            //         $product->thumbnail=$prod->images;
            //         $product->image1=$prod->images;
            //     }



             
            //     if($active==1)
            //     {
            //         $product->is_active='online';
            //     }
            //     else
            //     {
            //         $product->is_active='offline';
            //     }

               
            //     $product->name=$name;
            //     $product->description=$description;
            //     $product->price=$price;
            //     $product->sprice=$sprice;
            //     $product->brand=$brand;
            //     $product->sku=$sku;
            //     $product->slug=$slug;
            //     $product->product_warranty=$warranty;
            //     $product->date_sale_price_start= $date_sale_price_start;
            //     $product->date_sale_price_ends= $date_sale_price_ends;
            //     $product->save();
               

            // }
            

        // echo "completed";
    }

    
    public function slug($string)
    {
        $string = str_replace(' ', '-', $string);
        $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
        return preg_replace('/-+/', '-', $string);    
    }

    public function extract1()
    {

        echo "Updated Successfully"; die;
        // $products=DB::table('products')->select('*')->get();
        // foreach($products as $item)
        // {
        //   $name=$item->name;
        //   $createslug=$this->slug($item->name);
        //   $rand_num=rand(100,1000);
        //   $slug=$createslug."-".$rand_num;
        //   DB::table('products')->where('id',$item->id)->update(['slug' => $slug]); 
        // }

         // DB::table('products')->where('childsubcategory',' Chest Freezer')->update(['childsubcategory' => '12']);

        // foreach($products as $prod)
        // {

        //     $childsubcat[]=$prod->childsubcategory;


            /* for subcategory */
            // $subcat[]=$prod->subcategory;
           //  $subcategory=DB::table('subcategories')->where('catname',$subcat)->get();
           // foreach($subcategory as $subcategory)
           // {
           //      $id=$subcategory->id;
           //       DB::table('products')->whereIn('subcategory',$subcat)->update(['subcategory' => $id]);  
           // }

            /* for category */ 
           // $cat[]=$prod->category;
           //  $category=DB::table('categories')->where('catname',$cat)->get();
           // foreach($category as $category)
           // {
           //      $id=$category->id;
           //       DB::table('products')->whereIn('category',$cat)->update(['category' => $id]);  
           // }

           /*  for parentcategory    */
           // $pcategory[]=$prod->parentcategory;
           // $parentcategory=DB::table('parentcategories')->where('catname',$pcategory)->get();
           // foreach($parentcategory as $pcat)
           // {
           //      $id=$pcat->id;
           //       DB::table('products')->whereIn('parentcategory',$pcategory)->update(['parentcategory' => $id]);  
           // }

           /* for brand */
           // $brandname=$prod->brand;
           // $brand=DB::table('brands')->where('brandname',$brandname)->get();
           // foreach($brand as $brand)
           // {
           //    $id=$brand->id;
           //    DB::table('products')->where('brand',$brandname)->update(['brand' => $id]);  
           // }
               
        // }
        // echo "completed";
        // echo "<pre>";print_r($childsubcat);
    }
}

