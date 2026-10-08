<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Categorybanner;
use App\Models\Header_setting;
use App\Models\Footer_setting;
use App\Models\Homepage_setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Category_setting;
use App\Models\Parentcategory;
use App\Models\Subcategory;
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
use App\Models\General_setting;
use App\Models\Instock_notifier;
use App\Models\B2enquiry;
use App\Models\Installation;
use App\Models\Productcompare;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Schema;
//use App\Models\Header_setting;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use DB;
use Mail;
use Illuminate\Support\Facades\File;


class ApiController extends Controller
{
	public function getbannerlevel1(Request $request)
	{
		//echo "test";die;
		$bannerslevel1=Categorybanner::where('id',1)->first();
		//dd($bannerslevel1);
		return json_encode($bannerslevel1);
	}

	public function getbannerlevel2(Request $request)
	{
		//echo "test";die;
		$bannerslevel2=Categorybanner::where('id',4)->first();
		//dd($bannerslevel1);
		return json_encode($bannerslevel2);
	}

	public function getbannerlevel3(Request $request)
	{
		//echo "test";die;
		$bannerslevel3=Categorybanner::where('id',2)->first();
		//dd($bannerslevel1);
		return json_encode($bannerslevel3);
	}

	public function getbannerlevel6(Request $request)
	{
		//echo "test";die;
		$bannerslevel6=Categorybanner::where('id',3)->first();
		//dd($bannerslevel1);
		return json_encode($bannerslevel6);
	}

	public function categoryslider(Request $request)
	{
		//echo "test";die;
		$categories=Category::where('is_active','online')->get();
		//dd($bannerslevel1);
		return json_encode($categories);
	}


    public function mostselling(Request $request)
    {
        $products = DB::table('products')
            ->where('products.is_active', 'online')
            ->where('products.newarrival', 'online')
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select(
                'products.id',
                'products.slug',
                'products.name',
                'products.thumbnail',
                'products.thumbnail2',
                'products.modelno',
                'products.price',
                'products.sprice',
                'brands.brandname',
                'brands.slug as brandslug',
                'categories.slug as catslug',
                'categories.catname'
            )
            ->limit(12)
            ->get();
    
        $products->transform(function ($product) {
    
            // ✅ Correct folder path
            $thumb2Path = public_path('upload/product/thumbnail/' . $product->thumbnail2);
    
            if (
                empty($product->thumbnail2) ||
                !File::exists($thumb2Path)
            ) {
                $product->thumbnail2 = $product->thumbnail;
            }
    
            return $product;
        });
    
        return response()->json([
            'products' => $products,
            'category' => 'No cat data'
        ]);
    }
    
    private function getProductsByCategorySetting($settingId)
    {
        $getcatdata = Category_setting::where('id', $settingId)->first();
    
        if (!$getcatdata) {
            return null;
        }
    
        $products = DB::table('products')
            ->where('products.is_active', 'online')
            ->where('products.featured', 'online')
            ->where('products.category', $getcatdata->category_id)
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select(
                'products.id',
                'products.slug',
                'products.name',
                'products.thumbnail',
                'products.thumbnail2',
                'products.modelno',
                'products.price',
                'products.sprice',
                'brands.brandname',
                'brands.slug as brandslug',
                'categories.slug as catslug',
                'categories.catname'
            )
            ->limit(12)
            ->get();
    
        // ✅ Thumbnail2 fallback (CORRECT PATH)
        $products->transform(function ($product) {
    
            $thumb2Path = public_path('upload/product/thumbnail/' . $product->thumbnail2);
    
            if (empty($product->thumbnail2) || !File::exists($thumb2Path)) {
                $product->thumbnail2 = $product->thumbnail;
            }
    
            return $product;
        });
    
        return [
            'products' => $products,
            'category' => $getcatdata
        ];
    }
    
    public function productl1(Request $request)
    {
        $data = $this->getProductsByCategorySetting(1);
        return $data ? response()->json($data) : "No Data Found!";
    }
    
    public function productl2(Request $request)
    {
        $data = $this->getProductsByCategorySetting(2);
        return $data ? response()->json($data) : "No Data Found!";
    }


    public function productl3(Request $request)
    {
        $data = $this->getProductsByCategorySetting(3);
        return $data ? response()->json($data) : "No Data Found!";
    }
    
    public function productl4(Request $request)
    {
        $data = $this->getProductsByCategorySetting(4);
        return $data ? response()->json($data) : "No Data Found!";
    }
    
    public function productl5(Request $request)
    {
        $data = $this->getProductsByCategorySetting(5);
        return $data ? response()->json($data) : "No Data Found!";
    }
    
    public function productl6(Request $request)
    {
        $data = $this->getProductsByCategorySetting(6);
        return $data ? response()->json($data) : "No Data Found!";
    }
    
    public function productl7(Request $request)
    {
        $data = $this->getProductsByCategorySetting(7);
        return $data ? response()->json($data) : "No Data Found!";
    }

    public function productl8(Request $request)
    {
        $data = $this->getProductsByCategorySetting(8);
        return $data ? response()->json($data) : "No Data Found!";
    }

    public function productl9(Request $request)
    {
        $data = $this->getProductsByCategorySetting(9);
        return $data ? response()->json($data) : "No Data Found!";
    }





	public function storeuser(Request $request)
	{
		
		$storeuser=new Storeuser;
		$storeuser->fname=$request->name;
		$storeuser->email=$request->email; 
		$storeuser->save();

		return 'Stored';

	}

	 

	public function getcatproducts(Request $request)
	{
		$colors=Color::all();
		
		//$requestslug="office-stationery-and-writing";//$request->id;
		$requestslug=$request->id;
		$requestsubcats=$request->subcats;
		$requestcolors=$request->colors;

		$category=Category::where('slug',$requestslug)->first();
		$subcategories=Subcategory::where('catid',$category->id)->where('is_active','online')->get();

		function getproductquery($requestslug,$requestsubcats,$requestcolors)
		{	
			//$colors=Color::all();
			$category=Category::where('slug',$requestslug)->first();
			$subcategories=Subcategory::where('catid',$category->id)->where('is_active','online')->get();
			$productdetails=DB::table('products')
						->where('is_active','online')->where('category',$category->id);

			

			if(!empty($requestsubcats))
			{
					$productdetails=$productdetails->whereIN('products.subcategory',$requestsubcats);
			}

			if(!empty($requestcolors))
			{
				$mycolors=$requestcolors;
				$productdetails=$productdetails->where(function($query) use($mycolors) 
				{
	                foreach($mycolors as $term) {
	                    $query->orWhere('colors', 'like', "%$term%");
	                };
	        	});
			}	

			return $productdetails;

		}	
	
		$newarrival=getproductquery($requestslug,$requestsubcats,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('newarrival','online')
						->get();

		//echo "<pre>";print_r($newarrival);die;						

		$hotproduct=getproductquery($requestslug,$requestsubcats,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('sale','online')
						->get();

		$midocean=getproductquery($requestslug,$requestsubcats,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('brand','42')
						->get();


		$sub_products=$newarrival->merge($hotproduct)->merge($midocean);

		$sub_products_id=array();
        foreach($sub_products as $item)
        {
            $sub_products_id[]=$item->id;
        }    
        
        $remaining=getproductquery($requestslug,$requestsubcats,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->wherenotin('id',$sub_products_id)
						->get();


		$productdetails=$sub_products->merge($remaining)->unique('id');//->toarray();	

		foreach($productdetails as $key=>$reindex)
		{
			$finalarray[]=$reindex;
		}


		
		$productdetails=$finalarray;

		if(!empty($request->subcats))
		{
			$selectedsubcats=$request->subcats;	
		}	
		else
		{
			$selectedsubcats=[]	;	
		}

		if(!empty($request->colors))
		{
			$selectedcolors=$request->colors;	
		}	
		else
		{
			$selectedcolors=[];	
		}					
		//$selectedsubcats=$request->items['colors'];				
		return json_encode(
			[
				'products' => $productdetails,
				'categorydata' => $category,
				'subcategories' => $subcategories,
				'colors' => $colors,
				'selectedsubcats' => $selectedsubcats,
				'selectedcolors' => $selectedcolors
			]);
   
	}


	public function getsubcatproducts(Request $request)
	{
		$colors=Color::all();	
		$requestslug=$request->id;	
		$category=Subcategory::where('slug',$requestslug)->first();
		$maincatname=Category::where('id',$category->catid)->first();
		$requestcolors=$request->colors;
		function getproductquery($requestslug,$requestcolors)
		{	
			
			//$colors=Color::all();
			$category=Subcategory::where('slug',$requestslug)->first();
			$productdetails=DB::table('products')
						->where('is_active','online')->where('subcategory',$category->id);

			if(!empty($requestcolors))
			{
				$mycolors=$requestcolors;
				$productdetails=$productdetails->where(function($query) use($mycolors) 
				{
	                foreach($mycolors as $term) {
	                    $query->orWhere('colors', 'like', "%$term%");
	                };
	        	});
			}

			return $productdetails;
		}
		$newarrival=getproductquery($requestslug,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('newarrival','online')
						->get();

		$hotproduct=getproductquery($requestslug,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('sale','online')
						->get();

		$midocean=getproductquery($requestslug,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->where('brand','42')
						->get();

		$sub_products=$newarrival->merge($hotproduct)->merge($midocean);

		$sub_products_id=array();
        foreach($sub_products as $item)
        {
            $sub_products_id[]=$item->id;
        }
        $remaining=getproductquery($requestslug,$requestcolors)
						->select('id',
							'slug',
							'name',
							'thumbnail',
							'thumbnail2',
							'brand',
							'modelno',
							'newarrival',
							'sale',
							)
						->wherenotin('id',$sub_products_id)
						->get(); 	

		$productdetails=$sub_products->merge($remaining)->unique('id');

		foreach($productdetails as $key=>$reindex)
		{
			$finalarray[]=$reindex;
		}

		if(!empty($request->colors))
		{
			$selectedcolors=$request->colors;	
		}	
		else
		{
			$selectedcolors=[];	
		}

		$productdetails=$finalarray;

		return json_encode(['products' => $productdetails,
		 					'categorydata' => $category,
		 					'colors' => $colors,
							'maincatname'=>$maincatname,
							'selectedcolors' => $selectedcolors]);
	} 
 

	public function cartdata(Request $request)
	{   
		$cartdata=Tmporder::where('sessionid',session()->get('sessionid'))->get();
		return json_encode($cartdata);  
	} 


	public function gethomedata(Request $request)
	{   
		$homesettings=Homepage_setting::where('id','1')->first();
		return json_encode($homesettings);  
	} 


	public function getallparentcategories(Request $request)
	{   
		$categories=Category::select('id','catname','slug')->where('is_active','online')->orderBy('ranking')->get();
		return json_encode($categories);  
	} 

	public function getallcategories(Request $request)
	{   
		$categories=Subcategory::select('id','catid','catname','slug')->where('is_active','online')->orderBy('ranking')->get();
		return json_encode($categories);  
	} 
	
	// public function getallsubcategories(Request $request)
	// {   
	// 	// $categories=Subcategory::select('id','catid','catname','slug')->where('is_active','online')->orderBy('ranking')->get();
	// 	// return json_encode($categories);  

	// 	$categories=Category::select('id','catname','slug')->where('is_active','online')->orderBy('ranking')->get();
	// 	$subcategories=Subcategory::select('id','catid','catname','slug')->where('is_active','online')->orderBy('ranking')->get();
	// 	$product=Product::where('is_active','online')->select('id','name','slug')->get();

	// 	echo "CATEGORIES"." <br>";
	// 	foreach($categories as $item)
	// 	{
	// 		echo 	htmlspecialchars("<url>");
	// 		echo "<br>";
	// 	  	echo htmlspecialchars("<loc>https://silvergiftz.com/category/".$item->slug."</loc>");
	// 	  	echo "<br>";
	// 	  	echo htmlspecialchars("<lastmod>2022-09-29T10:24:40+00:00</lastmod>");
	// 	  	echo "<br>";
	// 	  	// echo htmlspecialchars("<priority>0.80</priority>");
	// 	  	// echo "<br>";
	// 		echo htmlspecialchars("</url>");

	// 		echo "<br>";
	// 	}

	// 	echo "SUBCATEGORIES"." <br>";
	// 	foreach($subcategories as $item)
	// 	{
	// 		echo 	htmlspecialchars("<url>");
	// 		echo "<br>";
	// 	  	echo htmlspecialchars("<loc>https://silvergiftz.com/subcategory/".$item->slug."</loc>");
	// 	  	echo "<br>";
	// 	  	echo htmlspecialchars("<lastmod>2022-09-29T10:24:40+00:00</lastmod>");
	// 	  	echo "<br>";
	// 	  	// echo htmlspecialchars("<priority>0.80</priority>");
	// 	  	// echo "<br>";
	// 		echo htmlspecialchars("</url>");

	// 		echo "<br>";
	// 	}	


	// 	echo "PRODUCTS"." <br>";
	// 	foreach($product as $item)
	// 	{
	// 		echo 	htmlspecialchars("<url>");
	// 		echo "<br>";
	// 	  	echo htmlspecialchars("<loc>https://silvergiftz.com/product/".$item->slug."</loc>");
	// 	  	echo "<br>";
	// 	  	echo htmlspecialchars("<lastmod>2022-09-29T10:24:40+00:00</lastmod>");
	// 	  	echo "<br>";
	// 	  	// echo htmlspecialchars("<priority>0.80</priority>");
	// 	  	// echo "<br>";
	// 		echo htmlspecialchars("</url>");

	// 		echo "<br>";
	// 	}		

	// 	die;


	// } 


	public function getallbrands(Request $request)
	{   
		$categories=Brand::select('id','brandname','slug','image1')->where('is_active','online')->get();
		return json_encode($categories);  
	} 

	public function addsubscriber(Request $request)
	{   
		 //return $request->email;

		 $checksubscriber=Subscribe::where('email',$request->email)->first();
		 if(!empty($checksubscriber))
		 {
			return "You have already subscribed to our Newsletters!";
		 }
		 else
		 {
			$subscriber=new Subscribe;
			$subscriber->email=$request->email;
			$subscriber->save();

			return "Thanks for subscribing our Newsletters!";

		 }   
	} 


	public function getproductdetails(Request $request)
	{   
		
		// $productdetails=Product::where('slug',$request->slug)->first();
		// return json_encode($productdetails);  


		$productdetails=DB::table('products')
					->where('products.is_active','online')
					->where('products.slug',$request->slug)
					->join('brands', 'products.brand', '=', 'brands.id')
					->join('categories', 'products.category', '=', 'categories.id')
					->join('subcategories', 'products.subcategory', '=', 'subcategories.id')
					->select('products.id',
							'products.slug',
							'products.name',
							'products.modelno',
							'products.metatitle',
							'products.metadesc',
							'products.og_url',
							'products.metakey',
							'products.bulk_image',
							'products.thumbnail',
							'products.shortdescription',
							'products.description',
							'products.specification',
							'products.quantity',
							'products.date_sale_price_ends',
							'products.date_sale_price_start',
							'products.image1',
							'products.image2',
							'products.image3',
							'products.image4',
							'products.image5',
							'products.image6',
							'products.price',
							'products.sprice',
							'products.variations',
							'products.colors',
							'products.choice_options',
							'brands.brandname',
							'brands.slug as brandslug',
							'categories.slug as catslug',
							'categories.catname',
							'subcategories.slug as subcatslug',
							'subcategories.catname as subcatname'
							)->first();
		$productsdata=Product::where('id',$productdetails->id)->first(); 

		$cmscolors=Color::all(); 
		//$item_array=explode(',',$productsdata->related_product);

		$relatedproducts=Product::where('category',$productsdata->category)->where('is_active','online')->where('slug','!=',$request->slug)
		->select(
		'id',
		'slug',
		'name',
		'thumbnail',
		'brand',
		'modelno'
		)->get()->take(9);   

	 

		//return json_encode($productdetails);     
		return json_encode(
			[
				'productdetails' => $productdetails,
				'relatedproduct' => $relatedproducts,
				'cmscolors'=>$cmscolors
			   
			]);        
		   
	}
	public function apitest(Request $request)
	{
		$installationsdata_A=Installation::where('product_id',$productdetails->id)->where('installation_type','A')->get();
		if(count($installationsdata_A)>0)
		{
			$installations_A_name=$installationsdata_A->first()->installation_title;
			$installations_A_service=$installationsdata_A;

		}
		else
		{
				 $installations_A_name=0;
				 $installations_A_service=0;
		}


		//b service

		$installationsdata_B=Installation::where('product_id',$productdetails->id)->where('installation_type','B')->get();
		if(count($installationsdata_B)>0)
		{
			$installations_B_name=$installationsdata_B->first()->installation_title;
			$installations_B_service=$installationsdata_B;

		}
		else
		{
				 $installations_B_name=0;
				 $installations_B_service=0;
		}


		//C service

		$installationsdata_C=Installation::where('product_id',$productdetails->id)->where('installation_type','C')->get();
		if(count($installationsdata_C)>0)
		{
			$installations_C_name=$installationsdata_C->first()->installation_title;
			$installations_C_service=$installationsdata_C;

		}
		else
		{
				 $installations_C_name=0;
				 $installations_C_service=0;
		}


		//D service

		$installationsdata_D=Installation::where('product_id',$productdetails->id)->where('installation_type','D')->get();
		if(count($installationsdata_D)>0)
		{
			$installations_D_name=$installationsdata_D->first()->installation_title;
			$installations_D_service=$installationsdata_D;

		}
		else
		{
				 $installations_D_name=0;
				 $installations_D_service=0;
		}

		return json_encode(
			[
				'installations_A_name' => $installations_A_name,
				'installations_A_service' => $installations_A_service,

				'installations_B_name' => $installations_B_name,
				'installations_B_service' => $installations_B_service,

				'installations_C_name' => $installations_C_name,
				'installations_C_service' => $installations_C_service,

				'installations_D_name' => $installations_D_name,
				'installations_D_service' => $installations_D_service,
				
			]); 

	  

		  

		// foreach($grouped as $data)
		// {
		//     //echo"<pre>";print_r($data);
		//     echo $data;
		//     // foreach($data as $mindata)
		//     // {
		//     //     echo $mindata;
		//     // }    
		// }    
		//echo"<pre>";print_r($grouped);die;
	}   
		

	public function getbrandproducts(Request $request)
	{   
		
		// $productdetails=Product::where('slug',$request->slug)->first();
		// return json_encode($productdetails);  

		$branddetails=Brand::where('slug',$request->slug)->first();
		$productdetails=DB::table('products')
					->where('products.is_active','online')->where('products.brand',$branddetails->id)
					->join('brands', 'products.brand', '=', 'brands.id')
					->join('parentcategories', 'products.parentcategory', '=', 'parentcategories.id')
					->select('products.id',
							'products.slug',
							'products.name',
							'products.thumbnail',
							'products.description',
							'products.image1',
							'products.image2',
							'products.price',
							'products.sprice',
							'brands.brandname',
							'brands.slug as brandslug',
							'parentcategories.slug as catslug',
							'parentcategories.catname'
							)->get();

		return json_encode(['products' => $productdetails,'brand' => $branddetails]);         
		   
	}


	public function addtocart(Request $request)
	{
		

		$products=$request->productsinfo;

			$orderid_data=Orderid::orderByDesc('id')->first();
			if(!empty($orderid_data))
			{
				$orderid=$orderid_data->orderid;
				$orderid=$orderid+1;
				$orderinsert=new Orderid;
				$orderinsert->orderid=$orderid;
				$orderinsert->save();
				
			} 
			else
			{
				$orderid="10001";
				$orderinsert=new Orderid;
				$orderinsert->orderid=$orderid;
				$orderinsert->save();
			} 
		$subtotal=0;    
		foreach($products as $item )
		{   
			$subtotal+=$item['price']*$item['quantity'];
			$tmporder=new Tmporder;
			$tmporder->orderid= $orderid;
			$tmporder->slug=$item['slug'];
			$tmporder->orderdate=date('d-m-Y');
			$tmporder->name=$item['name'];
			$tmporder->categoryname=$item['catname'];
			$tmporder->brandname=$item['brandname'];
			$tmporder->maincategoryslug=$item['catslug'];
			$tmporder->thumbnail=$item['image1'];
			$tmporder->quantity=$item['quantity'];
			$tmporder->price=$item['price'];
			$tmporder->sprice=$item['sprice'];
			//$tmporder->subtotal=$subtotal;
			$tmporder->save();
		}

		DB::table('tmporders')
			->where('orderid',$orderid)
			->update([
			 'userid' =>$request->userid, 
			 'subtotal' =>$subtotal,        
			'customer_name' => $request->userinfo['first_name'].$request->userinfo['last_name'],
			'customer_company' =>$request->userinfo['company_name'],
			'customer_email' => $request->userinfo['email_address'],
			'customer_phone' => $request->userinfo['tel_number'],
			'customer_address'=>$request->userinfo['p_address'],
			'customer_city'=>$request->userinfo['city_name'],
			'customer_country'=>$request->userinfo['country_name'],
			//'customer_state'=>$request->userinfo['province_name'],
			'customer_pincode'=>$request->userinfo['zip_code'],
			'additional_info'=>$request->userinfo['order_notes'],    
			'payment_mode'=>$request->userinfo['paymentmode'],    
			//'ordercomplete' =>"Yes",
		   
			]);

			$cust_email=$request->userinfo['email_address'];
			$cust_name=$request->userinfo["first_name"]." ".$request->userinfo["last_name"];
			$cust_address=$request->userinfo['p_address'];
			$cust_city=$request->userinfo['city_name'];
			$cust_country=$request->userinfo['country_name'];
			//$cust_ip=Request::ip();
			if($request->userinfo['updateaddress'])
			{
			   DB::table('storeusers')
				->where('id',$request->userid)
				->update([
				'email' =>$request->userinfo['email_address'],
				'mobile' => $request->userinfo['tel_number'],
				'fname' => $request->userinfo['first_name'],
				'lname'=>$request->userinfo['last_name'],
				'company'=>$request->userinfo['company_name'],
				'address'=>$request->userinfo['p_address'],
				//'customer_state'=>$request->userinfo['province_name'],
				'country'=>$request->userinfo['country_name'],
				'city'=>$request->userinfo['city_name'],    
				'pincode'=>$request->userinfo['zip_code'],    
				//'ordercomplete' =>"Yes",
			   
				]);
			}

			if($request->userinfo['paymentmode']=="PAYTABS")
			{   
			
			$order_details=Tmporder::where('orderid',$orderid)->first();
			$order_amount=$order_details->subtotal;     

			 $orderdata= <<<DATA
				{
					"profile_id": 48334,
					"tran_type": "sale",
					"tran_class": "ecom" ,
					"cart_id":"$orderid",
					"cart_description": "$cust_email",
					"cart_currency": "AED",
					"cart_amount": "$order_amount",
					"customer_details": {
					  "name": "$cust_name",
					  "email": "$cust_email",
					  "street1": "$cust_address",
					  "city": "$cust_city",
					  "country": "$cust_country",
					  "ip": "94.204.129.89"
					  
					},
					"shipping_details": {
					  "name": "$cust_name",
					  "email": "$cust_email",
					  "street1": "$cust_address",
					  "city": "$cust_city",
					  "country": "$cust_country",
					  "ip": "94.204.129.89"
					},
					"callback": "https://beta.sevenwonder.ae/callback",
					"return": "https://beta.sevenwonder.ae/paytab_returnurl"
				}
				DATA;

				return $payment_url=paytabs($orderdata);
			}
			else
			{

			}



		   
			
			return $orderid;   

			//return json_encode(['products' => $productdetails,'brand' => $branddetails]);  


	}

	public function searchproduct(Request $request)
	{
		$searchdata=$request->item;
		$columns = Schema::getColumnListing('products');
			//$columns = ['name'];  
			//return $columns;

			$query = Product::query();
			foreach($columns as $column){
				$query->orWhere($column, 'LIKE', '%' . $searchdata . '%');
			}
			$products=$query->where('is_active','online')
							//->join('brands', 'products.brand', '=', 'brands.id')
							//->join('categories', 'products.category', '=', 'categories.id')
							->select('products.id',
							'products.slug',
							'products.name',
							'products.thumbnail',
							'products.thumbnail2',
							'products.modelno',
							'products.brand',
							//'brands.slug as brandslug',
						   //	'categories.slug as catslug',
						   	//'categories.catname'
						   )
						   	->get();

			// $products =DB::table('products')
			// 		->where('products.is_active','online')->where('products.newarrival','online')  
			// 		//->join('brands', 'products.brand', '=', 'brands.id')
			// 		//->join('categories', 'products.category', '=', 'categories.id')
			// 		->select('products.id',
			// 				'products.slug',
			// 				'products.name',
			// 				'products.thumbnail',
			// 				'products.modelno',
			// 				'products.price',
			// 				'products.sprice',
			// 				//'brands.brandname',
			// 				//'brands.slug as brandslug',
			// 			   ///'categories.slug as catslug',
			// 			   //'categories.catname'
			// 				)
			// 		->get()->take(9);
			return json_encode(['products' => $products,'searchdata' => $searchdata]);
	}

	public function search_or_update(Request $request)
	{
		$orderid=$request->item; 
		 
		$order_details=Tmporder::where('orderid',$orderid)->first();

		if(!empty($order_details))
		{
			if($order_details->ordercomplete=="Yes")
			{
				return json_encode(['orderid' => $request->item,'status' =>"Already Completed"]);
			}
			else
			{
				DB::table('tmporders')->where('orderid',$orderid)
				->update(['ordercomplete' =>'Yes']);

				Tmporder::query()
				->where('orderid',$orderid)
				->each(function ($oldPost) {
				$newPost = $oldPost->replicate();
				$newPost->setTable('cartorders');
				$newPost->save(); });

				$order_email_details=Tmporder::where('orderid',$orderid)->first();

				$details = [
				'orderid'=>$orderid,
				'orderdate'=>date('d-m-Y'),
				'customer_name' =>$order_email_details->customer_name,
				'customer_company' =>$order_email_details->customer_company,
				'customer_email' =>$order_email_details->customer_email,
				'customer_phone' => $order_email_details->customer_phone,
				'additional_info'=> $order_email_details->additional_info,
				'customer_address'=> $order_email_details->customer_address,
				'customer_city'=>$order_email_details->customer_city, 
				'customer_country'=>$order_email_details->customer_country,
				'customer_pincode'=>$order_email_details->customer_pincode,
				 'payment_mode'=>$order_email_details->payment_mode,
				];

				$headersettings= Header_setting::where('id','1')->first();
				$admin_email="richa@silverpixelz.com";
				// $admin_email="silverpixelz.advertising@gmail.com";
				// $admin_email=$headersettings->headeremail;
				$customer_email=$request->userinfo['email_address'];

				sendmail_to_admin($details,$admin_email);
				sendmail_to_customer($details,$customer_email,$admin_email);

				return json_encode(['orderid' => $orderid,'status' =>"Order Completed"]);
			}

			//return json_encode(['orderid' => $orderid,'status' =>"Order Completed"]);    
		}
		else
		{
			return json_encode(['orderid' => $orderid,'status' =>"Invalid"]);
		}    

	}

	public function paytab_returnurl(Request $request)
	{   

		//echo url('/');die ;
	   // echo $request->respStatus;
		//print_r($_POST);
	   $orderid=$request->cartId; 
	   if($request->respStatus=="D")
	   {
		  return   Redirect::to('http://localhost:4200/failed/'.$orderid);
	   }
	   else if ($request->respStatus=="A") 
	   {
		   // echo "die";
			return  Redirect::to('http://localhost:4200/thankyou/'.$orderid);
	   } 
	   else
	   {

	   } 
	}


	public function registeruser(Request $request)
	{
		//print_r($_POST);die;

		$email=$request->userinfo['email_address'];
		

		$checkuser=DB::table('storeusers')->select('*')->where('email', $email)->first();
		
		if(!empty($checkuser))
		{   
		 
			//$request->session()->flash('error','Email Already Registered !');
			//return Redirect::back();
			return json_encode(['status' =>'Error','msg' =>'Email Already Exists!']); 
		}
		else
		{
			$password=Str::random(12);

			// $user=new Storeuser;
			// $user->email=$email;
			// $user->fname=$request->userinfo['first_name'];
			// $user->lname=$request->userinfo['last_name'];
			// $user->password=$password;
			// $user->save();

			$user = Storeuser::create([
			'email' => $email,
			'fname' => $request->userinfo['first_name'],
			'lname' => $request->userinfo['last_name'],
			'password' =>$password
			]);



			$general_settings=General_setting::where('id','1')->first();

			$admin_email=$general_settings->mailfromemail;
			$mail_from=$general_settings->mailname;


			$details = [
			'email'=>$email,
			'password'=>$password,
			'$admin_email'=>$admin_email,
			'mail_from'=>$mail_from,
			 ];

			Mail::to($email)->send(new \App\Mail\Registrationemail($details));

		}



		return json_encode([
			'status' =>'Success',
			'msg' =>'Registered successfully!',
			'userid' =>$user->id,
		]);

		  //$request->session()->flash('success','Registered successfully !');
		  //return Redirect::to('store/login');
		
	}

	public function registersocialuser(Request $request)
	{
		//print_r($_POST);die;

		$data=$request->userinfo[0];

		$email=$data['email'];
		$id=$data['id'];
		$name=$data['user'];
		$type=$data['type'];
		//return json_encode(['status' =>$type]); 

		//return $email;

		$checkuser=DB::table('storeusers')->select('*')
		->where('email', $email)->first();
		
		if(!empty($checkuser))
		{   
			
			$checksocialuser=DB::table('storeusers')->select('*')
			->where('email', $email)->wherenotnull('socialid')->first();
			if(!empty($checksocialuser))
			{
				return json_encode([
					'status' =>'Success',
					'msg' =>'Log in successful!',
					'userid' =>$checksocialuser->id,
				]);
			}
			else
			{
				return json_encode(['status' =>'Error','msg' =>'Account exists, please try to login with username and password']); 
			}    


			//$request->session()->flash('error','Email Already Registered !');
			//return Redirect::back();
			
		}
		else
		{
			$password=Str::random(12);

			$user = Storeuser::create([
			'email' => $email,
			'fname' => $name,
			'socialid' => $id,
			'socialaccount' =>$type
			]);



			$general_settings=General_setting::where('id','1')->first();

			$admin_email=$general_settings->mailfromemail;
			$mail_from=$general_settings->mailname;


			$details = [
			'email'=>$email,
			'password'=>$password,
			'$admin_email'=>$admin_email,
			'mail_from'=>$mail_from,
			 ];

			Mail::to($email)->send(new \App\Mail\Registrationemail($details));

		}



		return json_encode([
			'status' =>'Success',
			'msg' =>'Registered successfully!',
			'userid' =>$user->id,
		]);

		  //$request->session()->flash('success','Registered successfully !');
		  //return Redirect::to('store/login');
		
	}

	public function loginuser(Request $request)
	{

	  $email=$request->userinfo['email_address'];
	  $password=$request->userinfo['password'];
	  $checkuser=DB::table('storeusers')->select('*')->where('email', $email)
	  ->where('password',$password)->first();
	  
	  if(!empty($checkuser))
	  {
		  
		$userid=$checkuser->id; 
		$useremail=$checkuser->email;
		return json_encode([
			'status' =>'Success',
			'msg' =>'Login Successfull!',
			'userid' =>$userid,
			'useremail' =>$useremail,
		]); 
		 
	  }
	  else
	  {
		return json_encode(['status' =>'Error','msg' =>'Invalid Credentials!']);

	   }   
	}

	public function getuserorderdetails(Request $request)
	{


	  $userorders=Cartorder::where('userid',$request->userinfo)->select('orderid','subtotal','id')->get()->unique('orderid')->values();

	  $orderdatas=Cartorder::where('userid',$request->userinfo)->get();

	 // echo "<pre>";print_r($userorders);die;
	  //$userorders=array_values($userorders);
	  
	  if(count($userorders)>0)
	  {
				  
		return json_encode([
			'status' =>'Success',
			'msg' =>'Orders Found !',
			'data' =>$userorders,
			'orderdata' =>$orderdatas
		]); 
		 
	  }
	  else
	  {
		return json_encode(['status' =>'Error','msg' =>'Orders Not Found !']);

	   }   
	}

	public function getuserdetails(Request $request)
	{


	  $checkuser=DB::table('storeusers')->select('*')->where('id',$request->userinfo)
	  ->first();
	  
	  if(!empty($checkuser))
	  {
				  
		return json_encode([
			'status' =>'Success',
			'msg' =>'User Found !',
			'data' =>$checkuser
		]); 
		 
	  }
	  else
	  {
		return json_encode(['status' =>'Error','msg' =>'User Not Found !']);

	   }   
	}

	public function updateuserdetails(Request $request)
	{

		DB::table('storeusers')
		->where('id',$request->userid)
		->update([
		'email' =>$request->userinfo['email_address'],
		'mobile' => $request->userinfo['tel_number'],
		'fname' => $request->userinfo['first_name'],
		'lname'=>$request->userinfo['last_name'],
		'company'=>$request->userinfo['company_name'],
		'address'=>$request->userinfo['p_address'],
		'country'=>$request->userinfo['country_name'],
		'city'=>$request->userinfo['city_name'],    
		'pincode'=>$request->userinfo['zip_code'],   
		'password'=>$request->userinfo['password'],    
		
	   
		]);

		 return json_encode(['status' =>'Success','msg' =>'User Profile Updated !']);
	}

	public function stocknotify(Request $request)
	{
		
		// $user = Instock_notifier::create([
		//     'product' =>$request->productid['id'],
		//     'email' => $request->userinfo['email']
		//     ]);
		
		$user=new Instock_notifier();
		$user->product=$request->productid['id'];
		$user->email=$request->userinfo['email'];
		$user->save();

	   return json_encode(['status' =>'Success','msg' =>'Added successfully !']);
	}

	public function b2benquiry(Request $request)
	{
		
		// $user = Instock_notifier::create([
		//     'product' =>$request->productid['id'],
		//     'email' => $request->userinfo['email']
		//     ]);
		
		$user=new B2enquiry();
		$user->product=$request->productid['id'];
		$user->email=$request->userinfo['customer_email'];
		$user->phone=$request->userinfo['customer_phone'];
		$user->name=$request->userinfo['customer_name'];
		$user->quantity=$request->userinfo['quantity'];
		$user->save();

	   return json_encode(['status' =>'Success','msg' =>'Enquiry Submitted Successfully !']);
	}

	public function addtocompare(Request $request)
	{
		$checkproduct=Productcompare::where('userid',$request->userinfo)
		->where('productslug',$request->productid)->first();

		if(empty($checkproduct))
		{    
			$checkcomparecount=Productcompare::where('userid',$request->userinfo)->get();

			if(count($checkcomparecount)==3)
			{
			  return json_encode([
				'status' =>'Error',
				'msg' =>'Compare List Full',
				'count'=>count($checkcomparecount)
				]);  
			}
			else 
			{
				$user=new Productcompare();
				$user->userid=$request->userinfo;
				$user->productslug=$request->productid;
				$user->save();

				$count=count($checkcomparecount)+1;

				return json_encode([
				'status' =>'Success',
				'msg' =>'Added to Compare List',
				'count'=>$count
				]);     
			}
		}
		else
		{
				return json_encode([
				'status' =>'Error',
				'msg' =>'Product Already in Compare List !',
				'count'=>0
				]);  
		}        

	}
	public function getcomparelist(Request $request)
	{
		
		$comparedata = Productcompare::where('userid',32)->get();

		//$slugarray=[];
		foreach($comparedata as $item)
		{
			$slugarray[]=$item->productslug;
		}    


		$productdata=Product::whereIN('products.slug',$slugarray)
					->where('products.is_active','online')
					->join('brands', 'products.brand', '=', 'brands.id')
					->join('parentcategories', 'products.parentcategory', '=', 'parentcategories.id')
					->select('products.id',
							'products.slug',
							'products.modelno',
							'products.name',
							'products.thumbnail',
							'products.shortdescription',
							'products.image1',
							'products.image2',
							'products.price',
							'products.weight',
							'products.sprice',
							'brands.brandname',
							'brands.slug as brandslug',
							'parentcategories.slug as catslug',
							'parentcategories.catname'
							)->get();

		//echo "<pre>";print_r($productdata);die;

		return json_encode([
			'status' =>'Success',
			'msg' =>'Fetched successfully !',
			'data'=>$productdata
		]);
	}

	public function abandancart(Request $request)
	{
		

			$products=$request->productsinfo;
			$subtotal=0;    
			foreach($products as $item )
			{   
				$subtotal+=$item['price']*$item['quantity'];
				$tmporder=new Tmporder;
				//$tmporder->orderid= $orderid;
				$tmporder->slug=$item['slug'];
				$tmporder->orderdate=date('d-m-Y');
				$tmporder->name=$item['name'];
				$tmporder->categoryname=$item['catname'];
				$tmporder->brandname=$item['brandname'];
				$tmporder->maincategoryslug=$item['catslug'];
				$tmporder->thumbnail=$item['image1'];
				$tmporder->quantity=$item['quantity'];
				$tmporder->price=$item['price'];
				$tmporder->sprice=$item['sprice'];
				$tmporder->userid=$request->userid;
				$tmporder->save();
			}

		return "Added";

	}

	public function enquiry(Request $request)
	{
		$products=$request->productsinfo;
		$orderid=rand(1111,9999);
		foreach($products as $item)
		{   
			$cartorder=new Cartorder;
			$cartorder->color=json_encode($item['colors']);
			$cartorder->productid=$item['product_id'];
			$cartorder->thumbnail=$item['product_image'];
			$cartorder->modelno=$item['product_modelno'];
			$cartorder->name=$item['product_name'];
			$cartorder->slug=$item['product_slug'];
			$cartorder->quantity=$item['totalquantity']; 
			$cartorder->customer_email=$request->userinfo['Email'];  
			$cartorder->customer_phone=$request->userinfo['Phone'];
			$cartorder->customer_company=$request->userinfo['Company'];
			$cartorder->customer_name=$request->userinfo['Name'];
			$cartorder->orderid=$orderid;
			$cartorder->save();
		}    
		
		$details = [
            'customer_name' => $request->userinfo['Name'],
            'customer_email' => $request->userinfo['Email'],
            'customer_phone' => $request->userinfo['Phone'],
            'customer_company' =>$request->userinfo['Company'],
            'orderid' =>$orderid,
             ];
  
     $user['to']=$request->userinfo['Email'];
     $headersettings= Header_setting::where('id','1')->first();
     $user['from']=$headersettings->headeremail;
     // User Mail
     Mail::send('mail/enquiry_user_email',  $details, function($message) use ($user) {
        $message->to($user['to'], 'SilverGiftz')->subject('Enquiry Information');
        $message->from($user['from'],'SilverGiftz');
      });

     // Admin Mail to enquiry
      Mail::send('mail/enquiry_admin_email',  $details, function($message) use ($user) {
          $message->to($user['from'], 'SilverGiftz')->subject('Enquiry Information');
          $message->from($user['from'],'SilverGiftz');
        });

     // Admin Mail to Gmail
      Mail::send('mail/enquiry_admin_email',  $details, function($message) use ($user) {
          $message->to("Murtaza.silverpixelz@gmail.com", 'SilverGiftz')->subject('Enquiry Information');
          $message->from($user['from'],'SilverGiftz');
        });

		// // $email=$request->userinfo['Email'];
		// // $cartorder=new Cartorder;
		// // $cartorder->customer_email=$request->userinfo['Email'];
		// // $cartorder->customer_phone=$request->userinfo['Phone'];
		// // $cartorder->customer_company=$request->userinfo['Company'];
		// // $cartorder->customer_name=$request->userinfo['Name'];
		// // $cartorder->save();
      
		 return json_encode([
			'status' =>'Success',
		   
		]);
	}

	 public function contact(Request $request)
	{
		return json_encode([
			'status' =>'Success',
		   
		]);  
	}

	public function mytest(Request $request)
	{
			$colors=['#f26522', '#6ecff6'];

		$products=Product::where('slug','lanyard-with-detachable-buckle-707')
		//->whereRaw("find_in_set($colors,author)")
		->where(function($query) use($colors) {
                        foreach($colors as $term) {
                            $query->orWhere('colors', 'like', "%$term%");
                        };
                    })
		->first();
		echo "<pre>";print_r($products);die;


		return json_encode([
			'products' =>$products,
		   
		]);  
		
	}

	public function headerfilter(Request $request)
	{
			$searchdata=$request->item;
			
			// $columns = Schema::getColumnListing('products');
			// //$columns = ['name'];  
			// //return $columns;

			// $query = Product::query();
			// foreach($columns as $column){
			// 	$query->orWhere($column, 'LIKE', '%' . $searchdata . '%');
			// }
			$products=Product::where('is_active','online')
							->where($searchdata,'online')
							//->join('brands', 'products.brand', '=', 'brands.id')
							//->join('categories', 'products.category', '=', 'categories.id')
							->select('products.id',
							'products.slug',
							'products.name',
							'products.thumbnail',
							'products.modelno',
							'products.brand',
							//'brands.slug as brandslug',
						   //	'categories.slug as catslug',
						   	//'categories.catname'
						   )
						   	->get();

		
			return json_encode(['products' => $products,'searchdata' => $searchdata]);
   
	}

//['#6ecff6', '#f26522', '#ed1c24', '#f06ea9']
	 

	// Portfolio Api
	public function portfolio()
	{
		$portfolio=Portfolio::select('id','name','slug','description','images')->where('is_active','online')->orderBy('ranking','asc')->paginate(19);
		return json_encode($portfolio);
	}

	public function portfolio_detail(Request $request)
	{
		$portfolio_detail=Portfolio::select('name','slug','description','images','event_name','client_name','launch_date','services','location')->where('slug',$request->slug)->first();
		return json_encode($portfolio_detail);
	}

	public function featured_portfolio(Request $request)
	{
		$featured_portfolio=Portfolio::select('name','slug','description','images')
								->where('is_active','online')->where('featured','online')->orderBy('ranking','asc')->get()->take(9);
		return json_encode($featured_portfolio);
	}

}