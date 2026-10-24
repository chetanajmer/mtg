<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExhibitionEvent;

use DB;

class ExhibitionEventController extends Controller
{
    public function index(Request $request)
    {

    		$items_array=ExhibitionEvent::paginate(10);
    		return view('backend.product.exhibition_events',compact('items_array'));
    }


    public function add(Request $request)
    {

       $item=new ExhibitionEvent;
       $item->name=$request->name;
       $item->start_date=$request->start_date;
       $item->end_date=$request->end_date;

       if($request->hasFile('image')){
           $image = $request->file('image');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('uploads/exhibition_events'), $filename);
           $item->image = $filename;
       }

       $item->save();

       $msg="Successfully Added !";
       return $msg;

    }

    public function find(Request $request)
    {

    	$items=ExhibitionEvent::where('id',$request->id)->first();

       return response()->json($items);

    }


    public function update(Request $request)
    {

        $id=(int)$request->id;

        $data = [
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        if($request->hasFile('image')){
           $image = $request->file('image');
           $filename = time() . '.' . $image->getClientOriginalExtension();
           $image->move(public_path('uploads/exhibition_events'), $filename);
           $data['image'] = $filename;
        }

        DB::table('exhibition_events')
        ->where('id',$id)
        ->update($data);

        $msg="Updated !";
       return $msg;

    }


    public function delete(Request $request)
    {

        $id=(int)$request->id;
        DB::table('exhibition_events')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;

    }



}
