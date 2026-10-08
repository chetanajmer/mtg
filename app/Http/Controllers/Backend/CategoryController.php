<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Redirect;
use App\Models\Category_filter;
use Image;
use Illuminate\Support\Str;
use Illuminate\carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
	public function list(Request $request)
	{
    $search=$request->search;
    if(!empty($search))
    {
      $columns = Schema::getColumnListing('categories');
      $query = Category::query();
      foreach($columns as $column)
      {
        $query->orWhere($column, 'LIKE', '%' . $search . '%');
        $items_array = $query->orderBy('id')->get();
      }
    }
    else
    {
		  $items_array=Category::orderBy('id','desc')->get(); 
    }
		return view('backend.category.list-categories',compact('items_array'));	
	}

  public function index()
  {
		$parentcategories=DB::select('select * from parentcategories');
		return view('backend.category.add-categories',compact('parentcategories'));
	}

	public function add(Request $request)
	{
		$item=new Category;
		$item->catname=$request->name;
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
      $imagedata->move(public_path('upload/category/'),$imageName);
    }

	  $image1=$request->bannerimage;
    $randomstr=Str::random(4);
    if(!empty($image1))
    {
      $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $item->bannerimage=$imageName1;
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1920,500);
      $image_resize->save(public_path('upload/category/' .$imageName1));
    } 

    
    $item->save();
	  $request->session()->flash('success','Added Successfully !');
    return Redirect::to('listcategory');
  }

  public function edit($slug)
  {
    $parentcategories=DB::Table('parentcategories')->select('*')->get();
    $items =DB::Table('categories')->select('*')->where('slug',$slug)->first();
    return view('backend.category.edit-categories',compact('items','parentcategories'));
  }

  public function updatecat(Request $request)
  {
    $id=$request->get('id');
      DB::table('categories')
        ->where('id',$id)
        ->update(['catname' => $request->name,
                	'catdescription' => $request->desc,
                	'is_active' => $request->is_active,
                	'ranking' => $request->ranking,
                	'metatitle' => $request->metatitle,
                	'metakey' => $request->metakey,
                	'metadesc' => $request->metadesc,
                  'slug' => $request->slug,
                  'title' => $request->title,
                  'og_type' => $request->og_type,
                  'og_url' => $request->og_url,
                  'twitter_card' => $request->twitter_card,
                  'twitter_url' => $request->twitter_url,
         ]);        
    $imagedata=$request->thumbs;
    $slug=$request->slug;
		if(!empty($imagedata))
		{	
      if(!empty($request->oldimage1))
      {
        $image_path="upload/category/".$request->oldimage1;
        if(is_file($image_path))
        {
           unlink($image_path);
        }
       
      } 
			$imageName =  rand().'.'.$imagedata->extension();  
      $imagedata->move(public_path('upload/category/'),$imageName);
			 DB::table('categories')
        ->where('slug',$slug)
        ->update(['image1' => $imageName]);
	  }

    $image1=$request->bannerimage;
    $randomstr=Str::random(4);
    if(!empty($image1))
    {
      if(!empty($request->oldbannnerimage))
      {
        $image_path="upload/category/".$request->oldbannerimage;
        if(is_file($image_path))
        {
          unlink($image_path);
        } 
      }  
      $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  
      $image_resize = Image::make($image1->getRealPath());              
      $image_resize->resize(1920,500);
      $image_resize->save(public_path('upload/category/' .$imageName1));
      DB::table('categories')
            ->where('slug',$slug)
            ->update(['bannerimage' => $imageName1]);
    } 

    

    $request->session()->flash('success','Saved Succesfully!');
    return Redirect::to('listcategory');
  }

  public function deletecat($id)
  {
    $products=Product::where('category',$id)->get();
    if(count($products)>0)
    {
      session()->flash('error','You cannot delete this Category, There is '.count($products).' product assigned, Please Remove them First ! ');
      return Redirect::to('listcategory');
    }   
    else
    {
      $record=Category::find($id);
      if(!empty($record['image1']))
      {
        $image1="upload/category/".$record['image1'];
        if(is_file($image1))
        {
          unlink($image1);
        }
        
      }
      if(!empty($record['bannerimage']))
      {
        $bannerimage="upload/category/".$record['bannerimage'];
        if(is_file($bannerimage))
        {
          unlink($bannerimage);
        } 
      }
      DB::table('categories')->where('id',$id)->delete();
      session()->flash('success','Deleted Successfully!');
      return Redirect::to('listcategory');
    }  
  }

  public function catfeatured(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('categories')
        ->where('slug',$parent_id)
        ->update(['featured' => $mystatus]);
    $msg="updated";
    return $msg;    
  }

  public function catactive(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('categories')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;    
  }

  public function findcategoryforproduct(Request $request)
  {
    $parent_id = $request->id;
    $categories =DB::Table('categories')->select('*')->where('brand_id',$parent_id)->get();
    return response()->json([
        'category' =>  $categories
      ]);
  }   

  public function slug($string)
  {
    $string = str_replace(' ', '-', $string);
    $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
    return preg_replace('/-+/', '-', $string);    
  }

  public function delete_category_thumbnail(Request $request)
  {
    $id=$request->id;
    $record = Category::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/category/".$record['image1'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    DB::update('update categories set image1=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);     
  }

  public function delete_category_banner(Request $request)
  {
    $id=$request->id;
    $record = Category::find($id);
    $id=$record['id'];
    $image='';
    $image_path="upload/category/".$record['bannerimage'];
    if(is_file($image_path))
    {
      unlink($image_path);
    }
   
    DB::update('update categories set bannerimage=? where id=?',[$image,$id]);
    return response()->json(['status'=>'success']);        
  }

  
  public function transfer_category()
  {
    $subcategories=DB::table('subcategories')->get();
    $categories=DB::table('categories')->get();
    return view('backend.category.transfer-category',compact('categories','subcategories'));       
  }

  public function dotransfercategory(Request $request)
  {
    // echo "<pre>";print_r($_POST);die;
    $cat_from=$request->cat_from;
    $cat_to=$request->cat_to;
    

    DB::table('products')
      ->where('subcategory',$cat_from)
      ->update(['subcategory' => $cat_to]);
      session()->flash('success','Category Transfer Successfully!');
      return Redirect::to('transfer_category');      
  }

  public function dotransfersubcategory(Request $request)
  {
    //echo "<pre>";print_r($_POST);die;
    $subcat_from=$request->subcat_from;
    $cat_to=$request->cat_to;
    

    DB::table('products')
      ->where('subcategory',$subcat_from)
      ->update(['category' => $cat_to]);


      DB::table('subcategories')
      ->where('id',$subcat_from)
      ->update(['catid' => $cat_to]);



      session()->flash('success','Category Transfer Successfully!');
      return Redirect::to('transfer_category');      
  }

}



