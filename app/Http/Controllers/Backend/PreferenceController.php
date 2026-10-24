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

       if($request->hasFile('image')){
           $image = $request->file('image');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('uploads/preferences'), $filename);
           $item->image = $filename;
       }

       $item->save();

       $msg="Successfully Added !";
       return $msg;

    }

    public function find(Request $request)
    {

    	$items=Preference::where('id',$request->id)->first();

       return response()->json($items);

    }


    public function update(Request $request)
    {

        $id=(int)$request->id;

        $data = ['name' => $request->name];

        if($request->hasFile('image')){
           $image = $request->file('image');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('uploads/preferences'), $filename);
           $data['image'] = $filename;
        }

        DB::table('preferences')
        ->where('id',$id)
        ->update($data);

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
