<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Tmporder;
use App\Models\Weightshipping;
use App\Models\Variation;
use App\Models\Color;
use DB;

class FilterController extends Controller
{
    

    public function find_subcat_filter(Request $request)
    {
        $id=$request->id;
        $subcategories =DB::Table('subcategories')->select('*')->where('id',$id)->get();
        $slug=$subcategories['0']->slug;
        $subcat_filters =DB::Table('category_filters')->select('*')->where('subcat_slug',$slug)->get();
        return response()->json([
        'subcat_filters' =>  $subcat_filters
        ]);
        
    }

    public function find_cat_filter(Request $request)
    {
        $id=$request->id;
        $categories =DB::Table('categories')->select('*')->where('id',$id)->get();
        $slug=$categories['0']->slug;
        $cat_filters =DB::Table('category_filters')->select('*')->where('category_slug',$slug)->get();
        return response()->json([
        'cat_filters' =>  $cat_filters
        ]);
        
    }






















    public function variation(Request $request)
    {
    		
    		$items_array=Variation::paginate(10);
    		return view('backend.product.variation',compact('items_array'));
    }


    public function add(Request $request)
    {

       $item=new Variation;
       $item->name=$request->name;
       $item->save();

       $msg="Successfully Added !";
       return $msg;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }

    public function find(Request $request)
    {

      
    	$items=Variation::where('id',$request->id)->first();
       
       //$msg="Successfully Added !";
       return $items;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }


    public function update(Request $request)
    {
       	 
        $id=(int)$request->id;
        DB::table('variations')
        ->where('id',$id)
        ->update(['name' => $request->name,
                    
                             ]);  

        $msg=$id.$request->name;
       return $msg;                    


    }   


    public function delete(Request $request)
    {
       	 
        $id=(int)$request->id;
        DB::table('variations')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;                    


    }  




}    
