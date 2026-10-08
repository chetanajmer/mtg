<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use DB;
use Illuminate\Support\Facades\Redirect;


class ReviewController extends Controller
{
  public function index(Request $request)
  {
    $items_array=Review::paginate(10);
    return view('backend.review.review',compact('items_array'));  
	}


 


    


    

    


   

    

    




    
	



	





    



    

    



    



}

