<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Childsubcategory;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Category_filter;
use Illuminate\Support\Facades\Redirect;
use Image;
use Illuminate\Support\Str;

class ChildsubcategoryController extends Controller
{
   public function list()
	{
		$items_array=Childsubcategory::orderBy('id','desc')->paginate(10);
		return view('backend.childsubcategory.list-categories',compact('items_array'));	
	}

    public function index(){

		$categories=DB::select('select * from subcategories');
		return view('backend.childsubcategory.add-categories',compact('categories'));
	}

	public function add(Request $request)
	{
    	
    	// print_r($request->catid);die; 
		$item=new Childsubcategory;

		$item->catname=$request->name;

        $catid=implode(',',$request->catid);
		$item->catid=$catid;
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

            $imagedata->move(public_path('upload/childsubcategory/'),$imageName);

        }

        

	    $image1=$request->bannerimage;
        $randomstr=Str::random(4);

        if(!empty($image1))
        {
            $imageName1 = $randomstr.'-1'.'.'.$request->bannerimage->extension();  
            $item->bannerimage=$imageName1;
            $image_resize = Image::make($image1->getRealPath());              
            $image_resize->resize(1920,500);
            $image_resize->save(public_path('upload/childsubcategory/' .$imageName1));

            
        } 

        $item->save();
	    
	    $request->session()->flash('successMsg','Added Succesfully !');
       return Redirect::to('listchildsubcat');

    }

    public function edit($slug)
    {
    	$categories=DB::select('select * from subcategories');
    	$items =DB::Table('childsubcategories')->select('*')->where('slug',$slug)->first();
    	return view('backend.childsubcategory.edit-categories',compact('items','categories'));
    }

    public function updatechildsubcat(Request $request)
    {
    	// echo "<pre>"; print_r($_POST);die;
    	$slug=$request->get('slug');
        $catid=implode(',',$request->catid);
        DB::table('childsubcategories')
        ->where('slug',$slug)
        ->update(['catname' => $request->name,
        			'catid' => $catid,
                	'catdescription' => $request->desc,
                	'is_active' => $request->is_active,
					'ranking'=>$request->ranking,
                	'metatitle' => $request->metatitle,
                	'metakey' => $request->metakey,
                	'metadesc' => $request->metadesc,
          	

         ]);        
        
        $imagedata=$request->thumbs;
        if(!empty($imagedata))
        {   

            $imageName =  rand().'.'.$imagedata->extension(); 

            $imagedata->move(public_path('upload/childsubcategory/'),$imageName);

             DB::table('childsubcategories') ->where('slug',$slug)->update(['image1' => $imageName]);

        }
		
	   
	   	$image1=$request->bannerimage;
        $randomstr=Str::random(4);
        if(!empty($image1))
        {
           
            $imageName1 = $request->name."-".$randomstr.'-1'.'.'.$request->bannerimage->extension();  
            $image_resize = Image::make($image1->getRealPath());              
            $image_resize->resize(1920,500);
            $image_resize->save(public_path('upload/childsubcategory/' .$imageName1));

            DB::table('childsubcategories')
            ->where('slug',$slug)
            ->update(['bannerimage' => $imageName1]);
            
        } 
        $request->session()->flash('successMsg','Saved Succesfully!');
        return Redirect::to('listchildsubcat');
    	
    }

    public function deletecat($id)
    {

        $products=Product::where('childsubcategory',$id)->get();

        if(count($products)>0)
         {
            session()->flash('error','You cannot delete this Childsubcategory, There is '.count($products).' product assigned, Please Remove them First ! ');
            return Redirect::to('listsubcat');
         }   
         else
         {
            DB::table('subcategories')->where('id',$id)->delete();
            session()->flash('success','Deleted Succesfully!');
            return Redirect::to('listsubcat');
         }  
       
    	
    	//return redirect('admin.add-categories',compact('categories'));
    }


   public function childsubcatactive(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('childsubcategories')

            ->where('slug',$parent_id)

            ->update(['is_active' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }

    public function slug($string)
    {
        $string = str_replace(' ', '-', $string);
        $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
        return preg_replace('/-+/', '-', $string);    
    } 

    
    public function delete_childsubcategory_thumbnail(Request $request)
    {

        $id=$request->id;
        $record = Childsubcategory::find($id);
        $id=$record['id'];
        $image='';
        $image_path="upload/childsubcategory/".$record['image1'];
        unlink($image_path);
        DB::update('update childsubcategories set image1=? where id=?',[$image,$id]);
        return response()->json(['status'=>'success']);     
    }

    public function delete_childsubcategory_banner(Request $request){

        $id=$request->id;
        $record =Childsubcategory::find($id);
        $id=$record['id'];
        $image='';
        $image_path="upload/childsubcategory/".$record['bannerimage'];
        unlink($image_path);
        DB::update('update childsubcategories set bannerimage=? where id=?',[$image,$id]);
         return response()->json(['status'=>'success']);        
        }


    
}
