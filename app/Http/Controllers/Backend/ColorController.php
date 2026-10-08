<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Tmporder;
use App\Models\Weightshipping;
use App\Models\Color;

use DB;

class ColorController extends Controller
{
    public function color(Request $request)
    {
    		
    		$items_array=Color::paginate(10);
    		return view('backend.product.colors',compact('items_array'));
    }


    public function add(Request $request)
    {

      

       $item=new Color;
       $colorname=str_replace(' ', '-', $request->name);
       $item->name=$colorname;
       $item->code=$request->code;
       $item->save();

       $msg="Successfully Added !";
       return $msg;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }

    public function find(Request $request)
    {

      
    	$items=Color::where('id',$request->id)->first();
       
       //$msg="Successfully Added !";
       return $items;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }


    public function update(Request $request)
    {
       	 
        $id=(int)$request->id;
        $colorname=str_replace(' ', '-', $request->name);
        DB::table('colors')
        ->where('id',$id)
        ->update(['name' => $colorname,
                    'code' => $request->code,
                             ]);  

        $msg=$id.$request->name.$request->code;
       return $msg;                    


    }   


    public function delete(Request $request)
    {
       	 
        $id=(int)$request->id;
        DB::table('colors')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;                    


    }  




}    
