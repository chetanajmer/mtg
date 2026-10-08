<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Redirect;
use DB;

class SliderController extends Controller
{
    public function showslider(Request $request)
    {
    	
    	return view('backend.slider.add-slider');

    }


    public function addslider(Request $request)

   	{	
   			$slider =new Slider;    
			$slider->slidername=$request->get('slidername');	
			$slider->pagelink=$request->get('pagelink');
			$slider->ranking=$request->get('ranking');
			$image1=$request->image1;
		  	
		    if(isset($image1))
		    {   
		        $imageName1 = "slider".rand(100,1000).'-1'.'.'.$request->image1->extension();   
            	$request->image1->move(public_path('upload/slider/'), $imageName1);
            	$slider->sliderimage=$imageName1;
		    }

            if(!empty($request->mob_image1))
            {
                $mob_imageName1 = "slider".rand(100,1000).'-1'.'.'.$request->mob_image1->extension();   
                $request->mob_image1->move(public_path('upload/slider/'), $mob_imageName1);
                $slider->mob_image=$mob_imageName1;
            }
			
			$createslug=explode(" ",$request->slidername);
        	$createslug=implode("-",$createslug);
        	$rand_num=rand(100,1000);
        	$slug=$createslug."-".$rand_num;
        	$slider->slug=$slug;
			$slider->save();
			$request->session()->flash('success','Slider Added Successfully !');
			return Redirect::back();
				
	}


	public function listslider(Request $request)
    {
    	$items_array=Slider::orderby('id')->get();
    	return view('backend.slider.list-slider',compact('items_array'));

    }


    public function edit($slug)
    {
    	
    	
    	$items =DB::Table('sliders')->select('*')->where('slug',$slug)->first();
     	return view('backend.slider.edit-slider',compact('items'));

    }

    public function updateslider(Request $request)
    {
    	$slug=$request->get('slug');
    	$image1=$request->image1;
		  	// echo "pdf is ".$image1;
		    //die();
		    if(isset($image1))
		    {   
		    
		   	$imageName1 = "slider".rand(100,1000).'-1'.'.'.$request->image1->extension();   
            $request->image1->move(public_path('upload/slider/'), $imageName1);
            //$item->image1=$imageName1;

             DB::table('sliders')
            ->where('slug',$slug)
            ->update(['sliderimage' => $imageName1]);

		    }

            if(isset($request->mob_image1))
            {   
            
            $mob_imageName1 = "slider".rand(100,1000).'-1'.'.'.$request->mob_image1->extension();   
            $request->mob_image1->move(public_path('upload/slider/'), $mob_imageName1);
            //$item->image1=$imageName1;

             DB::table('sliders')
            ->where('slug',$slug)
            ->update(['mob_image' => $mob_imageName1]);

            }

		    DB::table('sliders')
                        ->where('slug',$slug)
                        ->update([
                            'slidername' => $request->get('slidername'),
                            'pagelink' =>$request->get('pagelink'),
                            'ranking' => $request->get('ranking'),
                            
                            
                    ]);

           	 $request->session()->flash('success','Updated Succesfully!');
    		return Redirect::to('list-slider');
         

    }

    public function deleteslider($slug)
    {
        $record = DB::table('sliders')->where('slug', $slug)->first();
        $image='';
        $image_path="upload/slider/".$record->sliderimage;
        unlink($image_path);
        DB::table('sliders')->where('slug', $slug)->delete();
        session()->flash('success','Deleted Succesfully!');
        return Redirect::to('list-slider');

   }

   public function getslider(Request $request)
    {
        $items_array=Slider::orderby('id')->get();
        return json_encode($items_array);

    }
}
