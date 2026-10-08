<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Category_filter;
use Illuminate\Support\Facades\Redirect;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class SubcategoryController extends Controller
{
  public function list(Request $request)
	{
    $search=$request->search;
    if(!empty($search))
    {

      $columns = Schema::getColumnListing('subcategories');
      $query = Subcategory::query();
      foreach($columns as $column)
      {
        $query->orWhere($column, 'LIKE', '%' . $search . '%');
        $items_array = $query->orderBy('id')->get();
      }
    }
    else
    {
		  $items_array=Subcategory::orderBy('catid','desc')->get();
    }
		return view('backend.subcategory.list-categories',compact('items_array'));	
	}

  public function index()
  {
		$categories=DB::select('select * from categories');
		return view('backend.subcategory.add-categories',compact('categories'));
	}

	public function add(Request $request)
	{
		$item=new Subcategory;
		$item->catname=$request->name;
		$item->catid=$request->catid;
		$item->catdescription=$request->desc;
		$item->is_active=$request->is_active;
		$item->ranking=$request->ranking;
		$item->metatitle=$request->metatitle;
		$item->metakey=$request->metakey;
		$item->metadesc=$request->metadesc;
     $item->title=$request->title;
    $item->og_type=$request->og_type;
    $item->og_url=$request->og_url;
    $item->twitter_card=$request->twitter_card;
    $item->twitter_url=$request->twitter_url;
		$createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
		$imagedata=$request->thumbs;
		if(!empty($imagedata))
    {   
      $imageName =  rand().'.'.$imagedata->extension(); 
      $item->image1=$imageName; 
      $imagedata->move(public_path('upload/subcategory/'),$imageName);
    }
	  $image1=$request->bannerimage;
    $randomstr=Str::random(4);
    if(!empty($image1))
    {
      $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $item->bannerimage=$imageName1;
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1920,500);
      $image_resize->save(public_path('upload/subcategory/' .$imageName1));
    } 
    $item->save();
	  $request->session()->flash('successMsg','Added Successfully !');
    return Redirect::to('listsubcat');
  }

  public function edit($slug)
  {
    $categories=DB::select('select * from categories');
    $items =DB::Table('subcategories')->select('*')->where('slug',$slug)->first();
    return view('backend.subcategory.edit-categories',compact('items','categories'));
  }

  public function updatecat(Request $request)
  {
    $slug=$request->get('slug');
    DB::table('subcategories')
        ->where('slug',$slug)
        ->update(['catname' => $request->name,
        			    'catid' => $request->catid,
                	'catdescription' => $request->desc,
                	'is_active' => $request->is_active,
					        'ranking'=>$request->ranking,
                	'metatitle' => $request->metatitle,
                	'metakey' => $request->metakey,
                	'metadesc' => $request->metadesc,
                  'title' => $request->title,
                  'og_type' => $request->og_type,
                  'og_url' => $request->og_url,
                  'twitter_card' => $request->twitter_card,
                  'twitter_url' => $request->twitter_url,
         ]);        
    $imagedata=$request->thumbs;
    if(!empty($imagedata))
    {   
      if(!empty($request->oldimage1))
      {
        $image_path="upload/subcategory/".$request->oldimage1;
        if(is_file($image_path))
        {
          unlink($image_path);
        }
      } 
      $imageName =  rand().'.'.$imagedata->extension(); 
      $imagedata->move(public_path('upload/subcategory/'),$imageName);
      DB::table('subcategories') ->where('slug',$slug)->update(['image1' => $imageName]);
    }
	 $image1=$request->bannerimage;
   $randomstr=Str::random(4);
   if(!empty($image1))
   {
      if(!empty($request->oldbannnerimage))
      {
        $image_path="upload/subcategory/".$request->oldbannerimage;
        if(is_file($image_path))
        {
          unlink($image_path);
        }
      }  
      $imageName1 = $request->name."-".$randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1920,500);
      $image_resize->save(public_path('upload/subcategory/' .$imageName1));
      DB::table('subcategories')
        ->where('slug',$slug)
        ->update(['bannerimage' => $imageName1]);
    } 
    $request->session()->flash('successMsg','Saved Successfully!');
    return Redirect::to('listsubcat');
  }

  public function deletecat($id)
  {
    $products=Product::where('subcategory',$id)->get();
    if(count($products)>0)
    {
      session()->flash('error','You cannot delete this Subcategory, There is '.count($products).' product assigned, Please Remove them First ! ');
      return Redirect::to('listsubcat');
    }   
    else
    {
      $record=Subcategory::find($id);
      if(!empty($record['image1']))
      {
        $image1="upload/subcategory/".$record['image1'];
        if(is_file($image1))
        {
          unlink($image1);
        }
      }
      if(!empty($record['bannerimage']))
      {
        $bannerimage="upload/subcategory/".$record['bannerimage'];
        if(is_file($bannerimage))
        {
          unlink($bannerimage);
        } 
      }
      DB::table('subcategories')->where('id',$id)->delete();
      session()->flash('success','Deleted Succesfully!');
      return Redirect::to('listsubcat');

    }  
  }

  public function findsubcat(Request $request)
  {
    $parent_id = $request->my_id;
    $subcategories =DB::Table('subcategories')->select('*')->where('catid',$parent_id)->get();
    return response()->json([
        'subcategories' => $subcategories
   		 ]);
  }    

  public function findsubcategory(Request $request)
  {
    $parent_id = $request->id;
    $subcategories =DB::Table('subcategories')->select('*')->where('id',$parent_id)->get();
    return response()->json([
        'subcategory' =>  $subcategories
         ]);
  }    

  public function findcategory(Request $request)
  {
    $parent_id = $request->id;
    $subcategories =DB::Table('subcategories')->select('*')->where('catid',$parent_id)->get();
    return response()->json([
        'subcategory' =>  $subcategories]);
  }    

  public function findsubcat_filter(Request $request)
  {
    $subcat_slug = $request->subcat_slug;
    $cat_slug = $request->cat_slug;
    $cat_filters =DB::Table('category_filters')->select('*')->where('category_slug',$cat_slug)->get();
    $subcat_filters=DB::Table('category_filters')->select('*')->where('subcat_slug',$subcat_slug)->get();
    if(!empty($subcat_slug))
    {
      return response()->json([
        'subcat_filters' =>  $subcat_filters
           ]);
    }
    else
    {
      return response()->json([
                'subcat_filters' =>  $cat_filters
         ]);
    }
   
  }

  public function deletefilter(Request $request)
  {
    $id=$request->id;
    DB::table('category_filters')->where('id',$id)->delete();
    $msg="deleted";
    return $msg;     
  }  

  public function slug($string)
  {
    $string = str_replace(' ', '-', $string);
    $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
    return preg_replace('/-+/', '-', $string);    
  } 

  public function subcatactive(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('subcategories')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;    
 }

  public function delete_subcategory_thumbnail(Request $request)
  {
    $id=$request->id;
    $record = Subcategory::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/subcategory/".$record['image1'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    
    DB::update('update subcategories set image1=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);     
  }

  public function delete_subcategory_banner(Request $request)
  {
    $id=$request->id;
    $record = Subcategory::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/subcategory/".$record['bannerimage'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    DB::update('update subcategories set bannerimage=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);        
  }

}

