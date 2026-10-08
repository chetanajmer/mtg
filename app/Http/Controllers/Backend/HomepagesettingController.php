<?php



namespace App\Http\Controllers\Backend;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;

use App\Models\Parentcategory;

use App\Models\Category;

use App\Models\Color;

use App\Models\Homepage_setting;

use App\Models\Brand;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Category_filter;

use Illuminate\Support\Facades\Redirect;

use Image;

use Illuminate\Support\Str;

use Illuminate\carbon\Carbon;

use App\Models\Category_setting;



class HomepagesettingController extends Controller

{

	public function category_section()
	{
		$category=Category::where('is_active','online')->get();
		$color=Color::get();
		$category1=Category_setting::where('id','1')->first();
		$category2=Category_setting::where('id','2')->first();
		$category3=Category_setting::where('id','3')->first();
		$category4=Category_setting::where('id','4')->first();
		$category5=Category_setting::where('id','5')->first();
		$category6=Category_setting::where('id','6')->first();
		$category7=Category_setting::where('id','7')->first();
		$category8=Category_setting::where('id','8')->first();
		$category9=Category_setting::where('id','9')->first();
		return view('backend.homepage.homepage_settings',compact('category','category1','category2','category3','category4','category5','category6','category7','category8','category9','color'));	

	}


	public function categorysettingupdate(Request $request)
	{
		// echo "<pre>"; print_r($_POST);die;
		if(!empty($request->category1))
		{
			$category_name=DB::table('categories')->where('id',$request->category1)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','1')
	        ->update(['category_status' => $request->categorystatus1,
	        					'category_name' => $catname,
	                  'category_id' => $request->category1,
	                  'product_limit' => $request->productlimit1,
	                  'category_slug' => $catslug,
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon1=$request->image_icon1;
	    if(!empty($image_icon1))
	    {
	      if(!empty($request->oldimage_icon1))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon1;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-1'.'.'.$request->image_icon1->extension(); 
	      $image_resize = Image::make($image_icon1->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','1')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors1;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','1')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','1')->update(['colors' => '']);
       }
		}
		
		if(!empty($request->category2))
		{
			$category_name=DB::table('categories')->where('id',$request->category2)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','2')
	        ->update(['category_status' => $request->categorystatus2,
	        			'category_name' => $catname,
	                    'category_id' => $request->category2,
	                    'product_limit' => $request->productlimit2,
	              'category_slug' =>$catslug
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon2=$request->image_icon2;
	    if(!empty($image_icon2))
	    {
	      if(!empty($request->oldimage_icon2))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon2;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-2'.'.'.$request->image_icon2->extension(); 
	      $image_resize = Image::make($image_icon2->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','2')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors2;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','2')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','2')->update(['colors' => '']);
       }
		}

		if(!empty($request->category3))
		{
			$category_name=DB::table('categories')->where('id',$request->category3)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','3')
	        ->update(['category_status' => $request->categorystatus3,
	        	'category_name' => $catname,
	                    'category_id' => $request->category3,
	                    'product_limit' => $request->productlimit3,
	                 'category_slug' =>$catslug
	             ]);

	    $randomstr=Str::random(4);
	    $image_icon3=$request->image_icon3;
	    if(!empty($image_icon3))
	    {
	      if(!empty($request->oldimage_icon3))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon3;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-3'.'.'.$request->image_icon3->extension(); 
	      $image_resize = Image::make($image_icon3->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','3')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors3;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','3')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','3')->update(['colors' => '']);
       }
		}

		if(!empty($request->category4))
		{
			$category_name=DB::table('categories')->where('id',$request->category4)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','4')
	        ->update(['category_status' => $request->categorystatus4,
	        	'category_name' => $catname,
	                    'category_id' => $request->category4,
	                    'product_limit' => $request->productlimit4,
	                    'category_slug' =>$catslug
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon4=$request->image_icon4;
	    if(!empty($image_icon4))
	    {
	      if(!empty($request->oldimage_icon4))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon4;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-4'.'.'.$request->image_icon4->extension(); 
	      $image_resize = Image::make($image_icon4->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','4')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors4;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','4')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','4')->update(['colors' => '']);
       }
		}

		if(!empty($request->category5))
		{
			$category_name=DB::table('categories')->where('id',$request->category5)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','5')
	        ->update(['category_status' => $request->categorystatus5,
	        	'category_name' => $catname,
	                    'category_id' => $request->category5,
	                    'product_limit' => $request->productlimit5,
	                    'category_slug' =>$catslug
	             ]);
	   	$randomstr=Str::random(4);
	    $image_icon5=$request->image_icon5;
	    if(!empty($image_icon5))
	    {
	      if(!empty($request->oldimage_icon5))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon5;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-5'.'.'.$request->image_icon5->extension(); 
	      $image_resize = Image::make($image_icon5->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','5')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors5;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','5')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','5')->update(['colors' => '']);
       }
		}

		if(!empty($request->category6))
		{
			$category_name=DB::table('categories')->where('id',$request->category6)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','6')
	        ->update(['category_status' => $request->categorystatus6,
	        	'category_name' => $catname,
	                    'category_id' => $request->category6,
	                    'product_limit' => $request->productlimit6,
	                    'category_slug' =>$catslug
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon6=$request->image_icon6;
	    if(!empty($image_icon6))
	    {
	      if(!empty($request->oldimage_icon6))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon6;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-6'.'.'.$request->image_icon6->extension(); 
	      $image_resize = Image::make($image_icon6->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','6')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors6;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','6')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','6')->update(['colors' => '']);
       }
		}

		if(!empty($request->category7))
		{
			$category_name=DB::table('categories')->where('id',$request->category7)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','7')
	        ->update(['category_status' => $request->categorystatus7,
	        	'category_name' => $catname,
	                    'category_id' => $request->category7,
	                    'product_limit' => $request->productlimit7,
	                    'category_slug' =>$catslug,
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon7=$request->image_icon7;
	    if(!empty($image_icon7))
	    {
	      if(!empty($request->oldimage_icon7))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon7;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-7'.'.'.$request->image_icon7->extension(); 
	      $image_resize = Image::make($image_icon7->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','7')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors7;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','7')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','7')->update(['colors' => '']);
       }
		}

		if(!empty($request->category8))
		{
			$category_name=DB::table('categories')->where('id',$request->category8)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','8')
	        ->update(['category_status' => $request->categorystatus8,
	        	'category_name' => $catname,
	                    'category_id' => $request->category8,
	                    'product_limit' => $request->productlimit8,
	                    'category_slug' =>$catslug
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon8=$request->image_icon8;
	    if(!empty($image_icon8))
	    {
	      if(!empty($request->oldimage_icon8))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon8;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-8'.'.'.$request->image_icon8->extension(); 
	      $image_resize = Image::make($image_icon8->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','8')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors8;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','8')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','8')->update(['colors' => '']);
       }
		}

		if(!empty($request->category9))
		{
			$category_name=DB::table('categories')->where('id',$request->category9)->first();
			$catname=$category_name->catname;
			$catslug=$category_name->slug;
			DB::table('category_settings')
	        ->where('id','9')
	        ->update(['category_status' => $request->categorystatus9,
	        	'category_name' => $catname,
	                    'category_id' => $request->category9,
	                    'product_limit' => $request->productlimit9,
	                    'category_slug' =>$catslug
	             ]);
	    $randomstr=Str::random(4);
	    $image_icon9=$request->image_icon9;
	    if(!empty($image_icon9))
	    {
	      if(!empty($request->oldimage_icon9))
	      {
	        $image_path="upload/category/icon/".$request->oldimage_icon9;
	        if(is_file($image_path))
	        {
	          unlink($image_path);
	        } 
	      }  
	      $imageName1 = $randomstr.'-9'.'.'.$request->image_icon9->extension(); 
	      $image_resize = Image::make($image_icon9->getRealPath());              
	      $image_resize->resize(100,100);
	      $image_resize->save(public_path('upload/category/icon/' .$imageName1));
	      DB::table('category_settings')
	            ->where('id','9')
	            ->update(['image_icon' => $imageName1]);
	    } 

	    $color=$request->colors9;
       if(!empty($color))
       {
          $colors=implode(",", $color);
          DB::table('category_settings')->where('id','9')->update(['colors' => $colors]);
       }
       else
       {
         DB::table('category_settings')->where('id','9')->update(['colors' => '']);
       }
		}
		 
         $request->session()->flash('success','Category Settings Updated Successfully !');    
        return Redirect::back();  

	}
    
	public function tag_section(Request $request)
	{
	  $tag=Homepage_setting::where('id','1')->first();
		return view('backend.homepage.tag_settings',compact('tag'));
	}

	public function tagsettingupdate(Request $request)
	{
		// echo "<pre>"; print_r($_POST);die;
		if(!empty($request->id))
		{
			DB::table('homepage_settings')
	        ->where('id','1')
	        ->update(['meta_title' => $request->meta_title,
	        					'meta_key' => $request->meta_key,
	                  'meta_desc' => $request->meta_desc,
	                  'title' => $request->title,
	                  'og_type' =>$request->og_type,
	                  'og_url' =>$request->og_url,
	                  'twitter_card' =>$request->twitter_card,
	                  'twitter_url' =>$request->twitter_url,
	             ]);
		}
		$request->session()->flash('success','Tag Settings Updated Successfully !');    
        return Redirect::back();  
	}

	public function content_section()
	{
		$item=Homepage_setting::where('id','1')->first();
		return view('backend.homepage.content',compact('item'));
	}

	public function contentupdate(Request $request)
	{
		// echo "<pre>"; print_r($_POST);die;
			DB::table('homepage_settings')
	        ->where('id','1')
	        ->update(['content' => $request->description,
	        					
	             ]);
		
		$request->session()->flash('success','Tag Settings Updated Successfully !');    
        return Redirect::back();  
	}
}

