<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Midocean_product;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Color;

class Midocean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addproduct:midocean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add midocean products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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

        // echo "<pre>";echo $response;die;
        $data=json_decode($response);
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
                         $allcolors=implode(',', $color);   
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
                            $colors=implode(',', $main_color);
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
       $this->info('Added Successfully');
    }
}
