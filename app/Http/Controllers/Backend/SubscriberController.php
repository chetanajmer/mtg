<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscribe;
use DB;
use Illuminate\Support\Facades\Redirect;

class SubscriberController extends Controller
{
    public function subscribers(Request $return)
    {
        $items_array=Subscribe::all();
        return view('backend.subscribers.subscribers',compact('items_array'));
    }


    public function deletesubscribers($slug)
    {
        DB::table('subscribes')->where('id',$slug)->delete();
        session()->flash('success','Deleted Succesfully!');
        return Redirect::to('subscribers');
        //return redirect('admin.add-categories',compact('categories'));
    }
}



