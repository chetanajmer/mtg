<?php



namespace App\Http\Controllers\Backend;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;

use App\Models\Category;

use App\Models\Category_filter;

use App\Models\Subcategory;

use App\Models\Parentcategory;

use App\Models\Product;

use App\Models\Brand;

use App\Models\Installation;

use App\Models\Color;

use App\Models\Product_setting;

use App\Models\General_setting;

use App\Models\Counter;

use App\Models\Admin;

use Illuminate\Support\Facades\Redirect;

use ImageOptimizer;

use Image;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Schema;

// use Illuminate\Support\Facades\Input;



class ProductController extends Controller
{
  public function list(Request $request)
  {
    $search_brand=$request->brand;
    $search_category=$request->category;
    $search_subcategory=$request->subcategory;
    $search_name=$request->search;

    $newarrival=DB::table('products')->select('id','slug','name','newarrival','featured','sale','most_selling','thumbnail','category','subcategory','brand','is_active')->where('newarrival','online')->where('is_active','online')->get();

    $hotproduct=DB::table('products')->select('id','slug','name','newarrival','featured','sale','most_selling','thumbnail','category','subcategory','brand','is_active')->where('sale','online')->where('is_active','online')->get();

    $midocean=DB::table('products')->select('id','slug','name','newarrival','featured','sale','most_selling','thumbnail','category','subcategory','brand','is_active')->where('brand','42')->where('is_active','online')->get();

    $sub_products=$newarrival->merge($hotproduct)->merge($midocean);
    foreach($sub_products as $item)
    {
      $sub_products_id[]=$item->id;
    }    

    $remaining=DB::table('products')->select('id','slug','name','newarrival','featured','sale','most_selling','thumbnail','category','subcategory','brand','is_active')->wherenotin('id',$sub_products_id)->get();

    $items_array=$sub_products->merge($remaining)->unique('id');

    //searching
    if( (!empty($search_brand)) && (!empty($search_category))  && (!empty($search_subcategory)) )
    {
      $items_array=  $items_array->where('brand',$search_brand)->where('category',$search_category)->where('subcategory',$search_subcategory);
    }  
    elseif( (!empty($search_brand)) && (!empty($search_category)) )
    {
      $items_array=  $items_array->where('brand',$search_brand)->where('category',$search_category);
    }
    elseif( (!empty($search_brand)) && (!empty($search_subcategory)) )
    {
      $items_array=  $items_array->where('brand',$search_brand)->where('subcategory',$search_subcategory);
    }
    elseif( (!empty($search_category)) && (!empty($search_subcategory)) )
    {
      $items_array= $items_array->where('category',$search_category)->where('subcategory',$search_subcategory);
    }
    elseif(!empty($search_brand))
    {
      $items_array= $items_array->where('brand',$search_brand);
    }
    elseif(!empty($search_category))
    {
      $items_array=  $items_array->where('category',$search_category);
    }
    elseif(!empty($search_subcategory))
    {
      $items_array=  $items_array->where('subcategory',$search_subcategory);
    }
    elseif(!empty($search_name))
    {
      $brands=Brand::select('id')->where('brandname','like','%'.$search_name.'%')->first();
      $pcat=Parentcategory::select('id')->where('catname','like','%'.$search_name.'%')->first();
      $cat=Category::select('id')->where('catname','like','%'.$search_name.'%')->first();
      $subcat=Subcategory::select('id')->where('catname','like','%'.$search_name.'%')->first();
      if(!empty($brands))
      {
        $items_array=$items_array->where('brand',$brands->id);
      }
      elseif(!empty($pcat))
      {
        $items_array=$items_array->where('parentcategory',$pcat->id);
      }
      elseif(!empty($cat))
      {
        $items_array=$items_array->where('category',$cat->id);
      }
      elseif(!empty($subcat))
      {
        $items_array=$items_array->where('subcategory',$subcat->id);
      }
      else
      {
        $columns = Schema::getColumnListing('products');
        $query = Product::query();
        foreach($columns as $column)
        {
          $query->orWhere($column, 'LIKE', '%' . implode('%',explode(' ', $search_name)) . '%');
          $items_array = $query;
        }
      }
    }
    else
    {
    }   

      $items_array=$items_array->paginate(30);


      $parentcategory=Parentcategory::get();

      $category=Category::get();

      $subcategory=Subcategory::get();

      $brand=Brand::get();

    

      return view('backend.product.list-products',compact('items_array','parentcategory','category','subcategory','brand','search_brand','search_category','search_subcategory')); 

    }



    public function index()

    {

      $data=Product::all();

      $count=count($data);

      $start_product=Product_setting::where('id','1')->first();

      $product_limit=$start_product->productlimit;

      if($count<$product_limit)

      {

        $counter =DB::Table('counters')->select('*')->orderByDesc('id')->first();

        if(!empty($counter))

        {

                    

                    $lastid=$counter->counterstartsfrom;

                    $start_product=Product_setting::where('id','1')->first();

                    $startsequence=$start_product->startsfrom;

                    $modelno= $startsequence.($lastid+1);



                    /*$counter=new Counter;

                    $counter->counterstartsfrom=($lastid+1);

                    $counter->save();*/

                  }

                  else

                  {

                    

                    $start_product=Product_setting::where('id','1')->first();

                    $startsequence=$start_product->startsfrom;

                    $modelno= $startsequence."1";

                    /*$counter=new Counter;

                    $counter->counterstartsfrom="1";

                    $counter->save();  */   



                  }  



        }

        else

        {

        

            echo "You have reached you account Limit !";die;

        

        }    



        $brands=Brand::where('is_active','online')->get();

        $categories=Category::where('is_active','online')->get();

        return view('backend.product.add-products',compact('categories','modelno','brands'));

    }



    public function add(Request $request)

    {

       $settings=General_setting::findOrFail(1);

       $sitename=$settings->site_name;

       // dd($request->all()); 

       // echo"<pre>";print_r($_POST);die; 

       $data=Product::all();

       $count=count($data); 

       $start_product=Product_setting::where('id','1')->first();

       $product_limit=$start_product->productlimit;



       if($count>$product_limit)

       {

          $request->session()->flash('error','You have reached you account Limit to add products!');    

          return Redirect::back();

       }



       $product_name=$request->name;

       $modelno=$request->modelno;

       $shortdesc=$request->shortdescription;

       $spec=$request->specification;

       $desc=$request->description;

       $master_product=$request->master_product;

       $variantname=$request->variant_name;

       $slugs=$request->name;

       $heading1=$request->support_heading1;

       $heading2=$request->support_heading2;

       $heading3=$request->support_heading3;

       $pdf1=$request->support_pdf1;

       $pdf2=$request->support_pdf2;

       $pdf3=$request->support_pdf3;

       $brands=$request->brand;

       $cat=$request->category;

       $subcat=$request->subcategory;

       $price=$request->price;

       $sprice=$request->sprice;

       $cprice=$request->cprice;

       // $active=$request->is_active;

       foreach($product_name as $key=>$val){ 

            

            $model_no=$modelno[$key];

            $shortdescription=$shortdesc[$key];

            $specification=$spec[$key];

            $description=$desc[$key];

            $masterproduct=$master_product[$key];

            $variant_name=$variantname[$key];

            $slug=$slugs[$key];

            $support_heading1=$heading1[$key];

            $support_heading2=$heading2[$key];

            $support_heading3=$heading3[$key];

            if(!empty($pdf1))

            {

               $support_pdf1=$pdf1[$key];

            }

            

            if(!empty($pdf2))

            {

               $support_pdf2=$pdf2[$key];

            }

            

            if(!empty($pdf3))

            {

               $support_pdf3=$pdf3[$key];

            }

           

            $price=$price[$key];

            $sprice=$sprice[$key];

            $cprice=$cprice[$key];

            $product=new Product;

            $product->name=$val;

            $product->modelno=$model_no;

            $product->shortdescription=$shortdescription;

            $product->specification=$specification;

            $product->description=$description;

            // $product->is_active=$active;

            if(!empty($masterproduct))

            {

              $product->master_product=$masterproduct;

            }

            if(!empty($variant_name))

            {

              $product->variant_name=$variant_name;

            }



            $rand_num=rand(100,1000);

            $main_slug=$slug."-".$rand_num;

            $slug=preg_replace('/[^A-Za-z0-9\-]/', '-', $main_slug);

            $product->slug=preg_replace('/-+/', '-', $slug);

            $product->support_heading1=$support_heading1;

            $product->support_heading2=$support_heading2;

            $product->support_heading3=$support_heading3;

            if(!empty($support_pdf1))

            {

              $destinationPath = 'upload/documents';

              $fileName1 = rand(11111,99999).'.pdf';

              $support_pdf1->move($destinationPath, $fileName1);

              $product->support_pdf1 = $fileName1;

            }



            if(!empty($support_pdf2))

            {

              $destinationPath = 'upload/documents';

              $fileName2 = rand(11111,99999).'.pdf';

              $support_pdf2->move($destinationPath, $fileName2);

              $product->support_pdf2 = $fileName2;

            }



            if(!empty($support_pdf3))

            {

              $destinationPath = 'upload/documents';

              $fileName3 = rand(11111,99999).'.pdf';

              $support_pdf3->move($destinationPath, $fileName3);

              $product->support_pdf3 = $fileName3;

            }



            $meta=preg_replace('/[^A-Za-z0-9\-]/', '-', $val);

            $meta_product=preg_replace('/-+/', '-', $meta);

            if($model_no!='')

            {

                $product->metatitle=$meta_product.'-'.$model_no;

                $product->metakey=$meta_product.'-'.$model_no;

                $product->metadesc=$meta_product.'-'.$model_no;

            }

            else

            {

                $product->metatitle=$meta_product;

                $product->metakey=$meta_product;

                $product->metadesc=$meta_product;

            }

            

             $product->brand=$brands;

             $product->category=$cat;

             $product->subcategory=$subcat;

             $product->is_active='online';

             $product->price=$price;

             $product->sprice=$sprice;

             $product->cprice=$cprice;



             // echo "<pre>"; print_r($product);die; 



             $product->save();

        }

        $request->session()->flash('success','Saved Successfully !');

        return Redirect::back();



    }





    public function sku_combination(Request $request)
    {

            //returns combinations of customer choice options array
            if (! function_exists('combinations')) {
                function combinations($arrays) {
                    $result = array(array());
                    foreach ($arrays as $property => $property_values) {
                        $tmp = array();
                        foreach ($result as $result_item) {
                            foreach ($property_values as $property_value) {
                                $tmp[] = array_merge($result_item, array($property => $property_value));
                            }
                        }
                        $result = $tmp;
                    }
                    return $result;
                }
            }

            $options = array();
            if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                $colors_active = 1;
                array_push($options, $request->colors);
            }
            else {
                $colors_active = 0;
            }

            $unit_price = $request->price;
            $product_name = $request->name;

            if($request->has('choice_no')){
                foreach ($request->choice_no as $key => $no) {
                    $name = 'choice_options_'.$no;
                    $my_str = implode('|', $request[$name]);
                    array_push($options, explode(',', $my_str));
                }
            }

            $combinations = combinations($options);
            
      
            return view('backend.product.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));

    }

    public function sku_combination_edit(Request $request)
    {
        
        //returns combinations of customer choice options array
        if (! function_exists('combinations')) {
            function combinations($arrays) {
                $result = array(array());
                foreach ($arrays as $property => $property_values) {
                    $tmp = array();
                    foreach ($result as $result_item) {
                        foreach ($property_values as $property_value) {
                            $tmp[] = array_merge($result_item, array($property => $property_value));
                        }
                    }
                    $result = $tmp;
                }
                return $result;
            }
        }

        $product = Product::findOrFail($request->id);

        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->price;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('backend.product.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
        
    }



    public function editproduct($slug,$paginationid)

    {

        $brands=Brand::all();

        $parentcategory=Parentcategory::all();

        $category=Category::all();

        $subcategory=Subcategory::all();

        $items =DB::Table('products')->select('*')->where('slug',$slug)->first();

        $product_id=$items->id;

        $installation=DB::Table('installations')->select('*')->where('product_id',$product_id)->get();

        return view('backend.product.edit-products',compact('items','parentcategory','category','subcategory','brands',

          'installation','paginationid'));

    }

    



    public function updateproduct(Request $request)

    {

        // echo"<pre>"; print_r($_POST);die;

        $settings=General_setting::findOrFail(1);

        $sitename=$settings->site_name;

        $slug=$request->get('slug');


        $specifications=array();
        if(!empty($request->spec_name))
        {
          foreach($request->spec_name as $i=>$specname)
          {
            if(trim($specname)!='')
            {
              $specifications[]=array('name'=>$specname,'value'=>isset($request->spec_value[$i])?$request->spec_value[$i]:'');
            }
          }
        }

        $occasion_names=$this->resolveTagNames($request->occasion_tags,'occasions');

        $preference_names=$this->resolveTagNames($request->preferences,'preferences');


        DB::table('products')

        ->where('slug',$slug)

        ->update([
          'name' => $request->name,

          'subheading' => $request->subheading,

          'rating' => $request->rating,

          'review_count' => $request->review_count,

          'reviews_enabled' => $request->reviews_enabled ? 'yes' : 'no',

          'brand' =>$request->brand,

          'category' => $request->category,

          'subcategory' => $request->subcategory,

          'modelno'=>$request->modelno,

          'sku' =>$request->sku,

          'weight' =>$request->weight,

          'price' => $request->price,

          'sprice' => $request->sprice,

          'date_sale_price_start' => $request->date_sale_price_start,

          'date_sale_price_ends' => $request->date_sale_price_ends,

          'product_warranty' => $request->product_warranty,

          'is_active' => $request->is_active,

          'metatitle' => $request->metatitle,

          'metakey' => $request->metakey,

          'metadesc' => $request->metadesc,

          'title' => $request->title,

          'og_type' => $request->og_type,

          'og_url' => $request->og_url,

          'twitter_card' => $request->twitter_card,

          'twitter_url' => $request->twitter_url,

          'quantity' => $request->quantity,

          'stock' => $request->stock,

          'low_stock' => $request->low_stock,

          'shortdescription' => $request->shortdescription,

          'specification' => $request->specification,

          'specifications' => !empty($specifications)?json_encode($specifications):null,

          'min_order_quantity' => $request->min_order_quantity,

          'moq_unit' => $request->moq_unit,

          'branding_options' => !empty($request->branding_options)?implode(',',$request->branding_options):null,

          'occasion_tags' => !empty($occasion_names)?implode(',',$occasion_names):null,

          'preferences' => !empty($preference_names)?implode(',',$preference_names):null,

          'ai_tags' => $request->ai_tags,

          'description' => $request->description,

          'support_heading1'=>$request->support_heading1,

          'support_heading2'=>$request->support_heading2,

          'support_heading3'=>$request->support_heading3, 

          'refurbished_product' => $request->refurbished_product,  

          'updated_at' => date('Y-m-d H:i:s')

         ]);  

      
        // if(!empty($request->brand))

        // {

        //    $brand=implode(',',$request->brand);

        //      DB::table('products')

        //     ->where('slug',$slug)

        //     ->update(['brand' =>$brand]);

        // }

       



        // if(!empty($request->parentcategory))

        // {

        //    $parentcategory=implode(',',$request->parentcategory);

        //      DB::table('products')

        //     ->where('slug',$slug)

        //     ->update(['parentcategory' =>$parentcategory]);

        // }

       



        // if(!empty($request->category))

        // {

        //    $category=implode(',',$request->category);

        //      DB::table('products')

        //     ->where('slug',$slug)

        //     ->update(['category' =>$category]);

        // }

        



        // if(!empty($request->subcategory))

        // {

        //    $subcategory=implode(',',$request->subcategory);

        //      DB::table('products')

        //     ->where('slug',$slug)

        //     ->update(['subcategory' =>$subcategory]);

        // }

        



        // if(!empty($request->childsubcategory))

        // {

        //    $childsubcategory=implode(',',$request->childsubcategory);

        //      DB::table('products')

        //     ->where('slug',$slug)

        //     ->update(['childsubcategory' =>$childsubcategory]);

        // }

      



        $randomstr1=Str::random(5);

        $imagedata=$request->thumbnailval;





        if(!empty($imagedata))

        {   

            if(!empty($request->oldthumbimage))
            {
              $image_path="upload/product/thumbnail/".$request->oldthumbimage;

              if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            }

            $imageName =  rand().'.'.$imagedata->extension(); 

            $imagedata->move(public_path('upload/product/thumbnail/'),$imageName);

            DB::table('products')

            ->where('slug', $slug)

            ->update(array('thumbnail'=>$imageName));

            //echo $check1;die;

        }





        $image1=$request->image1;

        $image2=$request->image2;

        $image3=$request->image3;

        $image4=$request->image4;

        $image5=$request->image5;

        $image6=$request->image6;

        $randomstr=Str::random(4);



            //image sections  

        $product_name=$request->get('name');

        $productname=preg_replace('/[^A-Za-z0-9\-]/', '', $product_name);



        if(!empty($image1))

        {

            if(!empty($request->oldimage1))

            {

              $image_path="upload/product/".$request->oldimage1;

              if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            } 

            $imageName1 = $productname."-silvergiftz-".$randomstr."-image1".'.'.$request->image1->extension();  

            $image_resize = Image::make($image1->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName1));



            $check2=DB::table('products')

            ->where('slug',$slug)

            ->update(['image1' => $imageName1]);

            //echo $check2;die;

            

        }    



        if(!empty($image2))

        {

            if(!empty($request->oldimage2))

            {

              $image_path="upload/product/".$request->oldimage2;

             	if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            }

            $imageName2 = $productname."-silvergiftz-".$randomstr."-image2".'.'.$request->image2->extension(); 

            $image_resize = Image::make($image2->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName2));



             DB::table('products')

            ->where('slug',$slug)

            ->update(['image2' => $imageName2]);

            

        } 



        if(!empty($image3))

        {

           if(!empty($request->oldimage3))

            {

              $image_path="upload/product/".$request->oldimage3;

             if(is_file($image_path))
        		 {
        	 			unlink($image_path);
        			}

            }

            $imageName3 = $productname."-silvergiftz-".$randomstr."-image3".'.'.$request->image3->extension();   

            $image_resize = Image::make($image3->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName3));



             DB::table('products')

            ->where('slug',$slug)

            ->update(['image3' => $imageName3]);

           // $item->save();

        }

        if(!empty($image4))

        {



            if(!empty($request->oldimage4))

            {

              $image_path="upload/product/".$request->oldimage4;

              if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            }

            $imageName4 = $productname."-silvergiftz-".$randomstr."-image4".'.'.$request->image4->extension();   

            $image_resize = Image::make($image4->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName4));



             DB::table('products')

            ->where('slug',$slug)

            ->update(['image4' => $imageName4]);

           // $item->save();

        }



        if(!empty($image5))

        {

          if(!empty($request->oldimage5))

            {

              $image_path="upload/product/".$request->oldimage5;

              if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            }

            $imageName5 = $productname."-silvergiftz-".$randomstr."-image5".'.'.$request->image5->extension();   

            $image_resize = Image::make($image5->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName5));



             DB::table('products')

            ->where('slug',$slug)

            ->update(['image5' => $imageName5]);

           // $item->save();

        }



        if(!empty($image6))

        {

          if(!empty($request->oldimage6))

            {

              $image_path="upload/product/".$request->oldimage6;

              if(is_file($image_path))
        			{
        	 			unlink($image_path);
        			}

            }

            $imageName6 = $productname."-silvergiftz-".$randomstr."-image6".'.'.$request->image6->extension();   

         

            $image_resize = Image::make($image6->getRealPath());              

            // $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName6));



             DB::table('products')

            ->where('slug',$slug)

            ->update(['image6' => $imageName6]);

           // $item->save();

        } 

        // product video
        if($request->hasFile('video_file'))
        {
          $old_video=DB::table('products')->where('slug',$slug)->value('video');
          if(!empty($old_video) && strpos($old_video,'http')!==0)
          {
            $video_path="upload/product/video/".$old_video;
            if(is_file($video_path))
            {
              unlink($video_path);
            }
          }
          $videoFile=$request->file('video_file');
          $videoName=rand(11111,99999).'.'.$videoFile->getClientOriginalExtension();
          $videoFile->move(public_path('upload/product/video/'),$videoName);
          DB::table('products')->where('slug',$slug)->update(['video'=>$videoName]);
        }
        elseif(!empty($request->video))
        {
          DB::table('products')->where('slug',$slug)->update(['video'=>$request->video]);
        }

        // Bulk Images
        $current_date=date("Y-m-d");
        $current_time=date("H:i:s");
        $bulk_image=$request->bulk_image;
        $old_bulk_image=$request->old_bulk_image;

        if(!empty($old_bulk_image))
        {
           DB::table('products')->where('slug',$slug)->update(['bulk_image' => $old_bulk_image]);
        }


        if(!empty($bulk_image))
        {
          foreach($bulk_image as $img)
          {
            $bulkimageName= $productname."-silvergiftz-silverpixelz-".$current_date."+".$current_time.rand().'.'.$img->getClientOriginalExtension();
            $image_resize = Image::make($img->getRealPath());              
            // $image_resize->resize(800,800);
            $image_resize->save(public_path('upload/product/' .$bulkimageName));
            $imageName[]=$bulkimageName;
          }  
            $bulkimage=implode(',', $imageName);
            if(!empty($old_bulk_image))
            {
               $img=$old_bulk_image.','.$bulkimage;
            }
            else
            {
              $img=$bulkimage;
            }
            DB::table('products')->where('slug',$slug)->update(['bulk_image' => $img]);
        }

        $pdf1=$request->support_pdf1;

       $pdf2=$request->support_pdf2;

       $pdf3=$request->support_pdf3;

      

       if(!empty($pdf1))

       {

          if(!empty($request->oldpdf1))

          {

            $pdf1="upload/documents/".$request->oldpdf1;

            if(is_file($pdf1))
        		{
        	 		unlink($pdf1);
        		}

          }

          $destinationPath = 'upload/documents';

          $fileName1 = rand(11111,99999).'.pdf';

          $request->file('support_pdf1')->move($destinationPath, $fileName1);

          DB::table('products')->where('slug',$slug)->update(['support_pdf1' => $fileName1]);

       }

       if(!empty($pdf2))

       {

          if(!empty($request->oldpdf2))

          {

            $pdf2="upload/documents/".$request->oldpdf2;

            if(is_file($pdf2))
        		{
        	 		unlink($pdf2);
        		}

          }

          $destinationPath = 'upload/documents';

          $fileName2 = rand(11111,99999).'.pdf';

          $request->file('support_pdf2')->move($destinationPath, $fileName2);

          DB::table('products')->where('slug',$slug)->update(['support_pdf2' => $fileName2]);

       }

       if(!empty($pdf3))

       {

         if(!empty($request->oldpdf3))

          {

            $pdf3="upload/documents/".$request->oldpdf3;

            if(is_file($pdf3))
        		{
        	 		unlink($pdf3);
        		}

          }

          $destinationPath = 'upload/documents';

          $fileName3 = rand(11111,99999).'.pdf';

          $request->file('support_pdf3')->move($destinationPath, $fileName3);

          DB::table('products')->where('slug',$slug)->update(['support_pdf3' => $fileName3]);

       }

       // product color
       $product_color=$request->product_colors;
       if(!empty($product_color))
       {
          $colors=implode(",", $product_color);
          DB::table('products')->where('slug',$slug)->update(['colors' => $colors]);
       }
       else
       {
         DB::table('products')->where('slug',$slug)->update(['colors' => '']);
       }
       





        //returns combinations of customer choice options array
        // if (! function_exists('combinations')) {
        //     function combinations($arrays) {
        //         $result = array(array());
        //         foreach ($arrays as $property => $property_values) {
        //             $tmp = array();
        //             foreach ($result as $result_item) {
        //                 foreach ($property_values as $property_value) {
        //                     $tmp[] = array_merge($result_item, array($property => $property_value));
        //                 }
        //             }
        //             $result = $tmp;
        //         }
        //         return $result;
        //     }
        // }


        // if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
        //     //$product->colors = json_encode($request->colors);

        //     DB::table('products')
        //     ->where('slug',$slug)
        //     ->update(['colors' =>json_encode($request->colors)
        //     ]);
        //     //echo "active if";die;
        // }
        // else {
        //     $colors = array();

        //     //$product->colors = json_encode($colors);
        //     DB::table('products')
        //     ->where('slug',$slug)
        //     ->update(['colors' =>json_encode($colors)
        //     ]);

        //     //echo "active else";die;
        // }

        // $choice_options = array();

        // if($request->has('choice')){
        //     foreach ($request->choice_no as $key => $no) {
        //         $str = 'choice_options_'.$no;
        //         $item['name'] = 'choice_'.$no;
        //         $item['title'] = $request->choice[$key];
        //         $item['options'] = explode(',', implode('|', $request[$str]));
        //         array_push($choice_options, $item);
        //     }
        // }

        //$product->choice_options = json_encode($choice_options);
        // DB::table('products')
        //     ->where('slug',$slug)
        //     ->update(['choice_options' =>json_encode($choice_options)
        //     ]);

        

        // $variations = array();

        // //combinations start
        // $options = array();
        // if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
        //     $colors_active = 1;
        //     array_push($options, $request->colors);
        // }

        // if($request->has('choice_no')){
        //     foreach ($request->choice_no as $key => $no) {
        //         $name = 'choice_options_'.$no;
        //         $my_str = implode('|',$request[$name]);
        //         array_push($options, explode(',', $my_str));
        //     }
        // }

        // $combinations = combinations($options);
        // if(count($combinations[0]) > 0){
        //     foreach ($combinations as $key => $combination){
        //         $str = '';
        //         foreach ($combination as $key => $item){
        //             if($key > 0 ){
        //                 $str .= '-'.str_replace(' ', '', $item);
        //             }
        //             else{
        //                 if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
        //                     $color_name = \App\Models\Color::where('code', $item)->first()->name;
        //                     $str .= $color_name;
        //                 }
        //                 else{
        //                     $str .= str_replace(' ', '', $item);
        //                 }
        //             }
        //         }
        //         $item = array();
        //         $item['price'] = $request['price_'.str_replace('.', '_', $str)];
        //         $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
        //         $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
        //         $newimage= $request['img_'.str_replace('.', '_', $str)];
        //         $oldimg=$request['old_img_'.str_replace('.', '_', $str)];

        //         // echo "<pre>";print_r($newimage);die;
                
        //         if(!empty($newimage))
        //         {
        //           foreach($newimage as $key => $value)
        //           {
        //             $imageName1 = rand().'.'.$value->extension();
        //             $image_resize = Image::make($value->getRealPath());              
        //             $image_resize->save(public_path('upload/product/' .$imageName1));
        //             $new_image[]=$imageName1 ; 
        //             $var="oldimg_".$str.$key;
        //             $oldimage[]=$request->$var;
        //             $get_data=Product::where('id',$request->id)->first();
        //             if(isset(json_decode($get_data->variations)->$str->img))
        //             {
        //                $get_image=json_decode($get_data->variations)->$str->img;
        //             }
                   
        //           }

        //           if(!empty($get_image))
        //           {
        //             $search=$oldimage;
        //             $replace=$new_image;
        //             $array=$get_image;
        //             $result=str_replace($search, $replace, $array);
        //             $item['img']=$result;
        //           }
        //           else
        //           {
        //             foreach($newimage as $img)
        //             {
        //               $imageName1 = rand().'.'.$img->getClientOriginalExtension();
        //               $image_resize = Image::make($img->getRealPath());              
        //               // $image_resize->resize(800,800);
        //               $image_resize->save(public_path('upload/product/' .$imageName1));
        //               $item['img'][]=$imageName1;
        //             } 
        //           }
                 
        //         }
        //         else
        //         {
        //           $item['img']=$oldimg;
        //         }
                

        //         // add new image
        //         $image=$request['newimg_'.str_replace('.', '_', $str)];
        //         if(!empty($image))
        //         {
        //           foreach($image as $img)
        //           {
        //             $imageName1 = rand().'.'.$img->getClientOriginalExtension();
        //             $image_resize = Image::make($img->getRealPath());              
        //             // $image_resize->resize(800,800);
        //             $image_resize->save(public_path('upload/product/' .$imageName1));
        //             $item['img'][]=$imageName1;
        //           }   
        //         }


        //         $variations[$str] = $item;
        //     }
        // }
    
        // echo "<pre>"; print_r(json_encode($variations));die;
     
        // DB::table('products')
        //     ->where('slug',$slug)
        //     ->update(['variations' =>json_encode($variations)
        //     ]);

        $installation_title=$request->installation_title;

        $installation_opt_title=$request->installation_opt_title;

        $installation_opt_price=$request->installation_opt_price;

        $product_id=$request->id;



        

        $new_installation_opt_title=$request->new_installation_opt_title;

        $new_installation_opt_price=$request->new_installation_opt_price;



       

        if((!empty($installation_title)) && (!empty($installation_opt_title)) && (!empty($installation_opt_price)) )

        {

           foreach($installation_opt_title as $key=>$val){ 

              $val2 = $installation_opt_price[$key]; 

              $val3=$product_id;

              $val4=$installation_title;

              DB::table('installations')->where('product_id',$val3)->update(['installation_opt_title'=>$val,'installation_opt_price'=>$val2,'installation_title'=>$val4]);  

          }

        }

       



        if((!empty($installation_title)) && (!empty($new_installation_opt_title)) && (!empty($new_installation_opt_price)) )

        {

            foreach($new_installation_opt_title as $key=>$val){ 

            $val2 = $new_installation_opt_price[$key]; 

            $val3=$product_id;

            $val4=$request->installation_title;

            $installation=new Installation;

            $installation->installation_opt_title=$val;

            $installation->installation_opt_price=$val2;

            $installation->product_id=$val3;

            $installation->installation_title=$val4;

            $installation->save();

          }

        }



        if(!empty($request->related_product))

        {

           $related_product=implode(',',$request->related_product);

             DB::table('products')

            ->where('slug',$slug)

            ->update(['related_product' =>$related_product]);

        }

        else

        {

           DB::table('products')

            ->where('slug',$slug)

            ->update(['related_product' =>'']);

        }

        $request->session()->flash('success','Saved Successfully!');

        $username=session()->get('username');
        
        return Redirect::to('listproduct');
        
       

        

    }



    public function deletemyproduct(Request $request)

    { 

      // print_r($_POST);die;
      $slug=$request->slug;
       $record= DB::table('products')->where('slug',$slug)->first();

      if(!empty($record->thumbnail))

      {

        $thumb="upload/product/thumbnail/".$record->thumbnail;

        if(is_file($thumb))
        {
        	 unlink($thumb);
        }

      }

      if(!empty($record->image1))
      {
        $image1="upload/product/".$record->image1;
        if(is_file($image1))
        {
        		unlink($image1); 
        }
       	
      }

      if(!empty($record->image2))
      {
        $image2="upload/product/".$record->image2;
        if(is_file($image2))
        {
        	 unlink($image2);
        }
       
      }

      if(!empty($record->image3))

      {

        $image3="upload/product/".$record->image3;

       if(is_file($image3))
        {
        	 unlink($image3);
        }

      }

      if(!empty($record->image4))

      {

        $image4="upload/product/".$record->image4;

        if(is_file($image4))
        {
        	 unlink($image4);
        }

      }

      if(!empty($record->image5))

      {

        $image5="upload/product/".$record->image5;

        if(is_file($image5))
        {
        	 unlink($image5);
        }

      }

      if(!empty($record->image6))

      {

        $image6="upload/product/".$record->image6;

        if(is_file($image6))
        {
        	 unlink($image6);
        }

      }

      if(!empty($record->support_pdf1))

      {

        $pdf1="upload/documents/".$record->support_pdf1;

        if(is_file($pdf1))
        {
        	 unlink($pdf1);
        }

      }

      if(!empty($record->support_pdf2))

      {

        $pdf2="upload/documents/".$record->support_pdf2;

        if(is_file($pdf2))
        {
        	 unlink($pdf2);
        }

      }

      if(!empty($record->support_pdf3))

      {

        $pdf3="upload/documents/".$record->support_pdf3;

        if(is_file($pdf3))
        {
        	 unlink($pdf3);
        }

      }

        DB::table('products')->where('slug',$slug)->delete();

       
      $category=Category::get();

      $subcategory=Subcategory::get();

      $brand=Brand::get();
      $search_brand=$request->search_brand;
      $search_category=$request->search_category;
      $search_subcategory=$request->search_subcategory;
      $pageid=$request->pageid;
      if( (!empty($search_brand)) && (!empty($search_category))  && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->where('brand',$search_brand)->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_category)) )
      {
        $items_array=  DB::table('products')->select('*')->where('brand',$search_brand)->where('category',$search_category)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->where('brand',$search_brand)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_category)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_brand))
      {
        $items_array=  DB::table('products')->select('*')->where('brand',$search_brand)->paginate(30);
      }
      elseif(!empty($search_category))
      {
        $items_array=  DB::table('products')->select('*')->where('category',$search_category)->paginate(30);
      }
      elseif(!empty($search_subcategory))
      {
        $items_array=  DB::table('products')->select('*')->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_name))
      {
        $columns = Schema::getColumnListing('products');
        $query = Product::query();
        foreach($columns as $column)
        {
          $query->orWhere($column, 'LIKE', '%' . $search_name . '%');
          $items_array = $query->paginate(30);
        }
      }
      else
      {
        $items_array=  DB::table('products')->select('*')->paginate(30);
      }   
      // echo "<pre>"; print_r($items_array);die;

      return view('backend.product.partialproduct',compact('items_array','category','subcategory','brand','search_brand','search_category','search_subcategory','pageid'));

    }



    public function newarrival(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('products')

            ->where('slug',$parent_id)

            ->update(['newarrival' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }


    public function featured(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('products')

            ->where('slug',$parent_id)

            ->update(['featured' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }


    public function sale(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('products')

            ->where('slug',$parent_id)

            ->update(['sale' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }



    public function active(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

        //$catid=(int)$parent_id;

        

            DB::table('products')

            ->where('slug',$parent_id)

            ->update(['is_active' => $mystatus]);



        //print_r($subcategories);

        $msg="updated";

        return $msg;    

    }



    public function most_selling(Request $request)

    {

         

         $parent_id = $request->my_id;

         $mystatus=$request->status;

     

            DB::table('products')

            ->where('slug',$parent_id)

            ->update(['most_selling' => $mystatus]);



        $msg="updated";

        return $msg;    

    }



    

     public function deleteimage1(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image1'];

        if(is_file($image_path))
        {
        	 unlink($image_path);
        }

       

        DB::update('update products set image1=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }

    



    public function deleteimage2(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image2'];

        if(is_file($image_path))
        {
        	 unlink($image_path);
        }

        DB::update('update products set image2=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }

    





    public function deleteimage3(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image3'];

        if(is_file($image_path))
        {
        	 unlink($image_path);
        }

        DB::update('update products set image3=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }

    



    public function deleteimage4(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image4'];

        if(is_file($image_path))
        {
        	 unlink($image_path);
        }

        DB::update('update products set image4=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }

    



    public function deleteimage5(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image5'];

       if(is_file($image_path))
        {
        	 unlink($image_path);
        }

        DB::update('update products set image5=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }

    









    public function deleteimage6(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $image='';

        $image_path="upload/product/".$record['image6'];

       if(is_file($image_path))
        {
        	 unlink($image_path);
        }

        DB::update('update products set image6=? where id=?',[$image,$id]);

        $msg="updated";

        return $msg;     

        }



   public function deletebulkimage(Request $request)
   {
      $id=$request->id;
      $img=$request->img;
      $image_path="upload/product/".$img;
      if(is_file($image_path))
      {
        unlink($image_path);
      }
      $product=Product::where('id',$id)->first();
      $image=str_replace($img, '', $product->bulk_image);
      $arr_img=explode(',',$image);
      $result=array_filter($arr_img);
      $update_img=implode(',',$result);
      DB::table('products')->where('id',$id)->update(['bulk_image' => $update_img]);
    
      // $res=explode(",", $item);
      // DB::update('update products set image6=? where id=?',[$image,$id]);
      // $msg="updated";
      // return $msg;     
    }



    public function deleteproduct_filter(Request $request){



        $id=$request->id;

        $record = Product::find($id);

        $id=$record['id'];

        $filters='';

        DB::update('update products set filters=? where id=?',[$filters,$id]);

        $msg="updated";

        return $msg;   

        }   





    public function add_image(){



      return view('backend.product.add-images');   

    } 



    

    // public function addimage(Request $request)

    // {

    //     // echo "<pre>";print_r($_POST);die;

    //     $product=new Product_image;

    //     $createslug=explode(" ",$request->product_model);

    //     $createslug=implode("-",$createslug);

    //     $rand_num=rand(100,1000);

    //     $slug=$createslug."-".$rand_num;

    //     $thumb_slug=preg_replace('/[^A-Za-z0-9\-]/', '', $slug);

    //     // $product->slug=preg_replace('/[^A-Za-z0-9\-]/', '', $slug);

    //     // $product->slug=$slug;

    //     $product->product_model=$request->product_model;

    //     $imagedata=$request->get('thumbnailval');



    //     if(!empty($imagedata))

    //     {   

    //         $folderPath = public_path('upload/product/thumbnail/');

    //         $image_parts = explode(";base64,", $imagedata);

    //         $image_type_aux = explode("image/", $image_parts[0]);

    //         $image_type = $image_type_aux[1];

    //         $image_base64 = base64_decode($image_parts[1]);

    //         $imageName = $thumb_slug.'.jpg';

    //         $imageFullPath = $folderPath.$imageName;

    //         file_put_contents($imageFullPath, $image_base64);

    //         $product->thumbnail=$imageName;

    //        /// $item->save();



    //     }

        

    //     $image1=$request->image1;

    //     $image2=$request->image2;

    //     $image3=$request->image3;

    //     $image4=$request->image4;

    //     $image5=$request->image5;

    //     $image6=$request->image6;

    //     $randomstr=Str::random(4);



    //     date_default_timezone_set('Asia/Kolkata');  

    //     $current_date=date("Y-m-d");

    //     $current_time=date("H:i:s");



    //     $product_name=$request->get('product_model');

    //     $productname=preg_replace('/[^A-Za-z0-9\-]/', '', $product_name);



    //     if(!empty($image1))

    //     {

    //         $imageName1 = $productname."-meem-industrial-".$current_date."-image1".'.'.$request->image1->extension(); 

    //         $product->image1=$imageName1;

    //         $image_resize = Image::make($image1->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName1));



            

    //     }    



    //     if(!empty($image2))

    //     {

    //         $imageName2 = $productname."-meem-industrial-".$current_date."-image2".'.'.$request->image2->extension();  

    //         $product->image2=$imageName2;

    //         $image_resize = Image::make($image2->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName2));

            

    //     } 



    //     if(!empty($image3))

    //     {

    //        $imageName3 = $productname."-meem-industrial-".$current_date."-image3".'.'.$request->image3->extension();  

    //         $product->image3=$imageName3;

    //         $image_resize = Image::make($image3->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName3));

           

    //     } 



    //     if(!empty($image4))

    //     {

    //        $imageName4 = $productname."-meem-industrial-".$current_date."-image4".'.'.$request->image4->extension();  

    //         $product->image4=$imageName4;

    //         $image_resize = Image::make($image4->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName4));

           

    //     } 

    //     if(!empty($image5))

    //     {

    //         $imageName5 = $productname."-meem-industrial-".$current_date."-image5".'.'.$request->image5->extension();  

    //         $product->image5=$imageName5;

    //         $image_resize = Image::make($image5->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName5));

           

    //     }

    //     if(!empty($image6))

    //     {

    //         $imageName6 = $productname."-meem-industrial-".$current_date."-image6".'.'.$request->image6->extension();  

    //         $product->image6=$imageName6;

    //         $image_resize = Image::make($image6->getRealPath());              

    //         $image_resize->resize(800,800);

    //         $image_resize->save(public_path('upload/product/' .$imageName6));

           

    //     } 



    //       $product->save();

    //     $request->session()->flash('success','Saved Successfully !');

    //     return Redirect::back();



    // }



    public function edit_image($modelno)

    {

        $items =DB::Table('product_images')->select('*')->where('product_model',$modelno)->first();

        return view('backend.product.edit-images',compact('items','modelno'));

    }



    public function addimage(Request $request)

    {

       

       // echo "<pre>";print_r($_POST);die;

        $slug=$request->get('product_model');

        $randomstr1=Str::random(5);

        $imagedata=$request->get('thumbnailval');





        if(!empty($imagedata))

        {   

            $folderPath = public_path('upload/product/thumbnail/');

            $image_parts = explode(";base64,", $imagedata);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $imageName = $randomstr1.'.jpg';

            $imageFullPath = $folderPath.$imageName;

            file_put_contents($imageFullPath, $image_base64);

            //echo $imageName;die;

            



            DB::table('products')

            ->where('modelno', $slug)

            ->update(array('thumbnail'=>$imageName));



            //echo $check1;die;

        }





        $image1=$request->image1;

        $image2=$request->image2;

        $image3=$request->image3;

        $image4=$request->image4;

        $image5=$request->image5;

        $image6=$request->image6;

         $randomstr=Str::random(4);



        date_default_timezone_set('Asia/Kolkata');  

        $current_date=date("Y-m-d");

        $current_time=date("H:i:s");

            //image sections  

        $product_name=$request->get('product_model');

        $productname=preg_replace('/[^A-Za-z0-9\-]/', '', $product_name);



        if(!empty($image1))

        {  

            $imageName1 = $productname."-silvergiftz-".$current_date."+".$current_time."-image1".'.'.$request->image1->extension();  

            $image_resize = Image::make($image1->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName1));



            $check2=DB::table('products')

            ->where('modelno',$slug)

            ->update(['image1' => $imageName1]);

            //echo $check2;die;

            

        }    



        if(!empty($image2))

        {

            

            $imageName2 = $productname."-silvergiftz-".$current_date."+".$current_time."-image2".'.'.$request->image2->extension(); 

           

            $image_resize = Image::make($image2->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName2));



             DB::table('products')

            ->where('modelno',$slug)

            ->update(['image2' => $imageName2]);

            

        } 



        if(!empty($image3))

        {

            $imageName3 = $productname."-silvergiftz-".$current_date."+".$current_time."-image3".'.'.$request->image3->extension();   

         

            $image_resize = Image::make($image3->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName3));



             DB::table('products')

            ->where('modelno',$slug)

            ->update(['image3' => $imageName3]);

           // $item->save();

        }

        if(!empty($image4))

        {

            $imageName4 = $productname."-silvergiftz-".$current_date."+".$current_time."-image4".'.'.$request->image4->extension();   

         

            $image_resize = Image::make($image4->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName4));



             DB::table('products')

            ->where('modelno',$slug)

            ->update(['image4' => $imageName4]);

           // $item->save();

        }



        if(!empty($image5))

        {

            $imageName5 = $productname."-silvergiftz-".$current_date."+".$current_time."-image5".'.'.$request->image5->extension();   

         

            $image_resize = Image::make($image5->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName5));



             DB::table('products')

            ->where('modelno',$slug)

            ->update(['image5' => $imageName5]);

           // $item->save();

        }



        if(!empty($image6))

        {

            $imageName6 = $productname."-silvergiftz-".$current_date."+".$current_time."-image6".'.'.$request->image6->extension();   

         

            $image_resize = Image::make($image6->getRealPath());              

            $image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName6));



             DB::table('products')

            ->where('modelno',$slug)

            ->update(['image6' => $imageName6]);

           // $item->save();

        } 



        

        $request->session()->flash('success','Saved Succesfully!');

        return Redirect::back();

        

    }





    // Single Product

    public function add_products()

    {

        $data=Product::all();

        $count=count($data);

        $start_product=Product_setting::where('id','1')->first();

        $product_limit=$start_product->productlimit;

        if($count<$product_limit)

        {

            $counter =DB::Table('counters')->select('*')->orderByDesc('id')->first();



                  if(!empty($counter))

                  {

                    

                    $lastid=$counter->counterstartsfrom;

                    $start_product=Product_setting::where('id','1')->first();

                    $startsequence=$start_product->startsfrom;

                    $modelno= $startsequence.($lastid+1);



                    // $counter=new Counter;

                    // $counter->counterstartsfrom=($lastid+1);

                    // $counter->save();

                  }

                  else

                  {

                    

                    $start_product=Product_setting::where('id','1')->first();

                    $startsequence=$start_product->startsfrom;

                    $modelno= $startsequence."1";

                    /*$counter=new Counter;

                    $counter->counterstartsfrom="1";

                    $counter->save();  */   



                  }  



        }

        else

        {

        

            echo "You have reached you account Limit !";die;

        

        }    

        

        $brands=Brand::where('is_active','online')->get();

        $categories=Category::where('is_active','online')->get();

        return view('backend.singleproducts.add-products',compact('categories','modelno','brands'));

    }



    // resolves submitted tag names against a master table (case-insensitive, trimmed);
    // creates new master entries when missing and returns the canonical names
    private function resolveTagNames($tags,$table)
    {
        if(is_string($tags)) { $tags = explode(',', $tags); }
        $names=array();
        if(!empty($tags))
        {
          foreach($tags as $tag)
          {
            $tag=trim($tag);
            if($tag=='') continue;
            $existing=DB::table($table)->whereRaw('LOWER(TRIM(name)) = ?', array(strtolower($tag)))->first();
            if(empty($existing))
            {
              DB::table($table)->insert(array('name'=>$tag));
              $names[]=$tag;
            }
            else
            {
              $names[]=$existing->name;
            }
          }
        }
        return $names;
    }

    public function single_product_add(Request $request)
    {
       // echo "<pre>";print_r($_POST);die;

       $settings=General_setting::findOrFail(1);

       $sitename=$settings->site_name;

       $data=Product::all();

       $count=count($data); 

       $start_product=Product_setting::where('id','1')->first();

       $product_limit=$start_product->productlimit;



       if($count>$product_limit)

       {

          $request->session()->flash('error','You have reached you account Limit to add products!');    

          return Redirect::back();

       }



       $product=new Product;

       // if(!empty($request->brand))

       // {

       //    $product->brand=implode(',',$request->brand); 

       // }

       // if(!empty($request->parentcategory))

       // {

       //    $product->parentcategory=implode(',',$request->parentcategory); 

       // }

       // if(!empty($request->category))

       // {

       //    $product->category=implode(',',$request->category); 

       // }

       // if(!empty($request->subcategory))

       // {

       //    $product->subcategory=implode(',',$request->subcategory); 

       // }

       // if(!empty($request->childsubcategory))

       // {

       //    $product->childsubcategory=implode(',',$request->childsubcategory); 

       // }

       $product->brand=$request->brand; 

       $product->category=$request->category;

       $product->subcategory=$request->subcategory;

       $product->name=$request->name;

       $product->subheading=$request->subheading;

       $product->rating=$request->rating;

       $product->review_count=$request->review_count;

       $product->reviews_enabled=$request->reviews_enabled ? 'yes' : 'no';

       $product->sku=$request->sku;

       $product->weight=$request->weight;

       $product->modelno=$request->modelno;

       $product->price=$request->price;

       $product->sprice=$request->sprice;

       $product->date_sale_price_start=$request->date_sale_price_start;

       $product->date_sale_price_ends=$request->date_sale_price_ends;

       $product->product_warranty=$request->product_warranty;

       $product->is_active=$request->is_active;

       $product->metatitle=$request->metatitle;

       $product->metakey=$request->metakey;

       $product->metadesc=$request->metadesc;

        $product->title=$request->title;

        $product->og_type=$request->og_type;

        $product->og_url=$request->og_url;

        $product->twitter_url=$request->twitter_url;

        $product->twitter_card=$request->twitter_card;

       $product->quantity=$request->quantity;

       $product->stock=$request->stock;

       $product->low_stock=$request->low_stock;

       $product->shortdescription=$request->shortdescription;

       $product->shortdescription=$request->shortdescription;

       $product->specification=$request->specification;

       $specifications=array();
       if(!empty($request->spec_name))
       {
          foreach($request->spec_name as $i=>$specname)
          {
            if(trim($specname)!='')
            {
              $specifications[]=array('name'=>$specname,'value'=>isset($request->spec_value[$i])?$request->spec_value[$i]:'');
            }
          }
       }
       $product->specifications=!empty($specifications)?json_encode($specifications):null;

       $product->min_order_quantity=$request->min_order_quantity;

       $product->moq_unit=$request->moq_unit;

       $occasion_names=$this->resolveTagNames($request->occasion_tags,'occasions');

       $product->occasion_tags=!empty($occasion_names)?implode(',',$occasion_names):null;

       $preference_names=$this->resolveTagNames($request->preferences,'preferences');

       $product->preferences=!empty($preference_names)?implode(',',$preference_names):null;

       $product->ai_tags=$request->ai_tags;

       $product->description=$request->description;

       $product->support_heading1=$request->support_heading1;

       $product->support_heading2=$request->support_heading2;

       $product->support_heading3=$request->support_heading3;

       $pdf1=$request->support_pdf1;

       $pdf2=$request->support_pdf2;

       $pdf3=$request->support_pdf3;

      

        if(!empty($pdf1))
        {

          $destinationPath = 'upload/documents';

          $fileName1 = rand(11111,99999).'.pdf';

          $request->file('support_pdf1')->move($destinationPath, $fileName1);

          $product->support_pdf1 = $fileName1;

        }

        if(!empty($pdf2))
        {

          $destinationPath = 'upload/documents';

          $fileName2 = rand(11111,99999).'.pdf';

          $request->file('support_pdf2')->move($destinationPath, $fileName2);

          $product->support_pdf2 = $fileName2;

        }

        if(!empty($pdf3))
        {

          $destinationPath = 'upload/documents';

          $fileName3 = rand(11111,99999).'.pdf';

          $request->file('support_pdf3')->move($destinationPath, $fileName2);

          $product->support_pdf3 = $fileName3;
        }

           

        $createslug=$this->slug($request->name);

        $rand_num=rand(100,1000);

        $slug=$createslug."-".$rand_num;

        $product->slug=$slug;

             

        $randomstr1=Str::random(5);

        $imagedata=$request->thumbnailval;

        if(!empty($imagedata))

        {   

          $imageName =  rand().'.'.$imagedata->extension(); 

          $product->thumbnail=$imageName; 

          $imagedata->move(public_path('upload/product/thumbnail/'),$imageName);

        }



        $image1=$request->image1;

        $image2=$request->image2;

        $image3=$request->image3;

        $image4=$request->image4;

        $image5=$request->image5;

        $image6=$request->image6;

        $randomstr=Str::random(4);



        //image sections  

        $product_name=$request->name;

        $productname=preg_replace('/[^A-Za-z0-9\-]/', '', $product_name);



        if(!empty($image1))

        {  

            $imageName1 = $productname."-silvergiftz-".$randomstr."-image1".'.'.$request->image1->extension();  

            $product->image1=$imageName1;

            $image_resize = Image::make($image1->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName1));   

        }    



        if(!empty($image2))

        {

            

            $imageName2 = $productname."-silvergiftz-".$randomstr."-image2".'.'.$request->image2->extension(); 

            $product->image2=$imageName2;

            $image_resize = Image::make($image2->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName2));    

        } 



        if(!empty($image3))

        {

            $imageName3 = $productname."-silvergiftz-".$randomstr."-image3".'.'.$request->image3->extension();   

            $product->image3=$imageName3;

            $image_resize = Image::make($image3->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName3));

        }

        if(!empty($image4))

        {

            $imageName4 = $productname."-silvergiftz-".$randomstr."-image4".'.'.$request->image4->extension();   

            $product->image4=$imageName4;

            $image_resize = Image::make($image4->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName4));

        }



        if(!empty($image5))

        {

            $imageName5 = $productname."-silvergiftz-".$randomstr."-image5".'.'.$request->image5->extension();   

            $product->image5=$imageName5;

            $image_resize = Image::make($image5->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName5));

        }



        if(!empty($image6))

        {

            $imageName6 = $productname."-silvergiftz-".$randomstr."-image6".'.'.$request->image6->extension();   

            $product->image6=$imageName6;

            $image_resize = Image::make($image6->getRealPath());              

            //$image_resize->resize(800,800);

            $image_resize->save(public_path('upload/product/' .$imageName6));

        } 

        // product video
        if($request->hasFile('video_file'))
        {
          $videoFile=$request->file('video_file');
          $videoName=rand(11111,99999).'.'.$videoFile->getClientOriginalExtension();
          $videoFile->move(public_path('upload/product/video/'),$videoName);
          $product->video=$videoName;
        }
        elseif(!empty($request->video))
        {
          $product->video=$request->video;
        }

        // Bulk Images
        $current_date=date("Y-m-d");
        $current_time=date("H:i:s");
        $bulk_image=$request->bulk_image;
        if(!empty($bulk_image))
        {
          foreach($bulk_image as $img)
          {
            $bulkimageName= $productname."-silvergiftz-silverpixelz-".$current_date."+".$current_time.rand().'.'.$img->getClientOriginalExtension();
            $image_resize = Image::make($img->getRealPath());              
            // $image_resize->resize(800,800);
            $image_resize->save(public_path('upload/product/' .$bulkimageName));
            $bulk_imageName[]=$bulkimageName;
          }  
          $bulkimage=implode(',', $bulk_imageName);
          $product->bulk_image=$bulkimage;
        }
        
        // product image

        if(!empty($request->branding_options))
        {
           $product->branding_options=implode(',', $request->branding_options);
        }

        $product_color=$request->product_color;
        if(!empty($product_color))
        {
           $color=implode(',', $product_color);
           $product->colors=$color;
        }


        if(!empty($request->related_product))

        {

           $product->related_product=implode(',',$request->related_product);

        }

         //returns combinations of customer choice options array
        // if (! function_exists('combinations')) {
        //     function combinations($arrays) {
        //         $result = array(array());
        //         foreach ($arrays as $property => $property_values) {
        //             $tmp = array();
        //             foreach ($result as $result_item) {
        //                 foreach ($property_values as $property_value) {
        //                     $tmp[] = array_merge($result_item, array($property => $property_value));
        //                 }
        //             }
        //             $result = $tmp;
        //         }
        //         return $result;
        //     }
        // }




        // if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0)
        // {
        //     $product->colors = json_encode($request->colors);
        // }
        // else 
        // {
        //     $colors = array();
        //     $product->colors = json_encode($colors);
        // }


        // $choice_options = array();

        // if($request->has('choice')){
        //     foreach ($request->choice_no as $key => $no) {
        //         $str = 'choice_options_'.$no;
        //         $item['name'] = 'choice_'.$no;
        //         $item['title'] = $request->choice[$key];
        //         $item['options'] = explode(',', implode('|', $request[$str]));
                
        //         array_push($choice_options, $item);
        //     }
        // }

         
        //  $product->choice_options = json_encode($choice_options);

        //  $opt=json_encode($choice_options);
        // $variations = array();

        // //combinations start
        // $options = array();
        // if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
        //     $colors_active = 1;
        //     array_push($options, $request->colors);
        // }

        // if($request->has('choice_no')){
        //     foreach ($request->choice_no as $key => $no) {
        //         $name = 'choice_options_'.$no;
        //         $my_str = implode('|',$request[$name]);
        //         array_push($options, explode(',', $my_str));
        //     }
        // }

        // //Generates the combinations of customer choice options
        // $combinations = combinations($options);
        // if(count($combinations[0]) > 0){
        //     foreach ($combinations as $key => $combination){
        //         $str = '';
        //         foreach ($combination as $key => $item){
        //             if($key > 0 ){
        //                 $str .= '-'.str_replace(' ', '', $item);
        //             }
        //             else{
        //                 if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
        //                     $color_name =Color::where('code', $item)->first()->name;
        //                     $str .= $color_name;
        //                 }
        //                 else{
        //                     $str .= str_replace(' ', '', $item);
        //                 }
        //             }
        //         }
        //         $item = array();
              
        //         $item['price'] = $request['price_'.str_replace('.', '_', $str)];
        //         $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
        //         $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
        //         $image=$request['img_'.str_replace('.', '_', $str)];
        //         if(!empty($image))
        //         {
        //             foreach($image as $img)
        //             {
        //               $imageName1 = rand().'.'.$img->getClientOriginalExtension();
        //               $image_resize = Image::make($img->getRealPath());              
        //               // $image_resize->resize(800,800);
        //               $image_resize->save(public_path('upload/product/' .$imageName1));
        //               $item['img'][]=$imageName1;
        //             }   
        //         }
                
                
        //         // $item['img']=$var_img;
        //         $variations[$str] = $item;
       
        //     }


             
        // }
        // //combinations end
        // // print_r($image);
        // // echo "<pre>";print_r(json_encode($variations));die;
        // $product->variations=json_encode($variations);

        $product->refurbished_product=$request->refurbished_product;

        $product->created_at=date('Y-m-d H:i:s');

        $product->updated_at=date('Y-m-d H:i:s');

        $product->save();

        $product_id=$product->id;



        $installation_title=$request->installation_title;

        $installation_opt_title=$request->installation_opt_title;

        $installation_opt_price=$request->installation_opt_price;


        $result=array();

        if(!empty($installation_opt_title))

        {

          foreach($installation_opt_title as $key=>$val)

          { 

            $val2 = $installation_opt_price[$key];

            $val3=$installation_title; 

            $installation=new Installation;

            $installation->installation_opt_title=$val;

            $installation->installation_opt_price=$val2;

            $installation->installation_title=$val3;

            $installation->product_id=$product_id;

            $installation->save();

          }

        }



         $counter =DB::Table('counters')->select('*')->orderByDesc('id')->first();

        if(!empty($counter))

        {

            $lastid=$counter->counterstartsfrom;

            $counter=new Counter;

            $counter->counterstartsfrom=$lastid+1;

            $counter->save();     



        }

        else

        {



            $counter=new Counter;

            $counter->counterstartsfrom="1";

            $counter->save(); 

        }    

        

        $request->session()->flash('success','Saved Successfully !');

        return Redirect::back();



    }



    public function findcategoryforproduct(Request $request)

    {

        $parentcat_id=$request->parentcat_id;

        if(!empty($parentcat_id))

        {

          $category=DB::table('categories')->select('*')->whereIn('parentcat_id',$parentcat_id)->get();

          return response()->json([

          'category' =>  $category

           ]);

        }

        else

        {

          return response()->json([

          'category' =>  "0"

           ]);

        }

        

    }



    public function findsubcategoryforproduct(Request $request)

    {

        $cat_id=$request->cat_id;


        if(!empty($cat_id))

        {

          $subcategory=DB::table('subcategories')->select('*')->where('catid',$cat_id)->get();

          return response()->json([

          'subcategory' =>  $subcategory

           ]);

        }

        else

        {

          return response()->json([

          'subcategory' =>  "0"

           ]);

        }

        

    }



    public function findchildsubcategoryforproduct(Request $request)

    {

        $subcat_id=$request->subcat_id;



        if(!empty($subcat_id))

        {

          $childsubcategory=DB::table('childsubcategories')->select('*')->whereIn('catid',$subcat_id)->get();

          return response()->json([

          'childsubcategory' =>  $childsubcategory

           ]);

        }

        else

        {

          return response()->json([

          'childsubcategory' =>  "0"

           ]);

        }

        

    }



    public function slug($string)

    {

        $string = str_replace(' ', '-', $string);

        $string = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $string)); 

        return preg_replace('/-+/', '-', $string);

    }



    public function delete_installation(Request $request)

    {

        // echo $request->id;die;

        DB::table('installations')->where('id',$request->id)->delete();

        $msg="deleted";

        return $msg; 

    }

    public function appliances(Request $request)
    {
      $cat='1,9';
      $pcat=explode(',',$cat);
      $parentcategory=Parentcategory::get();
      $category=Category::whereIn('parentcat_id',$pcat)->get();
      $brand=Brand::get();
      $subcategory=Subcategory::get();

      // search
      $search_brand=$request->brand;
      $search_category=$request->category;
      $search_subcategory=$request->subcategory;
      $search_name=$request->search;

      if( (!empty($search_brand)) && (!empty($search_category))  && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_category)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('category',$search_category)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_category)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_brand))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->paginate(30);
      }
      elseif(!empty($search_category))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('category',$search_category)->paginate(30);
      }
      elseif(!empty($search_subcategory))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_name))
      {
        $columns = Schema::getColumnListing('products');
        $query = Product::query();
        foreach($columns as $column)
        {
          $query->orWhere($column, 'LIKE', '%' . $search_name . '%');
          $items_array = $query->paginate(30);
        }
      }
      else
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->paginate(30);
      }
      
      return view('backend.product.list-products',compact('items_array','parentcategory','category','subcategory','brand'));
    }

    public function accessories(Request $request)
    {
      $cat='7,2,6';
      $pcat=explode(',',$cat);
      $parentcategory=Parentcategory::get();
      $category=Category::whereIn('parentcat_id',$pcat)->get();
      $subcategory=Subcategory::get();
      $brand=Brand::get();

      // search
      $search_brand=$request->brand;
      $search_category=$request->category;
      $search_subcategory=$request->subcategory;
      $search_name=$request->search;

      if( (!empty($search_brand)) && (!empty($search_category))  && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_category)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('category',$search_category)->paginate(30);
      }
      elseif( (!empty($search_brand)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif( (!empty($search_category)) && (!empty($search_subcategory)) )
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('category',$search_category)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_brand))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('brand',$search_brand)->paginate(30);
      }
      elseif(!empty($search_category))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('category',$search_category)->paginate(30);
      }
      elseif(!empty($search_subcategory))
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->where('subcategory',$search_subcategory)->paginate(30);
      }
      elseif(!empty($search_name))
      {
        $columns = Schema::getColumnListing('products');
        $query = Product::query();
        foreach($columns as $column)
        {
          $query->orWhere($column, 'LIKE', '%' . $search_name . '%');
          $items_array = $query->paginate(30);
        }
      }
      else
      {
        $items_array=  DB::table('products')->select('*')->whereIn('parentcategory',$pcat)->paginate(30);
      }
      return view('backend.product.list-products',compact('items_array','parentcategory','category','subcategory','brand'));
    }

    public function delete_variation_image(Request $request)
    {
    	// echo "<pre>";print_r($_POST);die;
    	$product_id=$request->product_id;
    	$value=explode(',',$request->value);
    	$image=$value[1];
      $variationname=$value[0];
     	$variations='variations->'.$value[0].'->img';
     	// $image_path="upload/product/".$image;
      // if(is_file($image_path))
      // {
      //   unlink($image_path);
      // }
      $get_data=Product::where('id',$request->product_id)->first();
      $get_image=json_decode($get_data->variations)->$variationname->img;

      $update_image=str_replace($image, '', $get_image);
      $result=array_filter($update_image);
      $item=implode(',',$result);
      $res=explode(",", $item);

      print_r($res);die;
     	// DB::table('products')
      //     ->where('id',$product_id)
      //     ->update([$variations=>json_encode($res)]);

      $msg="updated";
      return $msg;
    }

  public function products(Request $request)
    {
      
      if(!empty($request->brand))
      {
        $items_array=Product::orderBy('id','desc')->where('brand',$request->brand)->paginate(30);
      }
      
      else

      {

        $items_array=Product::orderBy('id','desc')->paginate(30);

      }



      $parentcategory=Parentcategory::get();

      $category=Category::get();

      $subcategory=Subcategory::get();

      $brand=Brand::get();

      // echo "<pre>"; print_r($items_array);die;

      return view('backend.product.products',compact('items_array','parentcategory','category','subcategory','brand')); 

    }




}

