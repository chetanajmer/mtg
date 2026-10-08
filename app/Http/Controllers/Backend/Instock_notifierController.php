<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\Instock_notifier;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Input;

class Instock_notifierController extends Controller
{
  public function list()
  {
    $items_array=Instock_notifier::paginate(10);
    return view('backend.instock_notifier.list-instock-notifier',compact('items_array')); 
  }   
}
