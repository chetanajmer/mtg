<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Cartorder;
use App\Models\About;
use App\Models\Contact;
use Illuminate\Support\Facades\Redirect;

class CommonController extends Controller
{
    

    public function storeaboutus(Request $request)
    {
        $items=About::where('id','1')->first();
        return view('backend.pages.about',compact('items'));
    }


    public function updateaboutus(Request $request)
    {
       
            DB::table('abouts')
                        ->where('id','1')
                        ->update([
                            'h1title' => $request->h1title,
                            'h2title' => $request->h2title,
                            'h3title' => $request->h3title,
                            'h4title' => $request->h4title,

                            'h1desc' => $request->h1desc,
                            'h2desc' => $request->h2desc,
                            'h3desc' => $request->h3desc,
                            'h4desc' => $request->h4desc,

                            
                            
                    ]);

            $image1=$request->image1;
            
            if(isset($image1))
            {   
            
            $imageName1 = "about".rand(100,1000).'-1'.'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/pages/'), $imageName1);
            //$item->image1=$imageName1;

             DB::table('abouts')
            ->where('id','1')
            ->update(['image1' => $imageName1]);

            }


     
       $request->session()->flash('success','Updated Successfully!'); 
        return Redirect::to('storeaboutus');
    }



    public function storecontactus(Request $request)
    {
        $items=Contact::where('id','1')->first();
        return view('backend.pages.contact',compact('items'));
    }


    public function updatecontactus(Request $request)
    {
       
            DB::table('contacts')
                        ->where('id','1')
                        ->update([
                            'description' => $request->description,
                            'address' => $request->address,
                            'phone1' => $request->phone1,
                            'phone2' => $request->phone2,
                            'email1' => $request->email1,
                            'email2' => $request->email2,
                            'map' => $request->map,
  
                            
                    ]);



     
       $request->session()->flash('success','Updated Successfully!'); 
        return Redirect::to('storecontactus');
    }

}
