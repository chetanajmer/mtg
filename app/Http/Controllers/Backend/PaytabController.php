<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paytab;
use App\Models\Paytab_callback;
use App\Models\Cartorder;
use App\Models\Tmporder;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use DB;
use Mail;

class PaytabController extends Controller
{
   public function paytabs()
   {
   	  $url = "https://secure.paytabs.com/payment/request";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('authorization: S9JNL9KMLB-HZMB2JN6KW-2K9TK9NZ6T', 'Content-Type: application/json'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$data = <<<DATA
			{
			  "profile_id": 48334,
			    "tran_type": "sale",
			    "tran_class": "ecom" ,
			    "cart_id":"9999999",
			    "cart_description": "Dummy Order 35925502061445345",
			    "cart_currency": "AED",
			    "cart_amount": 46.17,
			    "customer_details": {
			      "name": "John Smith",
			      "email": "jsmith@gmail.com",
			      "street1": "404, 11th st, void",
			      "city": "Dubai",
			      "country": "AE",
			      "ip": "94.204.129.89"
			    },
			    "shipping_details": {
			      "name": "John Smith",
			      "email": "jsmith@gmail.com",
			      "street1": "404, 11th st, void",
			      "city": "Dubai",
			      "country": "AE",
			      "ip": "94.204.129.89"
			    },
			    "callback": "https://beta.sevenwonder.ae/callback",
			    "return": "https://beta.sevenwonder.ae/return_url"
			}
			DATA;

			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

			$resp = curl_exec($curl);
			curl_close($curl);
			// echo $resp;

			$data=json_decode($resp, true);
			print_r($data);die;

			//$url=$data['redirect_url'];
			//header("Location:".$url);
   }

    public function return_url(Request $request)
   {
   	 print_r($_POST);

   	echo $request->respStatus;
   }
   
   public function callback(Request $request)
   {	
   		$response=json_encode($request->payment_result);
   		$orderid=$request->cart_id;
   		$paytab_tran_ref=$request->tran_ref;
   		$response_status=$request->payment_result['response_status'];
   		$items_array=Tmporder::where('orderid',$orderid)->get();
   		if(count($items_array)>0)
   		{
   			$callback=new Paytab_callback;
			  $callback->response=$response;
			  $callback->orderid=$orderid;
			  $callback->save();
   		}
   		if($response_status='A')
   		{
   			DB::table('tmporders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Completed' ]);
   			DB::table('cartorders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Completed' ]);
   		}
   		else
   		{
   			DB::table('tmporders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Failed' ]);
   			DB::table('cartorders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Completed' ]);
   		}
		  
   }
}