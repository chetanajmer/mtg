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
use App\Models\User_reward;
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
		$banners=Categorybanner::where('id','1')->first();
		$all_brands=Brand::where('is_active','online')->get();
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.index',compact('brands','banners','all_brands'));
	}

	public function product($slug)
	{
		$items=Product::where('slug',$slug)->where('is_active',"online")->first();	
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.product',compact('items','brands'));
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

	public function cart(Request $request)
	{

		$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.cart',compact('items_array','brands'));
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

	public function quickview($slug)
	{
		$item=Product::where('slug',$slug)->where('is_active',"online")->first();
		// echo "<pre>";
		// print_r($item);
		// exit();
		return view('frontend.quickview',compact('item'));
	}

	public function gridview($slug)
	{
		$item=Product::where('slug',$slug)->where('is_active',"online")->first();
		return view('frontend.categorygrid',compact('item'));
	}

	public function checkout(Request $request)
	{
		$userid=session()->get('userid');
		$guestid=session()->get('guestid');
		$userdata=Storeuser::where('id',$userid)->get();
		$footerlevel1=Footerlevel1::findorFail(1);	
		$contact=Contact::where('id','1')->first();
		if(!empty($userid))
		{
			$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
			$userdata=Storeuser::where('id',$userid)->first();
			$shipping=Shipping::all();
			$weightshipping=Weightshipping::get()->unique('area');
			return view('frontend.checkout',compact('items_array','userdata','shipping','weightshipping','footerlevel1','contact'));
		}
		elseif(!empty($guestid)) 
		{
			$items_array=Tmporder::where('sessionid',session()->get('sessionid'))->get();
			$shipping=Shipping::all();
			$weightshipping=Weightshipping::get()->unique('area');
			return view('frontend.guestcheckout',compact('items_array','shipping','weightshipping','footerlevel','contact'));
		}
		else
		{
			return Redirect::to('/store/login');
		}    

		
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


	public function guestcheckout(Request $request)
	{
		session()->put('guestid',"guest");
		return Redirect::to('/checkout');
	}

	public function orders(Request $request)
	{
		$items_array=Cartorder::orderBy('created_at','desc')->get()->unique('orderid');
		return view('backend.orders.list',compact('items_array'));
	}

	public function printinvoice($id)
	{
		$items=Cartorder::where('orderid',$id)->first();
		return view('backend.orders.invoice',compact('items'));
	}

	public function deleteorder($id)
	{
		$items=Cartorder::where('orderid',$id)->delete();
		session()->flash('success','Deleted Successfully !'); 
		return Redirect::back();
	}

	public function orderdetails($id)

	{
		$orders=Cartorder::where('orderid',$id)->get();
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

	public function aboutus(Request $request)
	{
		$items=About::where('id','1')->first();
		$brands=Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
		return view('frontend.aboutus',compact('items','brands'));
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


	public function addtowishlist(Request $request)
	{
		$userid=session()->get('userid');

		if(!empty($userid))
		{
			$checkproduct=Wishlist::where('productid',$request->id)->where('userid',$userid)->first();

			if(!empty($checkproduct))
			{
				$msg="Product already in Wishlist";
				$code="error"; 
				return array($msg, $code);
			}
			else
			{

				$getproductdata=Product::where('id',$request->id)->first();    

				if(!empty($getproductdata))
				{
					$wishlist=new Wishlist;
					$wishlist->productslug=$getproductdata->slug;
					$wishlist->productid=$getproductdata->id;
					$wishlist->image=$getproductdata->thumbnail;
					$wishlist->modelno=$getproductdata->modelno;
					$wishlist->name=$getproductdata->name;
					$wishlist->price=$getproductdata->price;
					$wishlist->userid=$userid;

					$wishlist->save();

					$msg="Product Added to Wishlist";
					$code="success"; 
					return array($msg, $code);
				}
				else
				{

					$msg="Product Not Exists";
					$code="error"; 
					return array($msg, $code);
				}    
				
			   
			}   
		}
		else
		{
			$msg="Login to Add product to wishlist";
			$code="error"; 
			return array($msg, $code);
		}    
		 
	 
	}

	public function wishlist(Request $request)
	{

		$userid=session()->get('userid');
		if(!empty($userid))
		{

			$items_array=Wishlist::where('userid',$userid)->get();   

		   // echo"<pre>";print_r($items_array);die;            
			return view('frontend.wishlist',compact('items_array'));
		}
		else
		{
			session()->flash('error','Please Login to view Wishlist'); 
			return Redirect::to('/store/login');
		}    

	}

	public function deletewishlist($id)
	{

		$userid=session()->get('userid');

		//echo $userid."-".$id;die;
		Wishlist::where('id',$id)->where('userid',$userid)->delete();
		session()->flash('success', 'Deleted successfully');
		return Redirect::back();

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

   public function contactform(Request $request)
	{
		$username=$request->get('contact-name');
		$phone=$request->get('contact-phone');
		$msg=$request->get('message');
		$email=$request->get('contact-email');
		 $data = array(
		  'username'=>$username,
		  'msg'=>$msg,
		  'email'=>$email,
		  'phone'=>$phone,
		);

		$user['to']=$request->get('contact-email');
		// $user['from']="";
		$user['from']="richa@silverpixelz.com";
		Mail::send('mail/usercontactmail', $data, function($message) use ($user) {
		
		 $message->to($user['to'], 'Meem Industrial')->subject
			('Contact Information');
		 $message->from($user['from'],'Meem Industrial');
		 });

		
		Mail::send('mail/admincontactmail', $data, function($message) use ($user) {
		
		 $message->to($user['from'], 'Meem Industrial')->subject
			('Contact Information');
		 $message->from($user['from'],'Meem Industrial');
		 });
	  
		  $request->session()->flash('success','Your Message has been sent, we will get back to you soon !');
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
	
	public function midocean(Request $request)
	{
		$curl = curl_init();
	  curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.midocean.com/gateway/products/2.0?language=en',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'x-Gateway-APIKey: 9c471204-9eb9-4d1e-99d8-b13db507e22d'
		  ),
		));
		
		$response = curl_exec($curl);
		curl_close($curl);

// 		echo "<pre>";echo $response;die;

		$data=json_decode($response);
		// echo count($data);die;
		$insert_data=[];

		Midocean_product::query()->truncate();
	
		foreach($data as $val)
		{
			/*save record */
			$midocean_product=new Midocean_product;
		
			if(!empty($val->product_name))
			{
				$name=$val->product_name;
				$createslug=$this->slug($val->product_name);
				$rand_num=rand(100,1000);
				$newslug=$createslug."-".$rand_num;
				$slug=$newslug;
			}
			if(empty($val->product_name))
			{
				if(!empty($val->short_description))
				{
					$name=$val->short_description;
					$createslug=$this->slug($val->short_description);
					$rand_num=rand(100,1000);
					$newslug=$createslug."-".$rand_num;
					$slug=$newslug;
				}
			}
			if(!empty($val->short_description))
			{
				$shortdescription=$val->short_description;
			}
			if(!empty($val->long_description))
			{
				$description=$val->long_description;
			}
			
			//variants
			if(!empty($val->variants))
		  {
		  	$color=array();
		  	$image=array();
				foreach($val->variants as $item)
				{
				  if(!empty($item->category_level3))
				  {
						$category_level3=$item->category_level3;
						if(($category_level3=='Desk lights & Accessories') || ($category_level3=='Weather stations'))
						{
							$subcategory='20';
							$category='14';
						}
						if($category_level3=='Clocks & Calculators') 
						{
							$subcategory='67';
							$category='14';
						}
						if(($category_level3=='Picnic & Camping')  || ($category_level3=='Inflatables') ||($category_level3=='Playing cards'))
						{
							$subcategory='97';
							$category='16';
						}
						if(($category_level3=='Basic')  || ($category_level3=='Lights') ||($category_level3=='Token'))
						{
							$subcategory='12';
							$category='19';
						}
						if(($category_level3=='Hard cover')  || ($category_level3=='Soft cover') ||($category_level3=='Spiral'))
						{
							$subcategory='13';
							$category='14';
						}
						if($category_level3=='Paper weight/Trophies') 
						{
							$subcategory='91';
							$category='3';
						}
						if(($category_level3=='Accessories')  || ($category_level3=='Hangers') ||($category_level3=='Packaging Accessories'))
						{
							$subcategory='100';
							$category='2';
						}
						if(($category_level3=='Mugs')  || ($category_level3=='Mugs & Tumblers') ||($category_level3=='Sets & Others'))
						{
							$subcategory='53';
							$category='4';
						}
						if( ($category_level3=='Candles')  || ($category_level3=='Hammocks & Chairs') ||($category_level3=='Aroma diffusers') ||($category_level3=='Blankets'))
						{
							$subcategory='113';
							$category='18';
						}
						if(($category_level3=='Tea holders')  || ($category_level3=='Water bottles') ||($category_level3=='Insulated bottles') ||($category_level3=='Sports bottles'))
						{
							$subcategory='55';
							$category='4';
						}
						if($category_level3=='Teddy bears & Others')
						{
							$subcategory='98';
							$category='17';
						}
						if(($category_level3=='Cutting boards')  || ($category_level3=='Utensils') ||($category_level3=='Cutlery') ||($category_level3=='Lunch boxes') || ($category_level3=='Mitten/Gloves'))
						{
							$subcategory='75';
							$category='4';
						}
						if($category_level3=='Gift bags')
						{
							$subcategory='3';
							$category='12';
						}
						if(($category_level3=='Raincoat')  || ($category_level3=='Ponchos') ||($category_level3=='Unfolded') ||($category_level3=='Foldable') )
						{
							$subcategory='72';
							$category='19';
						}
						if($category_level3=='Colour pencils')
						{
							$subcategory='101';
							$category='17';
						}
						if($category_level3=='Anti stress')
						{
							$subcategory='16';
							$category='14';
						}
						if($category_level3=='Tote bags')
						{
							$subcategory='102';
							$category='12';
						}
						if($category_level3=='Beach bags')
						{
							$subcategory='102';
							$category='12';
						}
						if(($category_level3=='Beach games')  || ($category_level3=='Football') ||($category_level3=='Bicycle items'))
						{
							$subcategory='85';
							$category='16';
						}
						if(($category_level3=='Travel blankets')  || ($category_level3=='Travel accessories') )
						{
							$subcategory='59';
							$category='12';
						}
						if(($category_level3=='Document bags')  || ($category_level3=='Laptop bags'))
						{
							$subcategory='4';
							$category='12';
						}
						if(($category_level3=='Foldable bags'))
						{
							$subcategory='3';
							$category='12';
						}
						if(($category_level3=='Gardening'))
						{
							$subcategory='105';
							$category='16';
						}
						if(($category_level3=='Art paints'))
						{
							$subcategory='101';
							$category='17';
						}
						if(($category_level3=='Badges')  || ($category_level3=='Lanyards') ||($category_level3=='Buttons'))
						{
							$subcategory='106';
							$category='7';
						}
						if(($category_level3=='Cosmetic/Toiletry bags')  || ($category_level3=='Food bags'))
						{
							$subcategory='87';
							$category='12';
						}
						if(($category_level3=='Heat & Cold pad')  || ($category_level3=='Lip balms') || ($category_level3=='Mirrors')  || ($category_level3=='Nail kits') || ($category_level3=='First aid sets') || ($category_level3=='Medical items') || ($category_level3=='Cleansing wipes') || ($category_level3=='Sunscreen lotion') || ($category_level3=='Hand cleansers & Tissues') || ($category_level3=='Care essentials') )
						{
							$subcategory='90';
							$category='18';
						}

						if(($category_level3=='Multitool knifes')  || ($category_level3=='Ice scrapers') || ($category_level3=='Measuring tapes')  || ($category_level3=='Dynamo') || ($category_level3=='Reflective & Safety lights') || ($category_level3=='Pocket torches') )
						{
							$subcategory='88';
							$category='19';
						}
						if(($category_level3=='Cooler bags'))
						{
							$subcategory='77';
							$category='12';
						}
						if(($category_level3=='Pens'))
						{
							$subcategory='10';
							$category='14';
						}
						if(($category_level3=='Memo pads/sticky notes'))
						{
							$subcategory='14';
							$category='14';
						}
						if(($category_level3=='Fans')  || ($category_level3=='Outdoor'))
						{
							$subcategory='97';
							$category='16';
						}
						if(($category_level3=='Conference folders'))
						{
							$subcategory='49';
							$category='14';
						}
						if(($category_level3=='Hats')  || ($category_level3=='Caps') || ($category_level3=='Beanies'))
						{
							$subcategory='21';
							$category='11';
						}
						if(($category_level3=='Sets'))
						{
							$subcategory='35';
							$category='14';
						}
						if(($category_level3=='Wallets/RFID')  || ($category_level3=='Card holders'))
						{
							$subcategory='1';
							$category='14';
						}
						if(($category_level3=='Jackets')  || ($category_level3=='Bodywarmers') || ($category_level3=='Softshells'))
						{
							$subcategory='22';
							$category='11';
						}
						if(($category_level3=='T-shirts')  || ($category_level3=="Polo's") || ($category_level3=='Sweat shirts'))
						{
							$subcategory='18';
							$category='11';
						}
						if(($category_level3=='Backpacks')  || ($category_level3=='Smart backpacks') || ($category_level3=='Backpacks/Trolley'))
						{
							$subcategory='32';
							$category='12';
						}
						if(($category_level3=='Multifunctional')  || ($category_level3=='Phone stand/holders') || ($category_level3=='Cables'))
						{
							$subcategory='11';
							$category='13';
						}
						if(($category_level3=='Smart folders'))
						{
							$subcategory='49';
							$category='14';
						}
						if(($category_level3=='Bottle openers')  || ($category_level3=='Openers') || ($category_level3=='Straws'))
						{
							$subcategory='112';
							$category='4';
						}
						if(($category_level3=='Speakers')  || ($category_level3=='Speakers mood light') || ($category_level3=='Multifunctional Speakers'))
						{
							$subcategory='25';
							$category='13';
						}
						if(($category_level3=='Slippers')  || ($category_level3=='Sunglasses') || ($category_level3=='Wristbands') || ($category_level3=='Multifunctional scarves') || ($category_level3=='Headbands') || ($category_level3=='Watches') || ($category_level3=='Others') || ($category_level3=='Towels'))
						{
							$subcategory='110';
							$category='11';
						}
						if(($category_level3=='Computer accessories')  || ($category_level3=='USB hubs'))
						{
							$subcategory='26';
							$category='13';
						}
						if(($category_level3=='Running items'))
						{
							$subcategory='85';
							$category='16';
						}
						if(($category_level3=='Other accessories')  || ($category_level3=='Car chargers') || ($category_level3=='Emergency items & Key finders'))
						{
							$subcategory='78';
							$category='13';
						}
						if(($category_level3=='Pencils')  || ($category_level3=='Laser pointers'))
						{
							$subcategory='10';
							$category='14';
						}
						if(($category_level3=='Accessories & Cases'))
						{
							$subcategory='63';
							$category='14';
						}
						if(($category_level3=='Drones & Game sets'))
						{
							$subcategory='74';
							$category='13';
						}
						if(($category_level3=='Games')  || ($category_level3=='Brain teasers'))
						{
							$subcategory='17';
							$category='17';
						}
						if(($category_level3=='Low capacity ≥2.000')  || ($category_level3=='Wireless charger') || ($category_level3=='Magnetic') || ($category_level3=='Mid capacity ≥4.000') || ($category_level3=='High capacity ≥8.000') || ($category_level3=='Quick chargers ≥10W') )
						{
							$subcategory='24';
							$category='13';
						}
						if(($category_level3=='Coasters'))
						{
							$subcategory='19';
							$category='4';
						}
						if(($category_level3=='Sport bags') || ($category_level3=='Waterproof bags'))
						{
							$subcategory='2';
							$category='12';
						}
						if(($category_level3=='Earphones/TWS') || ($category_level3=='Headphones'))
						{
							$subcategory='109';
							$category='13';
						}
						if(($category_level3=='Health watches') )
						{
							$subcategory='111';
							$category='13';
						}
						if(($category_level3=='Aprons') )
						{
							$subcategory='71';
							$category='11';
						}
						if(($category_level3=='Waist bags') || ($category_level3=='Drawstring bags') )
						{
							$subcategory='34';
							$category='12';
						}
						if(($category_level3=='Fitness') || ($category_level3=='Noise makers') )
						{
							$subcategory='108';
							$category='16';
						}
						if(($category_level3=='Candies'))
						{
							$subcategory='114';
							$category='7';
						}
						if(($category_level3=='Travel trolleys'))
						{
							$subcategory='5';
							$category='12';
						}
						if(($category_level3=='Wine accessories') || ($category_level3=='AA/AAA batteries'))
						{
							$subcategory=Null;
							$category=Null;
						}
						

				  }

				  //color
				 	 if(!empty($item->color_group))
					 {
						 $color[]=$item->color_group;
						 $allcolors=implode(',', array_unique($color));	
						 $color_group=$allcolors;
					 }  

					if(!empty($color))
					{
						$colorcodes = [];
						for($i=0;$i<count($color);$i++)
						{
							$colorcmsdata=Color::where('name',$color[$i])->first();
							if(!empty($colorcmsdata))
							{
								$colorcodes[]=$colorcmsdata->code;
									//echo "found";
							}	
						}
						$json_colors=json_encode($colorcodes);	
						if(!empty($json_colors))
						{
						 	$main_color=json_decode($json_colors);
						 	$colors=implode(',', array_unique($main_color));
						 	$colors=$colors;
						}
						// echo"<pre>";print_r(json_encode($colorcodes));die;
					}	

					//bulk image
					if(!empty($item->digital_assets))
					{
						foreach($item->digital_assets as $img)
						{
							 $image[]=$img->url;
							 $bulk_image=implode(',', $image);
							 $bulk_images=$bulk_image;
						}
					}  
				}
		  }
			$arr=[
					'modelno'=>'',
					'suppliercode'=>$val->master_code,
					'name' =>$name,
					'shortdescription' =>$shortdescription,
					'description' =>$description,
					'brand' => '42',
					'subcat' =>$category_level3,
					'subcategory' => $subcategory,
					'category' => $category,
					'color_group' =>$color_group,
					'colors' => $colors,
					'bulk_image' =>$bulk_images,
					'is_active' => 'online',
					'newarrival'=> 'offline',
					'featured'=>'offline',
					'sale'=>'offline',
					'most_selling'=>'offline',
					'slug' =>$slug,
					'created_at' =>date('Y-m-d H:i:s'),
					'updated_at' =>date('Y-m-d H:i:s'),
			];
			$insert_data[]=$arr;
		}
		$insert_data = collect($insert_data);
		$chunks = $insert_data->chunk(200);
		// echo "<pre>";print_r($chunks);die;
		foreach($chunks as $chunk)
		{
		  DB::table('midocean_products')->insert($chunk->toArray());
		}		

		echo "Record Added Successfully";
		
	}

	
	public function extract(Request $request)
	{ 
     // echo "<pre>";print_r($prod);
		$products=DB::table('products')->where('brand','42')->orderBy('id')->chunk(200, function ($products) {
    foreach($products as $prod)
    {
      $checkproduct=DB::table('midocean_products')->where('suppliercode',$prod->suppliercode)->first();
      if(!empty($checkproduct))
			{
				$name=$checkproduct->name;
				$shortdescription=$checkproduct->shortdescription;
				$description=$checkproduct->description;
				$bulk_image=$checkproduct->bulk_image;
				$is_active=$checkproduct->is_active;
				$newarrival=$checkproduct->newarrival;
				$featured=$checkproduct->featured;
				$sale=$checkproduct->sale;
				$most_selling=$checkproduct->most_selling;
				$slug=$checkproduct->slug;
				$bulk_image=$checkproduct->bulk_image;
				$subcategory=$checkproduct->subcategory;
				$category=$checkproduct->category;
				$colors=$checkproduct->colors;
				$img=explode(',',$checkproduct->bulk_image);
				$thumb=$img[0];
				if(!empty($img[1]))
				{
					$thumb2=$img[1];
				}
				else
				{
					$thumb2='';
				}
					DB::table('products')
				->where('suppliercode',$checkproduct->suppliercode)
				->update([
					'name' => $name,
					'shortdescription' => $shortdescription,
					'description' =>$description,
					'bulk_image' => $bulk_image,
					'is_active' =>$is_active,
					'newarrival' =>$newarrival,
					'featured' =>$featured,
					'sale' => $sale,
					'most_selling' => $most_selling,
					// 'slug' => $slug,
					'thumbnail' => $thumb,
					'thumbnail2' => $thumb2,
					'subcategory' =>$subcategory,
					'category' =>$category,
					'colors' =>$colors,
					'updated_at' =>date('Y-m-d H:i:s'),
				]);
			} 
			// else
			// {
			// 	DB::table('products')->where('suppliercode',$prod->suppliercode)->where('brand','42')->delete();
			// }
  	}  
		});
	}

	public function extract1(Request $request)
	{ 
		$midocean_products=DB::table('midocean_products')->where('subcat','!=','Wine accessories')->where('subcat','!=','AA/AAA batteries')->orderBy('id')->chunk(200, function ($midocean_products) {
    foreach($midocean_products as $mid_prod)
    {
      $checkproduct=DB::table('products')->where('suppliercode',$mid_prod->suppliercode)->first();
      if(empty($checkproduct))
			{
				$product=new Product;
				$product->brand='42';
				$product->name=$mid_prod->name;
				$product->suppliercode=$mid_prod->suppliercode;
				$product->shortdescription=$mid_prod->shortdescription;
				$product->description=$mid_prod->description;
				$product->is_active=$mid_prod->is_active;
				$product->newarrival=$mid_prod->newarrival;
				$product->featured=$mid_prod->featured;
				$product->sale=$mid_prod->sale;
				$product->most_selling=$mid_prod->most_selling;
				$product->slug=$mid_prod->slug;
				$product->bulk_image=$mid_prod->bulk_image;
				$product->subcategory=$mid_prod->subcategory;
				$product->category=$mid_prod->category;
				$product->colors=$mid_prod->colors;
				$img=explode(',',$mid_prod->bulk_image);
				$product->thumbnail=$img[0];
				if(!empty($img[1]))
				{
					$product->thumbnail2=$img[1];
				}
				else
				{
					$product->thumbnail2='';
				}
				 $product->save();
			} 

  	}  
		});

		$prod=DB::table('products')->where('brand','42')->get();
		$modalno=7053;
		foreach($prod as $val)
		{
			$no=$modalno++;
			$modal_no='SGM'.$no;
			DB::table('products')
			->where('id',$val->id)
			->update(['modelno' => $modal_no]);
		}
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