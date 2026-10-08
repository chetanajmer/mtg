<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Tmporder;
use App\Models\Weightshipping;

use DB;

class ShippingController extends Controller
{
    public function shipping(Request $request)
    {
    	$items_array=Shipping::all();
    	return view('backend.shipping.list',compact('items_array'));
    }


    public function add(Request $request)
    {
      $item=new Shipping;
      $item->cityname=$request->cityname;
      $item->shippingcost=$request->shippingcost;
      $item->save();
      $msg="Successfully Added !";
      return $msg;
      //$request->session()->flash('success','Saved Successfully !');
       //return Redirect::back();
    }

    public function find(Request $request)
    {

      
    	$items=Shipping::where('id',$request->id)->first();
       
       //$msg="Successfully Added !";
       return $items;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }


    public function update(Request $request)
    {
       	 
        $id=(int)$request->id;
        DB::table('shippings')
        ->where('id',$id)
        ->update(['shippingcost' => $request->shippingcost,
                    'cityname' => $request->cityname,
                             ]);  

        $msg=$id.$request->shippingcost.$request->cityname;
       return $msg;                    


    }   


    public function delete(Request $request)
    {
       	 
        $id=(int)$request->id;
        DB::table('shippings')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;                    


    }  


    public function storeshipping(Request $request)
    {

       $sessionid=session()->get('sessionid');
      $items=Shipping::where('id',(int)$request->id)->first();
     

      $shippingcost=$items->shippingcost;

       DB::table('tmporders')
        ->where('sessionid',$sessionid)
        ->update(['shippingprice' => $shippingcost,
                  ]);  


      return $items;

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }



    /*Weight based */

    public function weightshipping(Request $request)
    {
        
        $items_array=DB::table('weightshippings')->select('*')->paginate(10);
        return view('backend.shipping.weightshipping',compact('items_array'));
    }


    public function weightadd(Request $request)
    {
      $item=new Weightshipping;
      $item->area=$request->area;
      $item->weightfrom=$request->weightfrom;
      $item->weightto=$request->weightto;
      $item->price=$request->price;
      $item->additional_charges=$request->additional_charges;
      $item->save();

      $msg="Successfully Added !";
      return $msg;
      //$request->session()->flash('success','Saved Successfully !');
      //return Redirect::back();
    }


    public function weightfind(Request $request)
    {

      
      $items=Weightshipping::where('id',$request->id)->first();
       
    
      return $items;

    }


    public function weightupdate(Request $request)
    {
         
        $id=(int)$request->id;
        DB::table('weightshippings')
        ->where('id',$id)
        ->update(['area' => $request->area,
                    'weightfrom' => $request->weightfrom,
                    'weightto' => $request->weightto,
                    'price' => $request->price,
                    'additional_charges' => $request->additional_charges
                             ]);  

        $msg=$id.$request->shippingcost.$request->cityname;
       return $msg;                    


    }   


    public function weightdelete(Request $request)
    {
         
        $id=(int)$request->id;
        DB::table('weightshippings')->where('id', $id)->delete();

        $msg="Deleted !";
       return $msg;                    


    }  


    public function storeweightshipping(Request $request)
    {

      $sessionid=session()->get('sessionid');

      $tmporders=Tmporder::where('sessionid',$sessionid)->get();
      $totalweight=0;

      foreach ($tmporders as $orders) 
      {
        $totalweight+=$orders->quantity*$orders->weight;
      }

      $items1=Weightshipping::where('area',$request->area1)
                        ->where('weightfrom', '<=',$totalweight)
                        ->where('weightto', '>=', $totalweight)
                        ->first();

      if(!empty($items1))
      {
        $shippingcost=$items1->price;  
        DB::table('tmporders')
        ->where('sessionid',$sessionid)
        ->update(['shippingprice' => $shippingcost,
                  ]);

        return $items1;

      }      
      else
      {
        $msg=$request->area1;
        return $msg;
      }  
      


     

        //$request->session()->flash('success','Saved Successfully !');
        //return Redirect::back();

    }

}    
