<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Category_filter;
use Illuminate\Support\Facades\Redirect;
use Image;
use Illuminate\Support\Str;
use Illuminate\carbon\Carbon;

class BrandController extends Controller
{
  public function list()
	{
		$items_array=Brand::orderBy('id','desc')->get(); 
		return view('backend.brand.list-brands',compact('items_array'));	
	}

  public function index()
  {
		$brands=DB::select('select * from brands');
		return view('backend.brand.add-brands',compact('brands'));
	}

	public function add(Request $request)
	{
		$item=new Brand;
		$item->brandname=$request->name;
		$item->brand_description=$request->desc;
		$item->is_active=$request->is_active;
		$item->ranking=$request->ranking;
		$item->metatitle=$request->metatitle;
		$item->metakey=$request->metakey;
		$item->metadesc=$request->metadesc;
    $createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
		$imagedata=$request->thumbs;
		if(!empty($imagedata))
    {   
      $imageName =  rand().'.'.$imagedata->extension(); 
      $item->image1=$imageName; 
      $imagedata->move(public_path('upload/brand/'),$imageName);
    }
	  $image1=$request->bannerimage;
    $randomstr=Str::random(4);
    if(!empty($image1))
    {
      $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $item->bannerimage=$imageName1;
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1205,205);
      $image_resize->save(public_path('upload/brand/' .$imageName1));
    } 
    $item->save();
	  $request->session()->flash('success','Added Successfully !');
    return Redirect::to('listbrand');
  }

  public function edit($slug)
  {
    $items =DB::Table('brands')->select('*')->where('slug',$slug)->first();
    return view('backend.brand.edit-brands',compact('items'));
  }

  public function updatebrand(Request $request)
  {
    $id=$request->get('id');
    DB::table('brands')
      ->where('id',$id)
      ->update(['brandname' => $request->name,
                'brand_description' => $request->desc,
                'is_active' => $request->is_active,
                'ranking' => $request->ranking,
                'metatitle' => $request->metatitle,
                'metakey' => $request->metakey,
                'metadesc' => $request->metadesc,
                'slug' => $request->slug,
         ]);        
    $imagedata=$request->thumbs;
    $slug=$request->slug;
		if(!empty($imagedata))
		{	
      if(!empty($request->oldimage1))
      {
        $image_path="upload/brand/".$request->oldimage1;
        if(is_file($image_path))
        {
          unlink($image_path);
        } 
      } 
			$imageName =  rand().'.'.$imagedata->extension();  
      $imagedata->move(public_path('upload/brand/'),$imageName);
			 DB::table('brands')->where('slug',$slug)->update(['image1' => $imageName]); 
	  }

    $image1=$request->bannerimage;
    $randomstr=Str::random(4);
    if(!empty($image1))
    {
      if(!empty($request->oldbannnerimage))
      {
        $image_path="upload/brand/".$request->oldbannerimage;
        if(is_file($image_path))
        {
          unlink($image_path);
        } 
      } 

      $imageName1 =  $randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1205,205);
      $image_resize->save(public_path('upload/brand/' .$imageName1));
        DB::table('brands')
            ->where('slug',$slug)
            ->update(['bannerimage' => $imageName1]);
    } 
    $request->session()->flash('success','Saved Successfully!');
    return Redirect::to('listbrand');
 }

  public function deletebrand($id)
  {
    $brand=Product::where('brand',$id)->get();
    if(count($brand)>0)
    {
      session()->flash('error','You cannot delete this Brand, There is '.count($brand).' product assigned, Please Remove them First ! ');
      return Redirect::to('listbrand');
    }   
    else
    {
      $record=Brand::find($id);
      if(!empty($record['image1']))
      {
        $image1="upload/brand/".$record['image1'];
        if(is_file($image1))
        {
          unlink($image1);
        } 
      }
      if(!empty($record['bannerimage']))
      {
        $bannerimage="upload/brand/".$record['bannerimage'];
        if(is_file($bannerimage))
        {
          unlink($bannerimage);
        } 
      }

      DB::table('brands')->where('id',$id)->delete();
      session()->flash('success','Deleted Successfully!');
      return Redirect::to('listbrand');
    }   
  }

  public function brandfeatured(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    //$catid=(int)$parent_id;
    DB::table('brands')
        ->where('slug',$parent_id)
        ->update(['featured' => $mystatus]);
    $msg="updated";
    return $msg;    
  }

  public function brandactive(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('brands')
    ->where('slug',$parent_id)
    ->update(['is_active' => $mystatus]);

    $msg="updated";
    return $msg;    
  }

  public function slug($string)
  {
    $string = str_replace(' ', '-', $string);
    $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
    return preg_replace('/-+/', '-', $string);    
  }

  public function delete_brand_thumbnail(Request $request)
  {
    $id=$request->id;
    $record = Brand::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/brand/".$record['image1'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    DB::update('update brands set image1=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);     
  }

  public function delete_brand_banner(Request $request)
  {
    $id=$request->id;
    $record = Brand::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/brand/".$record['bannerimage'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    DB::update('update brands set bannerimage=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);        
  }

}



