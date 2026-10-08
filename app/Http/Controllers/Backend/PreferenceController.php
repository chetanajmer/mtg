<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preference;

use DB;

class PreferenceController extends Controller
{
    public function index(Request $request)
    {

    		$items_array=Preference::paginate(10);
    		return view('backend.product.preferences',compact('items_array'));
    }


    public function add(Request $request)
    {

       $item=new Preference;
       $item->name=$request->name;
       $item->save();

       $msg="Successfully Added !";
       return $msg;

    }

    public function find(Request $request)
    {

    	$items=Preference::where('id',$request->id)->first();

       return $items;

    }


    public function update(Request $request)
    {

        $id=(int)$request->id;
        DB::table('preferences')
        ->where('id',$id)
        ->update(['name' => $request->name]);

        $msg="Updated !";
       return $msg;

    }


    public function delete(Request $request)
    {

        $id=(int)$request->id;
        DB::table('preferences')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;

    }




}
