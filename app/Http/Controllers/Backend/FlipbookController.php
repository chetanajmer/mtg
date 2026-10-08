<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flipbook;
use Illuminate\Support\Facades\Redirect;

use Image;
use DB;

class FlipbookController extends Controller
{
  public function index(Request $request)
  {
  	$items_array=Flipbook::get();
    return view('backend.flipbook.list-flipbook',compact('items_array'));
  }

  public function add_flipbook(Request $request)
  {
    return view('backend.flipbook.add-flipbook');
  }  

  public function doaddflipbook(Request $request)
  {
  	$flipbook=new Flipbook;
  	$image=$request->image;
  	$item = array();
  	foreach($image as $img)
    {
      $imageName1 = 'flipbook'.rand().'.'.$img->extension();
      $image_resize = Image::make($img->getRealPath());              
      $image_resize->save(public_path('upload/flipbook/' .$imageName1));
      $item[]=$imageName1 ;
     }
     // print_r($item);die;
     $img=implode(',',$item);
     $flipbook->image=$img;
     $flipbook->save();
    return Redirect::to('flipbook');
  }   

  public function edit_flipbook(Request $request)
  {
  	$items_array=Flipbook::where('id',$request->id)->first();
    return view('backend.flipbook.edit-flipbook',compact('items_array'));
  }

  public function doupdateflipbook(Request $request)
  {
  	$checkflipbook=FLipbook::where('id',$request->id)->first();
  	$original_image=explode(',',$checkflipbook->image);
  	
  	// old image update
  	if(!empty($request->new_image))
  	{
  		foreach($request->new_image as $key => $value)
  	 	{
  	 	  $imageName1 = 'flipbook'.rand().'.'.$value->extension();
	  		$image_resize = Image::make($value->getRealPath());              
				$image_resize->save(public_path('upload/flipbook/' .$imageName1));
			  $new_image[]=$imageName1;
			  $var="old_image".$key;
			  $old_image[]=$request->$var;
			  foreach($old_image as $value)
			  {
			  	$image_path="upload/flipbook/".$value;
			    if(is_file($image_path))
			    {
			      unlink($image_path);
			    }
			  }
  	 	}

  		$search=$old_image;
	  	$replace=$new_image;
	  	$array=$original_image;
	    $result=str_replace($search, $replace, $array);
	    $arr=implode(",",$result);
	     DB::table('flipbooks')
					      ->where('id',$request->id)
					      ->update(['image' => $arr]);
  		
  	}
  	 
  	 // add new image
  	if(!empty($request->image))
  	{
  		$getupdatedate=FLipbook::where('id',$request->id)->first();
  		$string=$getupdatedate->image;
  		foreach($request->image as $img)
    	{
		    $imageName1 = 'flipbook'.rand().'.'.$img->extension();
		    $image_resize = Image::make($img->getRealPath());              
		    $image_resize->save(public_path('upload/flipbook/' .$imageName1));
		    $item[]=$imageName1 ;
     	}
     	$new_image=implode(',', $item);
  		$result=$string.','.$new_image;
  		 DB::table('flipbooks')
				      ->where('id',$request->id)
				      ->update(['image' => $result]);
  	}
  			
  		return Redirect::to('flipbook');
  }
  // public function doupdateflipbook(Request $request)
  // {
  // 	$checkflipbook=FLipbook::where('id',$request->id)->first();
  // 	$original_image=explode(',',$checkflipbook->image);
  // 	$string=$checkflipbook->image;

  // 	$new_images=$request->file();

  // 	foreach($new_images as $key=>$value)
  // 	{
  // 		if(!empty($request->image))
  // 		{
  // 			foreach($request->image as $img)
  //   		{
		//       $imageName1 = 'flipbook'.rand().'.'.$img->extension();
		//       $image_resize = Image::make($img->getRealPath());              
		//       $image_resize->save(public_path('upload/flipbook/' .$imageName1));
		//       $item[]=$imageName1 ;
  //    		}
  // 		}
  // 		if(!empty($request->$key))
  // 		{
  // 			$images=$value;
	 //  		$imageName1 = 'flipbook'.rand().'.'.$request->$key->extension();
	 //  		$image_resize = Image::make($images->getRealPath());              
		// 		$image_resize->save(public_path('upload/flipbook/' .$imageName1));
		// 	  $new_image[]=$imageName1;
		// 	  $getvariablename=str_replace('new_image', 'old_image', $key);
		// 	  $old_image[]=$request->$getvariablename;
		// 	  foreach($old_image as $value)
		// 	  {
		// 	  	$image_path="upload/flipbook/".$value;
		// 	    if(is_file($image_path))
		// 	    {
		// 	      unlink($image_path);
		// 	    }
		// 	  }
		// 	} 
		// }	

  // 	if(!empty($item))
  // 	{
  // 		 $new_image=implode(',', $item);
  // 		 $result=$string.','.$new_image;
  // 		 DB::table('flipbooks')
		// 		      ->where('id',$request->id)
		// 		      ->update(['image' => $result]);
  // 	}
  // 	elseif(!empty($old_image))
  // 	{
  // 		$search=$old_image;
	 //  	$replace=$new_image;
	 //  	$array=$original_image;
	 //    $result=str_replace($search, $replace, $array);
	 //    $arr=implode(",",$result);
	 //     DB::table('flipbooks')
		// 			      ->where('id',$request->id)
		// 			      ->update(['image' => $arr]);
  // 	}
  // 	else
  // 	{
  // 			DB::table('flipbooks')
		// 			      ->where('id',$request->id)
		// 			      ->update(['image' => $string]);
  // 	}
  //   return Redirect::to('flipbook');
  // }

  public function deleteflipbookimage(Request $request)
  {
  	$checkflipbook=FLipbook::where('id',$request->id)->first();
  	$string=explode(',',$checkflipbook->image);
  	$removeimage=str_replace($request->imagename,'', $string);
  	$result=array_filter($removeimage);
  	$result=implode(',', $result);
  	$image_path="upload/flipbook/".$request->imagename;
    if(is_file($image_path))
    {
      unlink($image_path);
    }
  	DB::table('flipbooks')
					      ->where('id',$request->id)
					      ->update(['image' => $result]);
  	$msg="deleted";
		return $msg;
 	}

 	public function delete_flipbook($id)
 	{
 		$record= DB::table('flipbooks')->where('id',$id)->delete();
 		 session()->flash('success','Deleted Succesfully!');
 		 return Redirect::to('flipbook');
 	}

}    
