<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
   public function callback(Request $request)
   {
   		// Only trust callbacks that carry a valid PayTabs signature
   		if(!paytabs_valid_callback($request->getContent(), $request->header('signature')))
   		{
   			return response('Invalid signature', 403);
   		}

   		$response=json_encode($request->payment_result);
   		$orderid=$request->cart_id;
   		$paytab_tran_ref=$request->tran_ref;
   		$response_status=$request->payment_result['response_status'] ?? null;
   		$items_array=Tmporder::where('orderid',$orderid)->get();
   		if(count($items_array)>0)
   		{
   			$callback=new Paytab_callback;
			  $callback->response=$response;
			  $callback->orderid=$orderid;
			  $callback->save();
   		}
   		if($response_status==='A')
   		{
   			DB::table('tmporders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Completed' ]);
   			DB::table('cartorders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Completed' ]);
   		}
   		else
   		{
   			DB::table('tmporders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Failed' ]);
   			DB::table('cartorders')->where('orderid',$orderid)->update(['paytab_tran_ref' => $paytab_tran_ref,'paytab_status' => 'Failed' ]);
   		}

   		return response('OK', 200);
   }
}