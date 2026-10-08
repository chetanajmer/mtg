<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\B2enquiry;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Input;

class B2BenquiryController extends Controller
{

  public function list()
  {
    $items_array=B2enquiry::paginate(10);
    return view('backend.b2benquiry.b2benquiry',compact('items_array')); 
  }
     
}
