<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Portfolio_division;
use App\Models\Portfolio_category;
use App\Models\Portfolio_subcategory;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Redirect;
use Session;
use Image;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PortfolioController extends Controller
{
	public function list_portfolio(Request $request)
  {
  	$division=Portfolio_division::where('is_active','online')->get();
  	$category=Portfolio_category::where('is_active','online')->get();
    $search=$request->search;
    if(!empty($search))
    {
      $columns = Schema::getColumnListing('portfolios');
      $query = Portfolio::query();
      foreach($columns as $column)
      {
        $query->orWhere($column, 'LIKE', '%' . $search . '%');
        $items_array = $query->orderBy('id')->paginate(10);;
      }
    }
    else
    {
  	 $items_array=Portfolio::paginate(10);
    }
    return view('backend.portfolio.list-portfolio',compact('division','category','items_array'));
  }

  public function add_portfolio(Request $request)
  {
  	$division=Portfolio_division::where('is_active','online')->get();
  	$category=Portfolio_category::where('is_active','online')->get();
    return view('backend.portfolio.add-portfolio',compact('division','category'));
  }

  public function edit_portfolio(Request $request)
  {
  	$division=Portfolio_division::where('is_active','online')->get();
  	$category=Portfolio_category::where('is_active','online')->get();
  	$items=Portfolio::where('slug',$request->slug)->first();
    return view('backend.portfolio.edit-portfolio',compact('division','category','items'));
  }

  public function do_add_portfolio(Request $request)
  {
  	$item=new Portfolio;
  	$item->division=$request->division;
  	$item->category=$request->category;
  	$item->subcategory=$request->subcategory;
  	$item->name=$request->name;
  	$item->website=$request->website;
  	$item->launch_date=$request->launch_date;
  	$item->description=$request->description;
    $item->event_name=$request->event_name;
    $item->client_name=$request->client_name;
    $item->location=$request->location;
  	$item->is_active=$request->is_active;
    $item->ranking=$request->ranking;
  	$createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
    $randomstr=Str::random(4);
    if(!empty($request->logo))
		{
      $imageName = $request->name."-logo-".$randomstr.'.'.$request->logo->extension();   
      $item->logo=$imageName;
      $image_resize = Image::make($request->logo->getRealPath());              
      //$image_resize->resize(800,800);
      $image_resize->save(public_path('upload/portfolio/logo/' .$imageName));
    } 

    // Bulk Images
    $current_date=date("Y-m-d");
    $current_time=date("H:i:s");
    $bulk_image=$request->bulk_image;
    if(!empty($bulk_image))
    {
      foreach($bulk_image as $img)
      {
        $bulkimageName= $request->name.$current_date."+".$current_time.rand().'.'.$img->getClientOriginalExtension();
        $image_resize = Image::make($img->getRealPath());              
        // $image_resize->resize(800,800);
        $image_resize->save(public_path('upload/portfolio/' .$bulkimageName));
        $bulk_imageName[]=$bulkimageName;
      }  
      $bulkimage=implode(',', $bulk_imageName);
      $item->images=$bulkimage;
    }
    if(!empty($request->service))
    {
      $services=implode(',',$request->service);
    }
    else
    {
       $services='';
    }
    $item->services=$services;
  	$item->save();
  	$request->session()->flash('success','Saved Successfully !');
		return Redirect::back();
  }

 	public function do_update_portfolio(Request $request)
  {
  	DB::table('portfolios')
			 ->where('id',$request->id)
				->update(['division' =>$request->division,
									'category' => $request->category,
									'subcategory' => $request->subcategory,
									'name' => $request->name,
									'website' => $request->website,
									'launch_date' => $request->launch_date,
									'is_active' => $request->is_active,
									'description' => $request->description,
                  'event_name' => $request->event_name,
                  'client_name' => $request->client_name,
                  'location' => $request->location,
                  'ranking' =>  $request->ranking,
				]);
		$randomstr=Str::random(4);
		if(!empty($request->logo))
    {
      if(!empty($request->old_logo))
      {
        $image_path="upload/portfolio/logo/".$request->old_logo;
        if(is_file($image_path))
       	{
        	unlink($image_path);
        }
      } 

      $imageName = $request->name."-logo-".$randomstr.'.'.$request->logo->extension();
      $image_resize = Image::make($request->logo->getRealPath());              
      // $image_resize->resize(800,800);
      $image_resize->save(public_path('upload/portfolio/logo/' .$imageName));
      $check2=DB::table('portfolios')
            ->where('id',$request->id)
            ->update(['logo' => $imageName]);
    } 
    // Bulk Images
    $current_date=date("Y-m-d");
    $current_time=date("H:i:s");
    $bulk_image=$request->bulk_image;
    $old_bulk_image=$request->old_bulk_image;
    if(!empty($old_bulk_image))
    {
      DB::table('portfolios')->where('id',$request->id)->update(['images' => $old_bulk_image]);
    }
    if(!empty($bulk_image))
    {
      foreach($bulk_image as $img)
      {
        $bulkimageName= $request->name.$current_date."+".$current_time.rand().'.'.$img->getClientOriginalExtension();
        $image_resize = Image::make($img->getRealPath());              
        // $image_resize->resize(800,800);
        $image_resize->save(public_path('upload/portfolio/' .$bulkimageName));
        $image_Name[]=$bulkimageName;
      }  
      $bulkimage=implode(',', $image_Name);
      if(!empty($old_bulk_image))
      {
        $img=$old_bulk_image.','.$bulkimage;
      }
      else
      {
        $img=$bulkimage;
      }
      DB::table('portfolios')->where('id',$request->id)->update(['images' => $img]);
    }
    if(!empty($request->old_service))
    {
      $services=implode(',',$request->old_service);
      DB::table('portfolios')->where('id',$request->id)->update(['services' => $services]);
    }
    if(!empty($request->service))
    {
      $new_services=implode(',',$request->service);
      if(!empty($request->old_service))
      {
        $old_services=implode(',',$request->old_service);
        $services=$old_services.','.$new_services;
      }
      else
      {
        $services=$new_services;
      }
      DB::table('portfolios')->where('id',$request->id)->update(['services' => $services]);
    }
		$request->session()->flash('success','Update Successfully !');
		return Redirect::back();
  }

  public function delete_portfolio_bulkimage(Request $request)
  {
  	$id=$request->id;
    $img=$request->img;
    $image_path="upload/portfolio/".$img;
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    $item=Portfolio::where('id',$id)->first();
    $image=str_replace($img, '', $item->images);
    $arr_img=explode(',',$image);
    $result=array_filter($arr_img);
    $update_img=implode(',',$result);
    DB::table('portfolios')->where('id',$id)->update(['images' => $update_img]);
  }

  public function delete_portfolio_logo(Request $request)
  {
  	$id=$request->id;
    $img=$request->img;
    $image_path="upload/portfolio/logo/".$img;
    if(is_file($image_path))
    {
      unlink($image_path);
    }
    DB::table('portfolios')->where('id',$id)->update(['logo' => '']);
  }

  public function delete_portfolio(Request $request)
  {
  	$get_detail=Portfolio::where('slug',$request->slug)->first();
  	if(!empty($get_detail->logo))
  	{
  		$imagepath="upload/portfolio/logo/".$get_detail->logo;
  		if(is_file($imagepath))
	    {
	      unlink($imagepath);
	    }
  	}
  	if(!empty($get_detail->images))
  	{
  		$image_path="upload/portfolio/".$get_detail->images;
  		if(is_file($image_path))
	    {
	      unlink($image_path);
	    }
  	}
  	DB::table('portfolios')
			 ->where('slug',$request->slug)
				->delete();
		$request->session()->flash('success','Delete Successfully !');
		return Redirect::back();
  }

  public function delete_portfolio_service(Request $request)
  {
    // echo "<pre>"; print_r($_POST);die;
    $get_portfolio=Portfolio::where('id',$request->id)->first();
    $old_service=explode(",",$get_portfolio->services);
    unset($old_service[$request->index]);
    $services=implode(',',$old_service);
    DB::table('portfolios')->where('id',$request->id)->update(['services' => $services]);
    return "updated";
  }

  public function do_active_portfolio(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('portfolios')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;   
  }

  public function do_featured_portfolio(Request $request)
  {
    $parent_id = $request->my_id;
      $mystatus=$request->status;
      DB::table('portfolios')
        ->where('slug',$parent_id)
        ->update(['featured' => $mystatus]);
      $msg="updated";
       $status="success";
    
   
    return response()->json(['status'=>$status,'message'=>$msg]);
  }

  public function divisions(Request $request)
  {
  	$items_array=Portfolio_division::paginate(10);
    return view('backend.portfolio.list-division',compact('items_array'));
  }

  public function add_division(Request $request)
  {
    return view('backend.portfolio.add-division');
  }

  public function do_add_division(Request $request)
  {
  	$item=new Portfolio_division;
  	$item->name=$request->name;
  	$item->is_active=$request->is_active;
  	$createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
  	$item->save();
  	$request->session()->flash('success','Saved Successfully !');
		return Redirect::back();

  }

  public function edit_division(Request $request)
  {
  	$item=Portfolio_division::where('slug',$request->slug)->first();
    return view('backend.portfolio.edit-division',compact('item'));
  }

  public function do_update_division(Request $request)
  {
  	DB::table('portfolio_divisions')
			 ->where('id',$request->id)
				->update(['name' =>$request->name,
									'is_active' => $request->is_active
				]);
		$request->session()->flash('success','Update Successfully !');
		return Redirect::back();
  }

  public function delete_division(Request $request)
  {
  	DB::table('portfolio_divisions')
			 ->where('slug',$request->slug)
				->delete();
			$request->session()->flash('success','Delete Successfully !');
		return Redirect::back();
  }

  public function do_active_division(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('portfolio_divisions')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;   
  }

  public function list_portfolio_category(Request $request)
  {
  	$items_array=Portfolio_category::paginate(10);
    return view('backend.portfolio.list-category',compact('items_array'));
  }

  public function add_portfolio_category(Request $request)
  {
    return view('backend.portfolio.add-category');
  }

  public function do_add_portfolio_category(Request $request)
  {
  	$item=new Portfolio_category;
  	$item->name=$request->name;
  	$item->is_active=$request->is_active;
  	$createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
  	$item->save();
  	$request->session()->flash('success','Saved Successfully !');
		return Redirect::back();
  }

  public function edit_portfolio_category(Request $request)
  {
  	$item=Portfolio_category::where('slug',$request->slug)->first();
    return view('backend.portfolio.edit-category',compact('item'));
  }

  public function do_update_portfolio_category(Request $request)
  {
  	DB::table('portfolio_categories')
			 ->where('id',$request->id)
				->update(['name' =>$request->name,
									'is_active' => $request->is_active
				]);
		$request->session()->flash('success','Update Successfully !');
		return Redirect::back();
  }

  public function delete_portfolio_category(Request $request)
  {
  	$get_detail=Portfolio_category::where('slug',$request->slug)->first();
  	$cat_id=$get_detail->id;

  	$check_portfolio=Portfolio::where('category',$cat_id)->get();
  	if(count($check_portfolio)>0)
  	{
  		$request->session()->flash('error','You cannot delete this Category, There is '.count($check_portfolio).' portfolio assigned, Please Remove them First ! ');
  	}
  	else
  	{
  		DB::table('portfolio_categories')
			 ->where('slug',$request->slug)
				->delete();
			$request->session()->flash('success','Delete Successfully !');
  	}
  	
		return Redirect::back();
  }

  public function do_active_portfolio_category(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('portfolio_categories')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;   
  }

  public function list_portfolio_subcategory(Request $request)
  {
  	$items_array=Portfolio_subcategory::paginate(10);
    return view('backend.portfolio.list-subcategory',compact('items_array'));
  }

  public function add_portfolio_subcategory(Request $request)
  {
  	$category=Portfolio_category::where('is_active','online')->get();
    return view('backend.portfolio.add-subcategory',compact('category'));
  }

  public function do_add_portfolio_subcategory(Request $request)
  {
  	$item=new Portfolio_subcategory;
  	$item->cat_id=$request->category;
  	$item->name=$request->name;
  	$item->is_active=$request->is_active;
  	$createslug=$this->slug($request->name);
    $rand_num=rand(100,1000);
    $slug=$createslug."-".$rand_num;
    $item->slug=$slug;
  	$item->save();
  	$request->session()->flash('success','Saved Successfully !');
		return Redirect::back();
  }

  public function edit_portfolio_subcategory(Request $request)
  {
  	$category=Portfolio_category::where('is_active','online')->get();
  	$item=Portfolio_subcategory::where('slug',$request->slug)->first();
    return view('backend.portfolio.edit-subcategory',compact('item','category'));
  }

  public function do_update_portfolio_subcategory(Request $request)
  {
  	DB::table('portfolio_subcategories')
			 ->where('id',$request->id)
				->update(['name' =>$request->name,
									'cat_id' =>$request->category,
									'is_active' => $request->is_active
				]);
		$request->session()->flash('success','Update Successfully !');
		return Redirect::back();
  }

  public function delete_portfolio_subcategory(Request $request)
  {
  	$get_detail=Portfolio_subcategory::where('slug',$request->slug)->first();
  	$subcat_id=$get_detail->id;

  	$check_portfolio=Portfolio::where('subcategory',$subcat_id)->get();
  	if(count($check_portfolio)>0)
  	{
  		$request->session()->flash('error','You cannot delete this Subcategory, There is '.count($check_portfolio).' portfolio assigned, Please Remove them First ! ');
  	}
  	else
  	{
  		DB::table('portfolio_subcategories')
			 ->where('slug',$request->slug)
				->delete();
			$request->session()->flash('success','Delete Successfully !');
  	}
  
		return Redirect::back();
  }

  public function do_active_portfolio_subcategory(Request $request)
  {
    $parent_id = $request->my_id;
    $mystatus=$request->status;
    DB::table('portfolio_subcategories')
      ->where('slug',$parent_id)
      ->update(['is_active' => $mystatus]);
    $msg="updated";
    return $msg;   
  }

  public function find_subcategory_for_portfolio(Request $request)
  {
  	$cat_id=$request->cat_id;
		if(!empty($cat_id))
		{
      $subcategory=DB::table('portfolio_subcategories')->select('*')->where('cat_id',$cat_id)->get();
      return response()->json([ 'subcategory' =>  $subcategory ]);
    }
    else
    {
      return response()->json(['subcategory' =>  "0"]);
    }
  }

  public function slug($string)
  {
    $string = str_replace(' ', '-', $string);
    $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string));
    return preg_replace('/-+/', '-', $string);
  }


}    
