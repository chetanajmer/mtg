<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tmporder;
use App\Models\Cartorder;
use App\Models\Storeuser;
use App\Models\Shipping;
use App\Models\Orderid;;
use App\Models\Product_setting;
use App\Models\Weightshipping;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Mail;
use Carbon\carbon;



class OrderController extends Controller
{

    public function index(Request $request)
    {
        // One row per order number; old orders without a number stay separate
        $orders=Cartorder::orderBy('created_at','desc')->orderBy('id','desc')->get();
        $itemCounts=$orders->whereNotNull('orderid')->countBy('orderid')->all();
        $grouped=$orders->unique(function ($order) {
          return $order->orderid ?: 'id-'.$order->id;
        })->values();

        $perPage=25;
        $page=\Illuminate\Pagination\Paginator::resolveCurrentPage();
        $items_array=new \Illuminate\Pagination\LengthAwarePaginator(
          $grouped->forPage($page,$perPage)->values(), $grouped->count(), $perPage, $page,
          ['path'=>$request->url(), 'query'=>$request->query()]
        );
        return view('backend.orders.list',compact('items_array','itemCounts'));
    }

    /**
     * Carts (current cart_items) with no activity for 24 hours: one row per
     * customer, or per guest browser session.
     */
    public function abandoned_order(Request $request)
    {
      $cutoff=now()->subDay();
      $carts=\App\Models\CartItem::orderBy('updated_at','desc')->get()
        ->groupBy(function ($item) {
          return $item->user_id ? 'u'.$item->user_id : 's'.$item->session_id;
        })
        ->filter(function ($items) use ($cutoff) {
          return $items->max('updated_at') < $cutoff;
        });

      $users=Storeuser::whereIn('id',$carts->flatten()->pluck('user_id')->filter()->unique())->get()->keyBy('id');
      $items_array=$carts->map(function ($items) use ($users) {
        $first=$items->first();
        $user=$first->user_id ? $users->get($first->user_id) : null;
        return (object) [
          'key'=>$first->id,
          'customer'=>$user ? trim($user->fname.' '.$user->lname) : 'Guest',
          'email'=>$user->email ?? null,
          'userid'=>$user->id ?? null,
          'products'=>$items->count(),
          'quantity'=>$items->sum('quantity'),
          'value'=>$items->sum(function ($i) { return $i->price*$i->quantity; }),
          'last_activity'=>$items->max('updated_at'),
        ];
      })->values();

      return view('backend.orders.abandoned_list',compact('items_array'));
    }

    /**
     * $id is any cart_items id from the abandoned cart.
     */
    public function abandoned_orderdetails($id)
    {
      $item=\App\Models\CartItem::findOrFail($id);
      $orders=\App\Models\CartItem::when($item->user_id, function ($q) use ($item) {
          return $q->where('user_id',$item->user_id);
        }, function ($q) use ($item) {
          return $q->whereNull('user_id')->where('session_id',$item->session_id);
        })->orderBy('id')->get();
      $customer=$item->user_id ? Storeuser::find($item->user_id) : null;

      return view('backend.orders.abandoned_orderdetails',compact('orders','customer'));
    }

    /**
     * Email the customer a reminder about their abandoned cart.
     * $request->id is any cart_items id from that cart.
     */
    public function send_abandoned_mail(Request $request)
    {
      $item=\App\Models\CartItem::find($request->id);
      $customer=$item && $item->user_id ? Storeuser::find($item->user_id) : null;
      if(!$customer || empty($customer->email))
      {
        return response()->json(['success'=>false,'message'=>'This cart has no customer email (guest cart).'],422);
      }

      $items=\App\Models\CartItem::where('user_id',$customer->id)->orderBy('id')->get();
      try
      {
        Mail::send('mail.abandoned_cart_reminder',['customer'=>$customer,'items'=>$items],function ($message) use ($customer) {
          $message->to($customer->email)->subject('You left items in your quote cart - More Than Giftz');
        });
      }
      catch(\Throwable $e)
      {
        \Log::error('Abandoned cart email to '.$customer->email.' failed: '.$e->getMessage());
        return response()->json(['success'=>false,'message'=>'Email could not be sent. Check the mail settings.'],500);
      }

      return response()->json(['success'=>true,'message'=>'Reminder sent to '.$customer->email]);
    }

}




