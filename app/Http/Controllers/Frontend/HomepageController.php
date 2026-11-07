<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Categorybanner;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\HomeLogo;
use App\Models\Brand;
use App\Models\Tmporder;
use App\Models\Cartorder;
use App\Models\Storeuser;
use App\Models\Shipping;
use App\Models\Orderid;;
use App\Models\Product_setting;
use App\Models\Weightshipping;
use App\Models\Contact;
use App\Models\About;
use App\Models\Wishlist;
use App\Models\Color;
use App\Models\Subscribe;
use App\Models\Footerlevel1;
use App\Models\Corporatepage;
use App\Models\Midocean_product;
use App\Models\Midocean_counter;
//use App\Models\Header_setting;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use DB;
use Mail;
use Carbon\carbon;
use App\Models\Old_portfolio;
use App\Models\Portfolio;
use App\Models\Portfolio_categories;

class HomepageController extends Controller
{
	public function index()
	{
		$subcategories=Subcategory::where('is_active','online')->whereIn('slug',collect(config('home_page.category_cards'))->pluck('subcategories')->flatten()->filter()->all())->get()->keyBy('slug');

		$categoryCards=[];
		foreach(config('home_page.category_cards') as $card)
		{
			$links=[];
			foreach($card['subcategories'] ?? [] as $slug)
			{
				if(isset($subcategories[$slug]))
				{
					$links[]=['label'=>$subcategories[$slug]->catname,'url'=>url('subcategory/'.$slug)];
				}
			}
			$categoryCards[]=['title'=>$card['title'],'image'=>$card['image'],'links'=>$links,'url'=>url('category/'.$card['category'])];
		}

		$newArrivals=$this->newArrivals()->take(config('home_page.new_arrivals_count'));

		$homeLogos=HomeLogo::where('is_active',true)->ordered()->get()->groupBy('type');

		return view('web_frontend.home',compact('categoryCards','newArrivals','homeLogos'));
	}

	/**
	 * Products marked New Arrival first, then the latest, leaving out any without a picture.
	 */
	private function newArrivals()
	{
		return Product::where('is_active','online')->orderByRaw("newarrival='online' desc")->orderByDesc('id')->take(60)->get()
			->filter(function($product) { return $product->imageUrl() && $product->priceForQuantity($product->minOrderQuantity())>0; })->values();
	}

	public function search(Request $request)
	{
		$query=trim(preg_replace('/\s+/u',' ',mb_substr((string)$request->query('q',''),0,100)));
		$newOnly=$request->boolean('new') && $query==='';

		$products=Product::where('is_active','online');
		if($newOnly)
		{
			$products->orderByRaw("newarrival='online' desc");
		}
		else
		{
			// Every word must appear somewhere
			$words=array_filter(explode(' ',$query));
			if(!$words)
			{
				$products->whereRaw('1=0');
			}
			foreach($words as $word)
			{
				$like='%'.addcslashes($word,'%_\\').'%';
				$products->where(function($q) use ($like) {
					foreach(['name','modelno','ai_tags','occasion_tags','preferences','shortdescription'] as $column)
					{
						$q->orWhere($column,'like',$like);
					}
				});
			}
		}

		$products=$products->orderByDesc('id')->paginate(20)->withQueryString();
		$heading=$newOnly ? 'New Arrivals' : ($query==='' ? 'Search our gifts' : 'Results for "'.$query.'"');

		return view('web_frontend.search-results',compact('products','query','heading'));
	}

	public function product($slug)
	{
		$items=Product::where('slug',$slug)->where('is_active',"online")->first();
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.product',compact('items','brands'));
	}

	public function webProduct($slug)
	{
		$product=Product::where('slug',$slug)->where('is_active',"online")->first();
		if(!$product) {
			abort(404);
		}
		// Tier rows are kept when the toggle is off, so only use them when it is on.
		// A tier that ends below the MOQ can never be ordered, so it isn't shown.
		$moq=$product->minOrderQuantity();
		$tier_prices=$product->tier_pricing_enabled==='yes'
			? \DB::table('product_tier_prices')->where('product_id',$product->id)->where('price','>',0)->orderBy('min_qty')->orderBy('id')->get()
				->filter(function($tier) use ($moq) { return !$tier->max_qty || $tier->max_qty>=$moq; })->values()
			: collect();
		return view('web_frontend.product',compact('product','tier_prices'));
	}

	public function compareProducts()
	{
		return view('web_frontend.compare-products');
	}

	 public function myaccount()
	{
		$footerlevel1=Footerlevel1::findorFail(1);	
		$contact=Contact::where('id','1')->first();
		return view('frontend.myaccount',compact('footerlevel1','contact'));
	}


	public function addtocart(Request $request)
	{


	  // echo"<pre>";print_r($_POST);die;     
	 
		$tmporder=new Tmporder;
		$slug=$request->slug;
		$modelno=(string)$request->modelno;
		$qty=(int)$request->qty;
		if(!empty($qty))
		{
			$quantity=$qty;
		}
		else
		{
			$quantity=1;
		}
		//echo $quantity;die;
		$sessionid=session()->get('sessionid');
		
		$productdata=Product::where('modelno',$modelno)->where('is_active',"online")->first();  
		$product_quantity=$productdata->quantity;

	   
		$product=Tmporder::where('modelno',$modelno)->where('sessionid',$sessionid)->first();
			

		if(!empty($product))
		{
			
			// $request->session()->flash('error',"Product Already in Cart !");    
				return $msg="Product Already in Enquiry List ! ";
		}

		/*else if($quantity>$product_quantity)
		{
			$message="Available quantity is " .$product_quantity;
			return $message;
		
		}*/
		else
		{   
			
			if(!isset($sessionid))
			{
				$sessionvalue=Str::random(15);
				session()->put('sessionid',$sessionvalue);
			}
		   
			$tmporder->modelno=$request->modelno;
			$tmporder->name=$request->product_name;
			$tmporder->specification=$request->specification;
			$tmporder->slug=$request->slug;
			$tmporder->thumbnail=$request->thumbnail;
			$tmporder->weight=$request->weight;
			$tmporder->price=$request->price;
			$tmporder->cprice=$request->cprice;
			$tmporder->quantity=(int)$request->quantity;
			$tmporder->sessionid=session()->get('sessionid');
			$tmporder->save();
			// $message="Product Added to the Cart !";
			
		}
		// $request->session()->flash('success','Product Added Successfully !');    
				return $msg="Product Added to Enquiry List ! ";
	 
	}

	public function incrementquantity(Request $request)
	{

		//print_r($_POST);die;
		$id=$request->id;
		$quantity=$request->quantity;
		$quantity++;
		$sessionid=session()->get('sessionid');

		// $productdata=Product::where('modelno',$modelno)->where('is_active',"online")->first();	
		
		$temporder=DB::table('tmporders')
			->where('id',$id)
			->where('sessionid',$sessionid)
			->update(['quantity' => $quantity]);
	
		if($temporder)
		{
			return $msg="Quantity Updated Successfully !";
		}
		else
		{
			return $msg="Quantity Not Updated Successfully !";
		}
			
	
	}	

	public function decrementquantity(Request $request)
	{
		// print_r($_POST);die;
		$id=$request->id;
		$quantity=$request->quantity;

		$quantity--;
		$sessionid=session()->get('sessionid');

		// $productdata=Product::where('modelno',$modelno)->where('is_active',"online")->first();	
		
		// echo $quantity;die;

		if($quantity==0)
		{
			return $msg="Quantity Cannot be 0 !";
		}
		else
		{
			$temporder=DB::table('tmporders')
			->where('id',$id)
			->where('sessionid',$sessionid)
			->update(['quantity' => $quantity]);
		}
		
		if($temporder)
		{
			return $msg="Quantity Updated Successfully !";
		}
		else
		{
			return $msg="Quantity Not Updated Successfully !";
		}   	
	}	

	public function deleteproduct(Request $request)
	{
		$id=$request->product_id;
		Tmporder::where('id',$id)->delete();
		// session()->flash('success', 'Product Deleted successfully');
		return $msg="Product Deleted successfully";
	}

	public function clearcart(Request $request)
	{
		Tmporder::truncate();
		// session()->flash('success', 'Product Deleted successfully');
		return $msg="Enquiry List is Empty !";
	}

	// public function docheckout(Request $request)
	// {
	// 	$sessionid=session()->get('sessionid');
		

	// 	if(!empty($sessionid))
	// 	{    
	// 		$orderid_data=Orderid::orderByDesc('id')->first();
	// 		if(!empty($orderid_data))
	// 		{
	// 			$orderid=$orderid_data->orderid;
	// 			$orderid=$orderid+1;
	// 			$orderinsert=new Orderid;
	// 			$orderinsert->orderid=$orderid;
	// 			$orderinsert->save();
				
	// 		} 
	// 		else
	// 		{
	// 			$orderid="10001";
	// 			$orderinsert=new Orderid;
	// 			$orderinsert->orderid=$orderid;
	// 			$orderinsert->save();
	// 		}   
		
	// 		$orderdate=date("Y-m-d");
	// 		DB::table('tmporders')
	// 		->where('sessionid',$sessionid)
	// 		->update([

	// 		'customer_name' => $request->name,
	// 		'customer_company' =>$request->company_name,
	// 		'customer_email' => $request->email,
	// 		'customer_phone' => $request->phone,
	// 		'additional_info'=>$request->info,
	// 		'orderid' =>  $orderid,
	// 		'userid' =>  session()->get('userid'),
	// 		'ordercomplete' =>"Yes",
	// 		'orderdate'=>$orderdate,
	// 		]);

			
			 
			
	// 		$pushdata=Tmporder::where('orderid',$orderid)->get();

	// 		if(!empty($pushdata))
	// 		{
	// 				Tmporder::query()
	// 			   ->where('orderid',$orderid)
	// 			   ->each(function ($oldPost) {
	// 				$newPost = $oldPost->replicate();
	// 				$newPost->setTable('cartorders');
	// 				$newPost->save(); });
			   
	// 		}   

	// 		$headersettings= Header_setting::where('id','1')->first();

	// 		$admin_email=$headersettings->headeremail;

	// 		$orderdetails=Tmporder::query()
	// 		->where('orderid',$orderid)->first();

	// 		$detail = [
	// 		'orderid'=>$orderid,
	// 		'orderdate'=>$orderdate,
	// 		'customer_name' => $request->name,
	// 		'customer_company' =>$request->company_name,
	// 		'customer_email' => $request->email,
	// 		'customer_phone' => $request->phone,
	// 		'additional_info'=>$request->information,
	// 		 ];

	// 		 $user['to']=$request->email;
	// 		 // $user['from']="";
	// 		 $user['from']="richa@silverpixelz.com";
	// 		 Mail::send('mail/orderemail',  $detail, function($message) use ($user) {
	// 			$message->to($user['to'], 'SevenWonders')->subject
	// 			('Order Information');
	// 			$message->from($user['from'],'SevenWonders');
	// 		});

	// 		Mail::send('mail/adminorderemail',  $detail, function($message) use ($user) {
	// 			$message->to($user['from'], 'SevenWonders')->subject
	// 			('Order Information');
	// 			$message->from($user['from'],'SevenWonders');
	// 		});

	// 		// Mail::to($customer_email)->send(new \App\Mail\Orderemail($details)); 
	// 		// Mail::to($admin_email)->send(new \App\Mail\Adminorderemail($details)); 
			
	// 	   $footerlevel1=Footerlevel1::findorFail(1);   
	// 	   $contact=Contact::where('id','1')->first();
	// 		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
	// 		return view('frontend.thankyou',compact('orderid','footerlevel1','contact','brands'));

	// 	}    

	// 	 return Redirect::to('/');
	// }


	public function orders(Request $request)
	{
		// Check if user is authenticated
		$userid = session()->get('userid');

		if ($userid) {
			// Set fullname in session if not already set
			if (!session()->has('fullname')) {
				$user = Storeuser::where('id', $userid)->first();
				if ($user) {
					session()->put('fullname', $user->fname . ' ' . $user->lname);
				}
			}

			// One row per order: placed by this customer or sent to their email
			$email = optional(Storeuser::find($userid))->email;
			$orders = \App\Models\Order::with('items')
				->where(function ($q) use ($userid, $email) {
					$q->where('storeuser_id', $userid);
					if ($email) {
						$q->orWhere('customer_email', $email);
					}
				})->orderByDesc('created_at')->orderByDesc('id')->get();
			return view('web_frontend.orders', compact('orders'));
		}

		// The admin order list lives at /admin/orders
		return redirect()->route('corporate.login')->with('info', 'Please login to view your orders');
	}

	public function printinvoice($id)
	{
		$orders=Cartorder::where('orderid',$id)->orderBy('id')->get();
		if($orders->isEmpty()) {
			abort(404);
		}
		$items=$orders->first();
		return view('backend.orders.invoice',compact('items','orders'));
	}

	public function deleteorder($id)
	{
		$items=Cartorder::where('orderid',$id)->delete();
		session()->flash('success','Deleted Successfully !'); 
		return Redirect::back();
	}

	public function orderdetails($id)

	{
		$orders=Cartorder::where('orderid',$id)->orderBy('id')->get();
		if($orders->isEmpty()) {
			abort(404);
		}
		return view('backend.orders.orderdetail',compact('orders'));

	}

	public function cart_order(Request $request)
	{
		$items_array=Cartorder::orderbyDesc('id')->get()->unique('orderid');
		return view('backend.orders.list',compact('items_array'));
	}

	public function update_orderstatus(Request $request)
	{

	  $orderid=$request->orderid;
	  $orderstatus=$request->orderstatus;
	  $current_date=Carbon::now();
	  $date=$current_date->toDateString();

	   if($orderstatus=='Confirmed')
	   {
		  DB::table('cartorders')
			->where('orderid',$orderid)
			->update(['orderstatus' => $request->orderstatus,'ordercomplete' => "",'orderconfirmed_date'=>$date]);
	   }

	   if($orderstatus=='In Transit')
	   {
		  DB::table('cartorders')
			->where('orderid',$orderid)
			->update(['orderstatus' => $request->orderstatus,'ordercomplete' => "",'orderintransit_date'=>$date]);
	   }

	   if($orderstatus=='Delivered')
	   {
		  DB::table('cartorders')
			->where('orderid',$orderid)
			->update(['orderstatus' => $request->orderstatus,'ordercomplete' => "",'orderdelivery_date'=>$date]);
	   }

	   if($orderstatus=='No')
	   {
		   DB::table('cartorders')
			->where('orderid',$orderid)
			->update(['ordercomplete' => $request->orderstatus,'ordercancel_date'=>$date]);
	   }
	  
		session()->flash('success','Order Status Updated Successfully !'); 
		return Redirect::back();
		  


	}

	/** About Us page (new storefront design): live categories and the real order steps. */
	public function aboutus(Request $request)
	{
		$categories=Category::where('is_active','online')->orderBy('ranking')->orderBy('id')->get();
		$steps=config('order_steps');
		$content=About::pageContent(); // wording edited in the admin About editor
		return view('web_frontend.about',compact('categories','steps','content'));
	}

	public function contactus(Request $request)
	{
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		$items=Contact::where('id','1')->first();
		return view('frontend.contactus',compact('items','brands'));
	}


	public function variant_price(Request $request)
	{
	   
		// print_r($_POST);die;
		$product = Product::where('slug',$request->slug)->first();
		// echo "<pre>"; print_r($product);die; 
		return array('product'=>$product);
	}


	public function addsubscriber(Request $request)
	{

		$userid=session()->get('userid');

		//echo $userid."-".$id;die;
		$check=Subscribe::where('email',$request->subscribername)->first();
		
		if(!empty($check))
		{
			return "Already Subscribed !";
		}
		else
		{
				$subscribe=new Subscribe;
				$subscribe->email=$request->subscribername;
				$subscribe->save();
				return "Subscribed Successfully !";
		}    



	}

	public function privacy(Request $request)
	{
		$items=Corporatepage::where('id','1')->first();
		return view('frontend.privacypolicy',compact('items'));
	}

	public function return(Request $request)
	{
		$items=Corporatepage::where('id','1')->first();
		return view('frontend.returnpolicy',compact('items'));
	}


	public function shippment(Request $request)
	{
		$items=Corporatepage::where('id','1')->first();
		return view('frontend.shippingpolicy',compact('items'));
	}


	public function variationproduct($product_slug,$slug)
	{   

		$items=Product::where('slug',$product_slug)->get(['variations']);
		echo "<pre>"; print_r($items);
		$prod=Product::whereJsonContains('variations',"STAdapterforPowerMeter")->get();
		print_r($prod);die;
		return view('frontend.shippingpolicy',compact('items'));
	}



	public function category_filter(Request $request)
	{
	  $category_id=$request->get('categories');
	 
	  $subcatgory_id=$request->get('subcategories');

	  $brand_slug=$request->get('brand_slug');
	  // Called by the old-theme filter (AJAX); ignore requests without a brand
	  if(empty($brand_slug) || !Brand::where('slug',$brand_slug)->exists())
	  {
		return response('',204);
	  }
	  
	  if(!empty($category_id))
	  {
		$products= DB::table('products')->whereIn('category',$category_id)->Paginate(12)->onEachSide(0);
	  }

	  else
	  {
		 $branddata=Brand::where('slug',$brand_slug)->first();
		 $brandid=$branddata->id;
		 $products=DB::table('products')->where('is_active','online')->where('brand',$brandid)->Paginate(12)->onEachSide(0);
	  }
	  //return $products;
	  return view('frontend.partials.filteredproducts',compact('products'));
	}

	public function sub_category_filter(Request $request)
	{
	 
	  $subcategory_id=$request->get('subcategories');

	  $cat_slug=$request->get('cat_slug');
	  // Called by the old-theme filter (AJAX); ignore requests without a category
	  if(empty($cat_slug) || !Category::where('slug',$cat_slug)->exists())
	  {
		return response('',204);
	  }
	  

	  if(!empty($subcategory_id))
	  {
		$products= DB::table('products')->whereIn('subcategory',$subcategory_id)->Paginate(12)->onEachSide(0); 
	  }
	  else
	  {
		  $categorydata=Category::where('slug',$cat_slug)->first();
		  $brand=$categorydata->brand_id;
		  $products=DB::table('products')->where('is_active','online')->where('brand',$brand)->Paginate(12)->onEachSide(0);
		 
	  }
	  
	  return view('frontend.partials.filteredproducts',compact('products'));
	  //return $products;
	 
	}

   /**
    * Contact Us form: emails MTG staff (Reply-To the customer), then a short
    * confirmation to the customer that does not repeat their message, so the
    * form cannot be used to send arbitrary text to any address.
    */
   public function contactform(Request $request)
	{
		$data = $request->validate([
			'contact-name' => 'required|string|max:100',
			'contact-email' => 'required|email|max:150',
			'contact-phone' => 'nullable|string|max:30',
			'message' => 'required|string|max:5000',
		]);
		$contact = [
			'name' => trim($data['contact-name']),
			'email' => trim($data['contact-email']),
			'phone' => trim((string) ($data['contact-phone'] ?? '')),
			'msg' => trim($data['message']),
		];

		$staff = staff_email();
		try {
			if (empty($staff)) {
				throw new \RuntimeException('no staff email set (QUOTE_REQUEST_EMAIL or Header Settings email)');
			}
			Mail::send('mail.contact_staff', $contact, function ($message) use ($staff, $contact) {
				$message->to($staff)->replyTo($contact['email'], $contact['name'])
					->subject('Website contact message from ' . $contact['name']);
			});
		} catch (\Throwable $e) {
			\Log::error('Contact form email to staff failed: ' . $e->getMessage());
			return Redirect::back()->withInput()->with('error', 'Sorry, we could not send your message just now. Please email us at info@morethangiftz.com or call +971 55 786 3450.');
		}

		try {
			Mail::send('mail.contact_customer', ['name' => $contact['name']], function ($message) use ($contact) {
				$message->to($contact['email'], $contact['name'])->subject('We received your message - More Than Giftz');
			});
		} catch (\Throwable $e) {
			\Log::error('Contact form confirmation to ' . $contact['email'] . ' failed: ' . $e->getMessage());
		}

		$request->session()->flash('success', 'Thank you, your message has been sent. We will get back to you soon.');
		return Redirect::back();
   }

   public function brands()
	{
		$all_brands= DB::table('brands')->where('is_active','online')->Paginate(12)->onEachSide(0);
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.brands',compact('brands','all_brands'));
	}

	public function fileerror(Request $request)
	{
		 $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('errors.404',compact('brands'));
	}

	public function servererror(Request $request)
	{
		 $brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('errors.500',compact('brands'));
	}
	
	public function slug($string)
	{
		$string = str_replace(' ', '-', $string);
		$string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 
		return preg_replace('/-+/', '-', $string);    
	}

	public function portfolio()
	{
		$old_portfolio=Old_portfolio::get();
		foreach($old_portfolio as $old_portfolio)
		{
			 $rand_num=rand(100,1000);
			 $portfolio=new Portfolio;
			 $portfolio->category=$old_portfolio->category;
			 $portfolio->name=$old_portfolio->name;
			 $portfolio->logo=$old_portfolio->logo;
			 $portfolio->images=$old_portfolio->images;
			 $portfolio->is_active='online';
			 $portfolio->slug=$this->slug($old_portfolio->name)."-".$rand_num;;
			 $portfolio->description=$old_portfolio->description;
			 $portfolio->launch_date=$old_portfolio->tagline;
			 $portfolio->save();
		}	
	}
		

		

	
}