@php
if(!empty($items->category))
{
    $relatedproducts=\App\Models\Product::where('category',$items->category)->where('is_active',"online")->get()->take(9);

    $categorydata=\App\Models\Category::where('id',$items->category)->get();

    $otherproducts=\App\Models\Product::where('brand','!=',$items->brand)->orderByRaw('RAND()')->get()->take(9); 
}
@endphp
@extends('layouts.frontapp')

@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       @php   
          $breadcrumb_brand=\App\Models\Brand::where('id',$items->brand)->first();
          $breadcrumb_category=\App\Models\Category::where('id',$items->category)->first();
       @endphp
       @if($breadcrumb_brand)
        <li><a href="{{url('brand/'.$breadcrumb_brand->slug)}}">{{$breadcrumb_brand->brandname}}</a></li>
        @endif
        @if(!empty($breadcrumb_category))
         <li><a href="{{url('category/'.$breadcrumb_category->slug)}}">{{$breadcrumb_category->catname}}</a></li>
        @endif
       <li>{{$items->name}}</li>
     </ul>
  </div>
</section>

<form  id="option-choice-form" method="post" class="form-horizontal" enctype="multipart/form-data"> @csrf

        <input type="hidden" name="id" id="id" value="{{$items->id}}"> 

        <input type="hidden" name="modelno" id="modelno" value="{{$items->modelno}}">

        <input type="hidden" name="product_name" id="product_name" value="{{$items->name}}">

        <input type="hidden" name="specification" id="specification" value="{{$items->shortdescription}}">

        <input type="hidden" name="slug" id="slug" value="{{$items->slug}}">

        <input type="hidden" name="thumbnail" id="thumbnail" value="{{$items->thumbnail}}">

        @if(!empty($items->sprice))
        <input type="hidden" name="price" id="price" value="{{$items->sprice}}">
        @else
         <input type="hidden" name="price" id="price" value="{{$items->price}}">
        @endif

        <input type="hidden" name="qty" id="qty" value="">
<section class="default_section productdetail_section">
  <div class="container">
    <div class="default_row">
      <div class="col-md-12 col-lg-6 product_sliderdetail">
          <div class="default_div imgproduct_slider">
            <ul id="vertical" class="">
            @if(!empty($items->image1))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image1) }}">  <img src="{{ URL::asset('upload/product/'.$items->image1) }}"> </li>
            @endif

            @if(!empty($items->image2))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image2) }}">  <img src="{{ URL::asset('upload/product/'.$items->image2) }}"> </li>
            @endif

            @if(!empty($items->image3))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image3) }}">  <img src="{{ URL::asset('upload/product/'.$items->image3) }}"> </li>
            @endif

            @if(!empty($items->image4))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image4) }}">  <img src="{{ URL::asset('upload/product/'.$items->image4) }}"> </li>
            @endif

            @if(!empty($items->image5))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image5) }}">  <img src="{{ URL::asset('upload/product/'.$items->image5) }}"> </li>
            @endif

            @if(!empty($items->image6))  
              <li data-thumb="{{ URL::asset('upload/product/'.$items->image6) }}">  <img src="{{ URL::asset('upload/product/'.$items->image6) }}"> </li>
            @endif
             
            </ul>
          </div>
      </div>
      <div class="col-md-12 col-lg-6 product-descption">
        <div class="default_div product-heading">
          <h2 class="product_name">{{$items->name}}</h2>
          @if(!empty($items->modelno))
          <span class="category_name">Part No : {{$items->modelno}}</span>
          @endif

           @if(!empty($items->sprice))
          <span class="category_name">Price : AED {{$items->sprice}}</span>
          @else
          <span class="category_name">Price : AED {{$items->price}}</span>
          @endif

        </div>
        @if(!empty($items->shortdescription))
            <p>{!! $items->shortdescription !!}</p>
        @else
            <p>No Description Available</p>
        @endif
        <div class="default_div variation-div mt-4">
         @php  
            $variant_name=\App\Models\Product::where('master_product',$items->modelno)->where('is_active',"online")->get();
         @endphp

           @if(count($variant_name)>0)
         
          <p>Variations :</p>
          <div class="default_row otdrbtn">
            @foreach($variant_name as $val)
            <button type="button" class="btn btn-otdr" onclick="getvariant('{{$val->slug}}')">
                 {{$val->variant_name}}
            </button>
           @endforeach
          </div>
         
          @endif


          <div class="default_row mt-3 number_row">
            <div class="number">
              <span class="minus"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
              <input type="text" value="1" name="quantity" id="quantity" onchange="get_qty()" >
              <span class="plus"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>
            <button type="submit" class="cart_btn">Add to cart</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 tabb-info">

      @if( (!empty($items->description)) || (!empty($items->specification))  )
      <div class="default_div tabdiv">
         <div class="tab_div">
            <ul class="default_div tabs">
              @if(!empty($items->description))
              <li class="tab-link current" data-tab="tab-1">Description</li>
              @endif
               @if(!empty($items->specification))
              <li class="tab-link" data-tab="tab-2">Specifications</li>
              @endif
               @if(!empty($items->support_heading1))
              <li class="tab-link" data-tab="tab-3">Product Support</li>
              @endif
            </ul>
        </div>
        <div class="default_div tabinfo-div">
          <div id="tab-1" class="tab-content current">
           
          <p>{!! $items->description !!}</p>
           
         
          </div>
          <div id="tab-2" class="tab-content">
         
            <p>{!! $items->specification !!}</p>
           
          </div>
          <div id="tab-3" class="tab-content">
            
            <p> 
              <a href="{{ URL::asset('upload/documents/'.$items->support_pdf1) }}">
                @if(!empty($items->support_heading1))
                  {{ $items->support_heading1 }} 
                @else
                  {{$items->support_pdf1}}
                @endif
              </a>
           </p>

            <p> 
              <a href="{{ URL::asset('upload/documents/'.$items->support_pdf2) }}">
                @if(!empty($items->support_heading2))
                 {{ $items->support_heading2 }} 
                @else
                  {{$items->support_pdf2}}
                @endif
              </a>
           </p>

            <p> 
              <a href="{{ URL::asset('upload/documents/'.$items->support_pdf3) }}">
                @if(!empty($items->support_headings3))
                  {{ $items->support_heading3 }} 
                @else
                  {{$items->support_pdf3}}
                @endif
              </a>
           </p>
          </div>
      </div>
    </div>
@endif
   @if(!empty($relatedproducts))
    <div class="default_div relateddiv">
        <div class="default_div product-heading">
           <h2 class="product_name">Related Products</h2>
        </div>
        <div class="default_div">
         <div class="relatedproduct_slider owl-carousel">
             @foreach($relatedproducts as $items)

            <div class="default_div product_list">
              <a href="{{url('product/'.$items->slug)}}" class="default_div product-img"><img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="">
                <span class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</span>
              </a>
              <div class="default_div product-info">
              <a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->name}}</a>
              <!-- <span class="category_name">Firecat</span> -->
              <!-- <a href="{{url('product/'.$items->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a> -->
              </div>
            </div>
          @endforeach
          
         </div>
        </div>
      </div>
  @endif
    
 @if(!empty($otherproducts))
    <div class="default_div relateddiv">
        <div class="default_div product-heading">
           <h2 class="product_name">Suggested Products</h2>
        </div>
        <div class="default_div">
         <div class="relatedproduct_slider owl-carousel">
             @foreach($otherproducts as $items)

            <div class="default_div product_list">
              <a href="{{url('product/'.$items->slug)}}" class="default_div product-img"><img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="">
                <span class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</span>
              </a>
              <div class="default_div product-info">
              <a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->name}}</a>
              <!-- <span class="category_name">Firecat</span> -->
              <!-- <a href="{{url('product/'.$items->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a> -->
              </div>
            </div>
          @endforeach
          
         </div>
        </div>
      </div>
  @endif
  
  </div>
</form>


<!-- Modal -->
<form  id="variant-form" method="post" class="form-horizontal" enctype="multipart/form-data"> @csrf

  <input type="hidden" name="id" id="variant_id" value=""> 

  <input type="hidden" name="modelno" id="variant_modelno" value="">

  <input type="hidden" name="product_name" id="variant_product_name" value="">

  <input type="hidden" name="specification" id="variant_specification" value="">

  <input type="hidden" name="slug" id="variant_slug" value="">

  <input type="hidden" name="thumbnail" id="variant_thumbnail" value="">

  <input type="hidden" name="price" id="variant_price" value="">

   <input type="hidden" name="cprice" id="variant_cprice" value="">

  <input type="hidden" name="qty" id="variant_qty" value="">

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="product_title" style="color:#000">
          

        </h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        
       

      </div>

      <div class="modal-body" >
        <span class="category_name" id="shortdescription"></span><hr>
        <span class="category_name" id="product_modalno"></span>
         <span class="category_name" id="master_price"></span>
        <span class="category_name" id="product_price"></span>
        <span class="category_name" id="total_price"></span>
      </div>
      <div class="modal-footer">
         <div class="default_row mt-3 number_row">
            <div class="number">
              <span class="minus"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
              <input type="text" value="1" name="quantity" id="quantity" onchange="get_qty()" >
              <span class="plus"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>
            <button type="submit" class="cart_btn">Add to cart</button>
          </div>
      </div>
    </div>
  </div>
</div>

</section>
</form>



@endsection