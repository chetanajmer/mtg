<?php



namespace App\Http\Controllers\Backend;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;

use App\Models\Category;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Cartorder;

use Illuminate\Support\Facades\Redirect;

use Image;

use Illuminate\Support\Str;



class ReportController extends Controller
{
  public function product_report(Request $request)
  {
    $product=$request->get('product');
    $date_from=$request->get('from_date');
    $date_to=$request->get('to_date');
    if(!empty($product))
    {
      if((!empty($date_from)) && (empty($date_to)) )
      {
        $orders=DB::table('cartorders')->select('*')->where('modelno',$product)->whereDate('updated_at',$date_from)->orderByDesc('id')->Paginate(10);
      }
      elseif((empty($date_from)) && (!empty($date_to)) )
      {
      $orders=DB::table('cartorders')->select('*')->where('modelno',$product)->whereDate('updated_at',$date_to)->orderByDesc('id')->Paginate(10);
      }
      elseif((!empty($date_from)) && (!empty($date_to)) )
      {
      $orders=DB::table('cartorders')->select('*')->where('modelno',$product)
      ->whereBetween('updated_at',[$date_from,$date_to])->orderByDesc('id')->Paginate(10);
      }
      else
      {
      $orders=DB::table('cartorders')->select('*')->where('modelno',$product)->orderByDesc('id')->Paginate(10);
      }
    }
    else
    {
      $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->Paginate(10); 
    }
     $products=DB::table('products')->select('*')->get();
    return view('backend.report.product_report',compact('orders','products'));
}



    



	public function user_report(Request $request)

    {



      $user=$request->get('user');

      $date_from=$request->get('from_date');

      $date_to=$request->get('to_date');



      if(!empty($user))

      {

         if((!empty($date_from)) && (empty($date_to)) )

         {

             $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->where('userid',$user)->whereDate('updated_at',$date_from)->Paginate(10);

         }

         elseif((empty($date_from)) && (!empty($date_to)) )

         {

             $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->where('userid',$user)->whereDate('updated_at',$date_to)->Paginate(10);

         }

         elseif((!empty($date_from)) && (!empty($date_to)) )

         {

             $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->where('userid',$user)

             ->whereBetween('updated_at',[$date_from,$date_to])->Paginate(10);

         }

         else

         {

             $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->where('userid',$user)->Paginate(10);

         }

      }

      else

      {

         $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->Paginate(10); 

      }



     

      $users=DB::table('storeusers')->select('*')->orderByDesc('id')->get();

      return view('backend.report.user_report',compact('users','orders'));

        

    }



    public function date_report(Request $request)
    {

      $date_from=$request->from_date;

      $date_to=$request->to_date;


      if((!empty($date_from)) && (empty($date_to)) )
      {
          // echo $date_from;
        $orders=DB::table('cartorders')->select('*')->whereDate('updated_at',$date_from)->orderByDesc('id')->Paginate(10); 
      }

      elseif((empty($date_from)) && (!empty($date_to)) )
      {
        $orders=DB::table('cartorders')->select('*')->whereDate('updated_at',$date_to)->orderByDesc('id')->Paginate(10);
      }

     elseif((!empty($date_from)) && (!empty($date_to)) )

      {

        $orders=DB::table('cartorders')->select('*')->orderByDesc('id')

        ->whereBetween('updated_at',[$date_from,$date_to])

        ->Paginate(10);

      }

      else

      {

         $orders=DB::table('cartorders')->select('*')->orderByDesc('id')->Paginate(10); 

      }



     

      $users=DB::table('storeusers')->select('*')->orderByDesc('id')->get();

      return view('backend.report.date_report',compact('users','orders'));

        

    }



}

