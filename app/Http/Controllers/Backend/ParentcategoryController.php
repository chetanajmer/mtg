<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Parentcategory;
use App\Models\Product;
use App\Models\Category_filter;
use Illuminate\Support\Facades\Redirect;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ParentcategoryController extends Controller
{
   public function list(Request $request)
	{
        $search=$request->search;
        if(!empty($search))
        {
            $columns = Schema::getColumnListing('parentcategories');
            $query = Parentcategory::query();
            foreach($columns as $column)
            {
              $query->orWhere($column, 'LIKE', '%' . $search . '%');
              $items_array = $query->orderBy('id')->paginate(10);
            }
        }
        else
        {
            $items_array=Parentcategory::orderBy('id','desc')->paginate(10);
        }
		
		return view('backend.parentcategory.list-categories',compact('items_array'));	
	}

    public function index(){

		$categories=DB::select('select * from categories');
		return view('backend.parentcategory.add-categories',compact('categories'));
	}

	public function addparentcat(Request $request)
	{
    	
    	$item=new Parentcategory;

        // $item->brand_id=$request->brand;
        
        $item->catname=$request->name;

        $item->catdescription=$request->desc;

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

            $imagedata->move(public_path('upload/parentcategory/'),$imageName);

        }

        

        $image1=$request->bannerimage;

        $randomstr=Str::random(4);



        if(!empty($image1))

        {

            $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  

            $item->bannerimage=$imageName1;

            $image_resize = Image::make($image1->getRealPath());              

            $image_resize->resize(1920,500);

            $image_resize->save(public_path('upload/parentcategory/' .$imageName1));
        } 

         $whatapp_no=str_replace('+', '', $request->whatapp_no);

         $item->whatapp_no=$whatapp_no;

        $item->save();

        $request->session()->flash('success','Added Successfully !');

        return Redirect::to('listparentcategory');

    }

    public function edit($slug)
    {
    	$items =DB::Table('parentcategories')->select('*')->where('slug',$slug)->first();
    	return view('backend.parentcategory.edit-categories',compact('items'));
    }

    public function updateparentcat(Request $request)
    {
    	// echo "<pre>"; print_r($_POST);die;
    	$slug=$request->get('slug');
        $whatapp_no=str_replace('+', '', $request->whatapp_no);
        DB::table('parentcategories')
        ->where('slug',$slug)
        ->update(['catname' => $request->name,
                	'catdescription' => $request->desc,
                	'is_active' => $request->is_active,
					        'ranking'=>$request->ranking,
                	'metatitle' => $request->metatitle,
                	'metakey' => $request->metakey,
                	'metadesc' => $request->metadesc,
                    'whatapp_no' => $whatapp_no,
         ]);        
        
        $imagedata=$request->thumbs;
        if(!empty($imagedata))
        {   

          if(!empty($request->oldimage1))
          {
            $image_path="upload/parentcategory/".$request->oldimage1;
            unlink($image_path);
          } 
            $imageName =  rand().'.'.$imagedata->extension(); 

            $imagedata->move(public_path('upload/parentcategory/'),$imageName);

             DB::table('parentcategories')->where('slug',$slug)->update(['image1' => $imageName]);

        }
		
	   
	   	$image1=$request->bannerimage;
        $randomstr=Str::random(4);
        if(!empty($image1))
        {
           if(!empty($request->oldbannnerimage))
            {
              $image_path="upload/parentcategory/".$request->oldbannerimage;
              unlink($image_path);
            } 
            $imageName1 = $request->name."-".$randomstr.'-1'.'.'.$request->bannerimage->extension();  
            $image_resize = Image::make($image1->getRealPath());              
            $image_resize->resize(1920,500);
            $image_resize->save(public_path('upload/parentcategory/' .$imageName1));

            DB::table('parentcategories')
            ->where('slug',$slug)
            ->update(['bannerimage' => $imageName1]);
            
        } 
	   
        $request->session()->flash('successMsg','Saved Successfully!');
        return Redirect::to('listparentcategory');
    	
    }

    public function deleteparentcat($id)
    {

        $products=Product::where('parentcategory',$id)->get();

        if(count($products)>0)
         {
            session()->flash('error','You cannot delete this Parentcategory, There is '.count($products).' product assigned, Please Remove them First ! ');
            return Redirect::to('listsubcat');
         }   
         else
         {
          $record=Parentcategory::find($id);
            if(!empty($record['image1']))
            {
              $image1="upload/parentcategory/".$record['image1'];
              unlink($image1);
            }
            if(!empty($record['bannerimage']))
            {
              $bannerimage="upload/parentcategory/".$record['bannerimage'];
              unlink($bannerimage);
            }
            DB::table('parentcategories')->where('id',$id)->delete();
            session()->flash('success','Deleted Succesfully!');
            return Redirect::to('listparentcategory');
         }  
       
    	
    	//return redirect('admin.add-categories',compact('categories'));
    }


    public function findparentcat(Request $request)
    {

        $parent_id = $request->my_id;
        //$catid=(int)$parent_id;
        

        $subcategories =DB::Table('parentcategories')->select('*')->where('catid',$parent_id)->get();

        //print_r($subcategories);
    
        return response()->json([
        'subcategories' => $subcategories
   		 ]);

   		 //return $parent_id;

    }    

     public function findsubcategory(Request $request)
    {

        $parent_id = $request->id;
        
        $subcategories =DB::Table('parentcategories')->select('*')->where('id',$parent_id)->get();

        // print_r($subcategories['0']->slug);die;
        // $subcat_filters=DB::Table('category_filters')->select('*')->where('subcat_slug',$subcategories['0']->slug)->get();

        // echo "<pre>";print_r($subcat_filters);die;
    
        return response()->json([
        'subcategory' =>  $subcategories
         ]);

         //return $parent_id;

    }    

     public function findcategory(Request $request)
    {

        $parent_id = $request->id;
        
        $subcategories =DB::Table('parentcategories')->select('*')->where('catid',$parent_id)->get();

    
        return response()->json([
        'subcategory' =>  $subcategories
         ]);

         //return $parent_id;

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
       

         //return $parent_id;

    } 

    public function deletefilter(Request $request){

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

    public function catparentactive(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

            DB::table('parentcategories')

            ->where('slug',$parent_id)

            ->update(['is_active' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }

    public function catparentfeatured(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('parentcategories')

            ->where('slug',$parent_id)

            ->update(['featured' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }

    public function delete_parentcat_thumbnail(Request $request)
    {

        $id=$request->id;
        $record = Parentcategory::find($id);
        $id=$record['id'];
        $image='';
        $image_path="upload/parentcategory/".$record['image1'];
        unlink($image_path);
        DB::update('update parentcategories set image1=? where id=?',[$image,$id]);
        return response()->json(['status'=>'success']);     
    }

    public function delete_parentcat_banner(Request $request){

        $id=$request->id;
        $record = Parentcategory::find($id);
        $id=$record['id'];
        $image='';
        $image_path="upload/parentcategory/".$record['bannerimage'];
        unlink($image_path);
        DB::update('update parentcategories set bannerimage=? where id=?',[$image,$id]);
         return response()->json(['status'=>'success']);        
        }


    public function getparentcategories(Request $request){

        $categories=Parentcategory::where('featured','online')->where('is_active','online')->get();
        return json_encode($categories);
    }    
        
}
