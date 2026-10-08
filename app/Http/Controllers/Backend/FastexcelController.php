<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;

use App\Models\Category;

use App\Models\Product;

use App\Models\Subcategory;

use App\Models\Storeuser;

use Illuminate\Support\Facades\Redirect;

use Image;

use Illuminate\Support\Str;

use Rap2hpoutre\FastExcel\FastExcel;

use App\Models\B2enquiry;

class FastexcelController extends Controller



{

  	public $record;

    public $arr;



    public function exportcategory()

    {

      return (new FastExcel(Category::all()))->download('category.xlsx');

    }



    public function importcategory(Request $request)

    {



      $category = (new FastExcel)->import($request->file('category'), function ($line) 

      {



      		if( (empty($line['catname'])) ||  (empty($line['is_active'])) ||  (empty($line['catdescription'])) ||  (empty($line['slug'])) ||  (empty($line['ranking']))  )

      		{

            $arr1=array();

            $this->arr[]=$line;

      			$this->record=array_push($arr1,$this->arr);

      		}	



          

      }); 

      	 

          if(!empty($this->record))

          {

            // echo "error";die;

            $data=$this->arr;

            // echo "<pre>";print_r($data);die;

            return view('backend.fastexcel.category',compact('data'));

          }

          else

          {

              // echo "success";die;

             Category::query()->truncate(); 

            $category = (new FastExcel)->import($request->file('category'), function ($line) {



               return Category::updateorCreate([

                'id'=>$line['id'],

                'catname' => $line['catname'],

                'is_active' => $line['is_active'],

                'catdescription' => $line['catdescription'],

                'catmeta' => $line['catmeta'],

                'image1' => $line['image1'],

                'homethumbnail' => $line['homethumbnail'],

                'bannerimage' => $line['bannerimage'],

                'metatitle' => $line['metatitle'],

                'metakey' => $line['metakey'],

                'metadesc' => $line['metadesc'],

                'slug' => $line['slug'],

                'ranking' => $line['ranking'],

                'featured' => $line['featured'],

                'category_content' => $line['category_content'],

                'created_at' => $line['created_at'],

                'updated_at' => $line['updated_at'],

              ]);



            });



           $request->session()->flash('success','imported successfully !');

           return Redirect::to('listcat');

          }

    }



    public function importsubcategory(Request $request)

    {



      $subcategory = (new FastExcel)->import($request->file('subcategory'), function ($line) 

      {



          if( (empty($line['catname'])) ||  (empty($line['is_active'])) ||  (empty($line['slug'])) ||  (empty($line['ranking']))  )

          {

            $arr1=array();

            $this->arr[]=$line;

            $this->record=array_push($arr1,$this->arr);

          } 



          

      }); 

         

          if(!empty($this->record))

          {

            // echo "error";die;

            $data=$this->arr;

            // echo "<pre>";print_r($data);die;

            return view('backend.fastexcel.subcategory',compact('data'));

          }

          else

          {

              // echo "success";die;

             Subcategory::query()->truncate(); 

            $category = (new FastExcel)->import($request->file('subcategory'), function ($line) {



               return Subcategory::updateorCreate([

          

                'id'=>$line['id'],

                'catid'=>$line['catid'],

                'catname' => $line['catname'],

                'is_active' => $line['is_active'],

                'catdescription' => $line['catdescription'],

                'image1' => $line['image1'],

                'bannerimage' => $line['bannerimage'],

                'metatitle' => $line['metatitle'],

                'metakey' => $line['metakey'],

                'metadesc' => $line['metadesc'],

                'slug' => $line['slug'],

                'ranking' => $line['ranking'],

              

              ]);



            });



           $request->session()->flash('success','Imported successfully !');

           return Redirect::to('listsubcat');

          }

    }



    

    public function exportsubcategory()

    {

      return (new FastExcel(Subcategory::all()))->download('subcategory.xlsx');

    }





    public function exportproduct()

    {

      return (new FastExcel(Product::all()))->download('product.csv');

    }

    public function exportappliances()
  
    {
      $cat='1,9';
      $pcat=explode(',',$cat);
      return (new FastExcel(Product::whereIn('parentcategory',$pcat)->get()))->download('appliances.csv');

    }

    public function exportaccessories()
  
    {
      $cat='7,2,6';
      $pcat=explode(',',$cat);
      return (new FastExcel(Product::whereIn('parentcategory',$pcat)->get()))->download('accessories.csv');

    }


    public function importproduct(Request $request)
    {


      $product = (new FastExcel)->import($request->file('product'), function ($line) {
                  Product::updateOrCreate([
                  'id' => $line['id'],
                  ], 
                [

                'name'=>$line['name'],
                'modelno' => $line['modelno'],
                'sku' => $line['sku'],

                'brand' => $line['brand'],

                'parentcategory'=>$line['parentcategory'],

                'category' => $line['category'],

                'subcategory' => $line['subcategory'],

                'childsubcategory' => $line['childsubcategory'],

                'shortdescription' => $line['shortdescription'],

                'description' => $line['description'],

                'specification' => $line['specification'],

                'price' => $line['price'],

                'sprice' => $line['sprice'],

                'quantity' => $line['quantity'],

                'stock' => $line['stock'],

                'low_stock' => $line['low_stock'],

                'weight' => $line['weight'],

                'ranking' => $line['ranking'],

                'is_active' => $line['is_active'],

                'newarrival' => $line['newarrival'],

                'sale' => $line['sale'],

                'most_selling' => $line['most_selling'],

                'video' => $line['video'],

                'pdf' => $line['pdf'],

                'thumbnail' => $line['thumbnail'],

                'image1' => $line['image1'],

                'image2' => $line['image2'],

                'image3' => $line['image3'],

                'image4' => $line['image4'],

                'image5' => $line['image5'],

                'image6' => $line['image6'],

                'metatitle' => $line['metatitle'],

                'metakey' => $line['metakey'],

                'metadesc' => $line['metadesc'],

                'tax' => $line['tax'],

                'slug' => $line['slug'],

               'master_product' => $line['master_product'],

               'variant_name' => $line['variant_name'],

               'product_warranty' => $line['product_warranty'],

               'installation_title' => $line['installation_title'],

               'installation_opt_title' =>$line['installation_opt_title'],

               'installation_opt_price' => $line['installation_opt_price'],

               'related_product' => $line['related_product'],

               'refurbished_product' => $line['refurbished_product']

              ]);
            });
           $request->session()->flash('success','Imported successfully !');
           return Redirect::to('listproduct');




















      // $product = (new FastExcel)->import($request->file('product'), function ($line) 
      // {
      //   if( (empty($line['name'])) ||  (empty($line['modelno']))  ||  (empty($line['is_active'])) ||  (empty($line['slug'])) ||  (empty($line['ranking'])) ||  (empty($line['thumbnail'])) )
      //   {
      //     $arr1=array();
      //     $this->arr[]=$line;
      //     $this->record=array_push($arr1,$this->arr);
      //   } 
      // }); 

        // if(!empty($this->record))
        // {
          
        //   $data=$this->arr;
         
        //   return view('backend.fastexcel.product',compact('data'));
        // }
        // else
        // {
          
          // Product::query()->truncate(); 
          /*$product = //(new FastExcel)->import($request->file('product'), function ($line) {
              (new FastExcel)->import($request->file('product'), function ($line) {

                Product::updateorCreate(['id'=>$line['id'] ],
               [ */

                /*$product = (new FastExcel)->import($request->file('product'), function ($line) {
                        Product::updateOrCreate([
                            'id' => $line['id'],
                        ], [

                'name'=>$line['name'],

                'modelno' => $line['modelno'],

                'sku' => $line['sku'],

                'brand' => $line['brand'],

                'parentcategory'=>$line['parentcategory'],

                'category' => $line['category'],

                'subcategory' => $line['subcategory'],

                'childsubcategory' => $line['childsubcategory'],

                'shortdescription' => $line['shortdescription'],

                'description' => $line['description'],

                'specification' => $line['specification'],

                'price' => $line['price'],

                'sprice' => $line['sprice'],

                'quantity' => $line['quantity'],

                'stock' => $line['stock'],

                'low_stock' => $line['low_stock'],

                'weight' => $line['weight'],

                'ranking' => $line['ranking'],

                'is_active' => $line['is_active'],

                'newarrival' => $line['newarrival'],

                'sale' => $line['sale'],

                'most_selling' => $line['most_selling'],

                'video' => $line['video'],

                'pdf' => $line['pdf'],

                'thumbnail' => $line['thumbnail'],

                'image1' => $line['image1'],

                'image2' => $line['image2'],

                'image3' => $line['image3'],

                'image4' => $line['image4'],

                'image5' => $line['image5'],

                'image6' => $line['image6'],

                'metatitle' => $line['metatitle'],

                'metakey' => $line['metakey'],

                'metadesc' => $line['metadesc'],

                'tax' => $line['tax'],

                'slug' => $line['slug'],

               'master_product' => $line['master_product'],

               'variant_name' => $line['variant_name'],

               'product_warranty' => $line['product_warranty'],

               'installation_title' => $line['installation_title'],

               'installation_opt_title' =>$line['installation_opt_title'],

               'installation_opt_price' => $line['installation_opt_price'],

               'related_product' => $line['related_product']

              ]);
            });
           $request->session()->flash('success','Imported successfully !');
           return Redirect::to('listproduct');*/

          //}

    }


    public function bulkdeleteproducts(Request $request)
    {

      return view('backend.fastexcel.deleteproduct');

    }
    public function dobulkdeleteproducts(Request $request)
    {
          //echo "working";die;

        $users = (new FastExcel)->import($request->file('product'), function ($line) {
                Product::where('modelno',$line['modelno'])->delete();
                  
        });

        $request->session()->flash('success','Products Deleted');
        return Redirect::back();
  

    }

   
    public function exportstoreuser()
    {
      return(new FastExcel(Storeuser::all()))->download('storeuser.xlsx', function ($user) {
      return [

          'Name' => $user->fname . $user->lname,
          'Email' => $user->email,
      ];
        });
    }

    public function exportb2benquiry()
    {
      return (new FastExcel(B2enquiry::all()))->download('b2benquiry.xlsx');
    }

}



