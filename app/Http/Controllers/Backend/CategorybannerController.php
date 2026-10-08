<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorybanner;
use Illuminate\Support\Facades\Redirect;
use DB;

class CategorybannerController extends Controller
{

    public function small_banners_section1(Request $request)
    {
    	$items=Categorybanner::where('id','1')->first();
    	return view('backend.banner.section1.add-banner',compact('items'));
    }

    public function small_banners_section2(Request $request)
    {
        $items=Categorybanner::where('id','2')->first();
        return view('backend.banner.section2.add-banner',compact('items'));
    }

    public function small_banners_section3(Request $request)
    {
        $items=Categorybanner::where('id','3')->first();
        return view('backend.banner.section3.add-banner',compact('items'));
    }


    public function updatesmall_banners_section1(Request $request)
    {


    	DB::table('categorybanners')
        ->where('id','1')
        ->update(['image1link' => $request->image1link,
                    'image2link' => $request->image2link,
                    'image3link' => $request->image3link,
                    'image4link' => $request->image4link,
                    'image5link' => $request->image5link,
                    'bannername1' => $request->bannername1,
                    'bannername2' => $request->bannername2,
                    'bannername3' => $request->bannername3,
                    'bannername4' => $request->bannername4,
                    'bannername5' => $request->bannername5,
        ]);

		$image1=$request->image1;
        $mob_image1=$request->mob_image1;
        $image2=$request->image2;
        $mob_image2=$request->mob_image2;
        $image3=$request->image3;
        $mob_image3=$request->mob_image3;
        $image4=$request->image4;
        $mob_image4=$request->mob_image4;
        $image5=$request->image5;
        $mob_image5=$request->mob_image5;

        if(!empty($image1))
        {
           
            $imageName1 = 'banner'.rand(100,1000).'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/banners/'), $imageName1);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['image1' => $imageName1]);
            
        }   

        if(!empty($mob_image1))
        {
           
            $mob_imageName1 = 'banner'.rand(100,1000).'.'.$request->mob_image1->extension();   
            $request->mob_image1->move(public_path('upload/banners/'), $mob_imageName1);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['mob_image1' => $mob_imageName1]);
            
        }    

        if(!empty($image2))
        {
            $imageName2 = 'banner'.rand(100,1000).'.'.$request->image2->extension();    
            $request->image2->move(public_path('upload/banners/'), $imageName2);
            //$item->image2=$imageName2;

           	DB::table('categorybanners')
            ->where('id','1')
            ->update(['image2' => $imageName2]);
            
        } 

        if(!empty($mob_image2))
        {
           
            $mob_imageName2 = 'banner'.rand(100,1000).'.'.$request->mob_image2->extension();   
            $request->mob_image2->move(public_path('upload/banners/'), $mob_imageName2);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['mob_image2' => $mob_imageName2]);
            
        }   

        if(!empty($image3))
        {
            $imageName3 = 'banner'.rand(100,1000).'.'.$request->image3->extension();  
            $request->image3->move(public_path('upload/banners/'), $imageName3);
         	
         	DB::table('categorybanners')
            ->where('id','1')
            ->update(['image3' => $imageName3]);
        }  

        if(!empty($mob_image3))
        {
           
            $mob_imageName3 = 'banner'.rand(100,1000).'.'.$request->mob_image3->extension();   
            $request->mob_image3->move(public_path('upload/banners/'), $mob_imageName3);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['mob_image3' => $mob_imageName3]);
            
        }  

        if(!empty($image4))
        {
            $imageName4 = 'banner'.rand(100,1000).'.'.$request->image4->extension();  
            $request->image4->move(public_path('upload/banners/'), $imageName4);
            
            DB::table('categorybanners')
            ->where('id','1')
            ->update(['image4' => $imageName4]);
        }  

        if(!empty($mob_image4))
        {
           
            $mob_imageName4 = 'banner'.rand(100,1000).'.'.$request->mob_image4->extension();   
            $request->mob_image4->move(public_path('upload/banners/'), $mob_imageName4);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['mob_image4' => $mob_imageName4]);
            
        }      

        if(!empty($image5))
        {
            $imageName5 = 'banner'.rand(100,1000).'.'.$request->image5->extension();  
            $request->image5->move(public_path('upload/banners/'), $imageName5);
            
            DB::table('categorybanners')
            ->where('id','1')
            ->update(['image5' => $imageName5]);
        }     

        if(!empty($mob_image5))
        {
           
            $mob_imageName5 = 'banner'.rand(100,1000).'.'.$request->mob_image5->extension();   
            $request->mob_image5->move(public_path('upload/banners/'), $mob_imageName5);
             DB::table('categorybanners')
            ->where('id','1')
            ->update(['mob_image5' => $mob_imageName5]);
            
        }  

            

        $request->session()->flash('success','Banners Added Successfully !');
			return Redirect::back();
    }

    public function updatesmall_banners_section2(Request $request)
    {


        DB::table('categorybanners')
        ->where('id','2')
        ->update(['image1link' => $request->image1link,
                    'image2link' => $request->image2link,
                    'image3link' => $request->image3link,
                    'image4link' => $request->image4link,
                    'bannername1' => $request->bannername1,
                    'bannername2' => $request->bannername2,
                    'bannername3' => $request->bannername3,
                    'bannername4' => $request->bannername4,
        ]);

        $image1=$request->image1;
        $mob_image1=$request->mob_image1;
        $image2=$request->image2;
        $mob_image2=$request->mob_image2;
        $image3=$request->image3;
        $mob_image3=$request->mob_image3;
        $image4=$request->image4;
        $mob_image4=$request->mob_image4;
    

        if(!empty($image1))
        {
           
            $imageName1 = 'banner'.rand(100,1000).'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/banners/'), $imageName1);
             DB::table('categorybanners')
            ->where('id','2')
            ->update(['image1' => $imageName1]);
            
        }   

        if(!empty($mob_image1))
        {
           
            $mob_imageName1 = 'banner'.rand(100,1000).'.'.$request->mob_image1->extension();   
            $request->mob_image1->move(public_path('upload/banners/'), $mob_imageName1);
             DB::table('categorybanners')
            ->where('id','2')
            ->update(['mob_image1' => $mob_imageName1]);
            
        }    

        if(!empty($image2))
        {
            $imageName2 = 'banner'.rand(100,1000).'.'.$request->image2->extension();    
            $request->image2->move(public_path('upload/banners/'), $imageName2);
            //$item->image2=$imageName2;

            DB::table('categorybanners')
            ->where('id','2')
            ->update(['image2' => $imageName2]);
            
        } 

        if(!empty($mob_image2))
        {
           
            $mob_imageName2 = 'banner'.rand(100,1000).'.'.$request->mob_image2->extension();   
            $request->mob_image2->move(public_path('upload/banners/'), $mob_imageName2);
             DB::table('categorybanners')
            ->where('id','2')
            ->update(['mob_image2' => $mob_imageName2]);
            
        }   

        if(!empty($image3))
        {
            $imageName3 = 'banner'.rand(100,1000).'.'.$request->image3->extension();  
            $request->image3->move(public_path('upload/banners/'), $imageName3);
            
            DB::table('categorybanners')
            ->where('id','2')
            ->update(['image3' => $imageName3]);
        }  

        if(!empty($mob_image3))
        {
           
            $mob_imageName3 = 'banner'.rand(100,1000).'.'.$request->mob_image3->extension();   
            $request->mob_image3->move(public_path('upload/banners/'), $mob_imageName3);
             DB::table('categorybanners')
            ->where('id','2')
            ->update(['mob_image3' => $mob_imageName3]);
            
        }  

        if(!empty($image4))
        {
            $imageName4 = 'banner'.rand(100,1000).'.'.$request->image4->extension();  
            $request->image4->move(public_path('upload/banners/'), $imageName4);
            
            DB::table('categorybanners')
            ->where('id','2')
            ->update(['image4' => $imageName4]);
        }  

        if(!empty($mob_image4))
        {
           
            $mob_imageName4 = 'banner'.rand(100,1000).'.'.$request->mob_image4->extension();   
            $request->mob_image4->move(public_path('upload/banners/'), $mob_imageName4);
             DB::table('categorybanners')
            ->where('id','2')
            ->update(['mob_image4' => $mob_imageName4]);
            
        }      

        
            

        $request->session()->flash('success','Banners Added Successfully !');
            return Redirect::back();
    }

    public function updatesmall_banners_section3(Request $request)
    {

        DB::table('categorybanners')
        ->where('id','3')
        ->update(['image1link' => $request->image1link,
                    'image2link' => $request->image2link,
                    'image3link' => $request->image3link,
                    'image4link' => $request->image4link,
                    'image5link' => $request->image5link,
                    'image6link' => $request->image6link,
                    'image7link' => $request->image7link,
                    'image8link' => $request->image8link,
                    'bannername1' => $request->bannername1,
                    'bannername2' => $request->bannername2,
                    'bannername3' => $request->bannername3,
                    'bannername4' => $request->bannername4,
                    'bannername5' => $request->bannername5,
                    'bannername6' => $request->bannername6,
                    'bannername7' => $request->bannername7,
                    'bannername8' => $request->bannername8,
        ]);

        $image1=$request->image1;
        $mob_image1=$request->mob_image1;

        $image2=$request->image2;
        $mob_image2=$request->mob_image2;

        $image3=$request->image3;
        $mob_image3=$request->mob_image3;

        $image4=$request->image4;
        $mob_image4=$request->mob_image4;

        $image5=$request->image5;
        $mob_image5=$request->mob_image5;

        $image6=$request->image6;
        $mob_image6=$request->mob_image6;

        $image7=$request->image7;
        $mob_image7=$request->mob_image7;

        $image8=$request->image8;
        $mob_image8=$request->mob_image8;
    

        if(!empty($image1))
        {
           
            $imageName1 = 'banner'.rand(100,1000).'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/banners/'), $imageName1);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['image1' => $imageName1]);
            
        }   

        if(!empty($mob_image1))
        {
           
            $mob_imageName1 = 'banner'.rand(100,1000).'.'.$request->mob_image1->extension();   
            $request->mob_image1->move(public_path('upload/banners/'), $mob_imageName1);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image1' => $mob_imageName1]);
            
        }    

        if(!empty($image2))
        {
            $imageName2 = 'banner'.rand(100,1000).'.'.$request->image2->extension();    
            $request->image2->move(public_path('upload/banners/'), $imageName2);
            //$item->image2=$imageName2;

            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image2' => $imageName2]);
            
        } 

        if(!empty($mob_image2))
        {
           
            $mob_imageName2 = 'banner'.rand(100,1000).'.'.$request->mob_image2->extension();   
            $request->mob_image2->move(public_path('upload/banners/'), $mob_imageName2);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image2' => $mob_imageName2]);
            
        }   

        if(!empty($image3))
        {
            $imageName3 = 'banner'.rand(100,1000).'.'.$request->image3->extension();  
            $request->image3->move(public_path('upload/banners/'), $imageName3);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image3' => $imageName3]);
        }  

        if(!empty($mob_image3))
        {
           
            $mob_imageName3 = 'banner'.rand(100,1000).'.'.$request->mob_image3->extension();   
            $request->mob_image3->move(public_path('upload/banners/'), $mob_imageName3);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image3' => $mob_imageName3]);
            
        }  

        if(!empty($image4))
        {
            $imageName4 = 'banner'.rand(100,1000).'.'.$request->image4->extension();  
            $request->image4->move(public_path('upload/banners/'), $imageName4);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image4' => $imageName4]);
        }  

        if(!empty($mob_image4))
        {
           
            $mob_imageName4 = 'banner'.rand(100,1000).'.'.$request->mob_image4->extension();   
            $request->mob_image4->move(public_path('upload/banners/'), $mob_imageName4);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image4' => $mob_imageName4]);
            
        }   

        if(!empty($image5))
        {
            $imageName5 = 'banner'.rand(100,1000).'.'.$request->image5->extension();  
            $request->image5->move(public_path('upload/banners/'), $imageName5);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image5' => $imageName5]);
        } 

        if(!empty($mob_image5))
        {
           
            $mob_imageName5 = 'banner'.rand(100,1000).'.'.$request->mob_image5->extension();   
            $request->mob_image5->move(public_path('upload/banners/'), $mob_imageName5);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image5' => $mob_imageName5]);
            
        }      

        if(!empty($image6))
        {
            $imageName6 = 'banner'.rand(100,1000).'.'.$request->image6->extension();  
            $request->image6->move(public_path('upload/banners/'), $imageName6);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image6' => $imageName6]);
        }

        if(!empty($mob_image6))
        {
           
            $mob_imageName6 = 'banner'.rand(100,1000).'.'.$request->mob_image6->extension();   
            $request->mob_image6->move(public_path('upload/banners/'), $mob_imageName6);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image6' => $mob_imageName6]);
            
        }  

        if(!empty($image7))
        {
            $imageName7 = 'banner'.rand(100,1000).'.'.$request->image7->extension();  
            $request->image7->move(public_path('upload/banners/'), $imageName7);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image7' => $imageName7]);
        }  

        if(!empty($mob_image7))
        {
           
            $mob_imageName7 = 'banner'.rand(100,1000).'.'.$request->mob_image7->extension();   
            $request->mob_image7->move(public_path('upload/banners/'), $mob_imageName7);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image7' => $mob_imageName7]);
            
        }  

        if(!empty($image8))
        {
            $imageName8 = 'banner'.rand(100,1000).'.'.$request->image8->extension();  
            $request->image8->move(public_path('upload/banners/'), $imageName8);
            
            DB::table('categorybanners')
            ->where('id','3')
            ->update(['image8' => $imageName8]);
        } 

        if(!empty($mob_image8))
        {
           
            $mob_imageName8 = 'banner'.rand(100,1000).'.'.$request->mob_image8->extension();   
            $request->mob_image8->move(public_path('upload/banners/'), $mob_imageName8);
             DB::table('categorybanners')
            ->where('id','3')
            ->update(['mob_image8' => $mob_imageName8]);
            
        }  
            

        $request->session()->flash('success','Banners Added Successfully !');
            return Redirect::back();
    }

    public function banners(Request $request)
    {
        $items=Categorybanner::where('id','4')->first();
        return view('backend.banner.add-banner',compact('items'));
    }

    public function updatebanner(Request $request)
    {


        DB::table('categorybanners')
        ->where('id','4')
        ->update(['image1link' => $request->image1link,
                    'image2link' => $request->image2link,
                    'image3link' => $request->image3link,
                    'image4link' => $request->image4link,
                    'bannername1' => $request->bannername1,
                    'bannername2' => $request->bannername2,
                    'bannername3' => $request->bannername3,
                    'bannername4' => $request->bannername4,
        ]);

        $image1=$request->image1;
        $mob_image1=$request->mob_image1;
        $image2=$request->image2;
        $mob_image2=$request->mob_image2;
        $image3=$request->image3;
        $mob_image3=$request->mob_image3;
        $image4=$request->image4;
        $mob_image4=$request->mob_image4;

        if(!empty($image1))
        {
           
            $imageName1 = 'banner'.rand(100,1000).'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/banners/'), $imageName1);
             DB::table('categorybanners')
            ->where('id','4')
            ->update(['image1' => $imageName1]);
            
        }   

        if(!empty($mob_image1))
        {
           
            $mob_imageName1 = 'banner'.rand(100,1000).'.'.$request->mob_image1->extension();   
            $request->mob_image1->move(public_path('upload/banners/'), $mob_imageName1);
             DB::table('categorybanners')
            ->where('id','4')
            ->update(['mob_image1' => $mob_imageName1]);
            
        }    

        if(!empty($image2))
        {
            $imageName2 = 'banner'.rand(100,1000).'.'.$request->image2->extension();    
            $request->image2->move(public_path('upload/banners/'), $imageName2);
            //$item->image2=$imageName2;

            DB::table('categorybanners')
            ->where('id','4')
            ->update(['image2' => $imageName2]);
            
        } 

        if(!empty($mob_image2))
        {
           
            $mob_imageName2 = 'banner'.rand(100,1000).'.'.$request->mob_image2->extension();   
            $request->mob_image2->move(public_path('upload/banners/'), $mob_imageName2);
             DB::table('categorybanners')
            ->where('id','4')
            ->update(['mob_image2' => $mob_imageName2]);
            
        }   

        if(!empty($image3))
        {
            $imageName3 = 'banner'.rand(100,1000).'.'.$request->image3->extension();  
            $request->image3->move(public_path('upload/banners/'), $imageName3);
            
            DB::table('categorybanners')
            ->where('id','4')
            ->update(['image3' => $imageName3]);
        }  

        if(!empty($mob_image3))
        {
           
            $mob_imageName3 = 'banner'.rand(100,1000).'.'.$request->mob_image3->extension();   
            $request->mob_image3->move(public_path('upload/banners/'), $mob_imageName3);
             DB::table('categorybanners')
            ->where('id','4')
            ->update(['mob_image3' => $mob_imageName3]);
            
        }  

        if(!empty($image4))
        {
            $imageName4 = 'banner'.rand(100,1000).'.'.$request->image4->extension();  
            $request->image4->move(public_path('upload/banners/'), $imageName4);
            
            DB::table('categorybanners')
            ->where('id','4')
            ->update(['image4' => $imageName4]);
        }  

        if(!empty($mob_image4))
        {
           
            $mob_imageName4 = 'banner'.rand(100,1000).'.'.$request->mob_image4->extension();   
            $request->mob_image4->move(public_path('upload/banners/'), $mob_imageName4);
             DB::table('categorybanners')
            ->where('id','4')
            ->update(['mob_image4' => $mob_imageName4]);
            
        }      

        
            

        $request->session()->flash('success','Banners Added Successfully !');
            return Redirect::back();
    }

    public function deleteslider($slug)
    {

      DB::table('sliders')->where('slug', $slug)->delete();
     session()->flash('success','Deleted Succesfully!');
        return Redirect::to('list-slider');

   }
}
