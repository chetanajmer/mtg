<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branding_option;

use DB;

class BrandingOptionController extends Controller
{
    public function index(Request $request)
    {

    		$items_array=Branding_option::paginate(10);
    		return view('backend.product.branding-options',compact('items_array'));
    }


    public function add(Request $request)
    {

       $item=new Branding_option;
       $item->name=$request->name;

       if($request->hasFile('image')){
           $image = $request->file('image');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('upload/branding_options'), $filename);
           $item->image = $filename;
       }

       $item->save();

       $msg="Successfully Added !";
       return $msg;

    }

    public function find(Request $request)
    {

    	$items=Branding_option::where('id',$request->id)->first();

       return $items;

    }


    public function update(Request $request)
    {

        $id=(int)$request->id;
        $updateData = ['name' => $request->name];

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/branding_options'), $filename);
            $updateData['image'] = $filename;
        }

        DB::table('branding_options')
        ->where('id',$id)
        ->update($updateData);

        $msg="Updated !";
       return $msg;

    }


    public function delete(Request $request)
    {

        $id=(int)$request->id;
        DB::table('branding_options')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;

    }




}
