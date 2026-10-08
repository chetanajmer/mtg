<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Occasion;

use DB;

class OccasionController extends Controller
{
    public function index(Request $request)
    {

    		$items_array=Occasion::paginate(10);
    		return view('backend.product.occasions',compact('items_array'));
    }


    public function add(Request $request)
    {

       $item=new Occasion;
       $item->name=$request->name;
       $item->save();

       $msg="Successfully Added !";
       return $msg;

    }

    public function find(Request $request)
    {

    	$items=Occasion::where('id',$request->id)->first();

       return $items;

    }


    public function update(Request $request)
    {

        $id=(int)$request->id;
        DB::table('occasions')
        ->where('id',$id)
        ->update(['name' => $request->name]);

        $msg="Updated !";
       return $msg;

    }


    public function delete(Request $request)
    {

        $id=(int)$request->id;
        DB::table('occasions')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;

    }




}
