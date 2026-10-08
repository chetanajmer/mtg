@php   

$brands=\App\Models\Brand::get();

$parentcategory=\App\Models\Parentcategory::get();

$category=\App\Models\Category::get();

$subcategory=\App\Models\Subcategory::get();

$childsubcategory=\App\Models\Childsubcategory::get();

$variation=\App\Models\Category_filter::orderBy('id', 'asc')->get();

$product=\App\Models\Product::all();

$color=\App\Models\Color::all();

$branding_options=\App\Models\Branding_option::get();

$occasion_masters=\App\Models\Occasion::orderBy('name')->get();

$preference_masters=\App\Models\Preference::orderBy('name')->get();

$occasion_master_names=$occasion_masters->pluck('name')->toArray();

$preference_master_names=$preference_masters->pluck('name')->toArray();

$sel_occasions=!empty($items->occasion_tags)?explode(',', $items->occasion_tags):array();

$sel_preferences=!empty($items->preferences)?explode(',', $items->preferences):array();

$tier_prices=\DB::table('product_tier_prices')->where('product_id',$items->id)->orderBy('min_qty')->orderBy('id')->get();

$master_product=\App\Models\Product::where('master_product', '=', '')->orWhereNull('master_product')->get();

@endphp


@php

if(!empty(json_decode($items->variations))) 
{
$variationstatus="on";
}
else
{
$variationstatus="off";    
}
@endphp




@if(Session::has('role'))



@else



<script>



window.location.href = "{{url('/admin')}}";</script>



</script>   



@endif

@extends('layouts.app')







@section('content')







            <!-- ============================================================== -->



            <!-- Start right Content here -->



            <!-- ============================================================== -->                      



<div class="content-page">

                <!-- Start content -->

    <div class="content">

        <div class="container">

            <!-- Page-Title -->

            <div class="row">

                <div class="col-sm-12">

                    <h4 class="page-title">Edit Product</h4>

                        <ol class="breadcrumb"></ol>

                </div>

            </div>

            <div class="row">

                <div class="col-sm-12">

                    <form action="{{ route('updateproduct') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">{{ csrf_field() }}

                        <input type="hidden" name="paginationid" value="{{$paginationid}}">

                        <input type="hidden" name="slug" value="{{$items->slug}}">

                        <input type="hidden" name="id" value="{{$items->id}}" id="product_id">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Supplier/Category</b></h5>

                                        <div class="row">

                                            <div class="col-lg-4" style="padding-left:15px;padding-right: 15px">

                                                <div class="form-group m-b-20" >

                                                    <label>Supplier</label>

                                                      <select class="brand form-control select2" id="brand" name="brand"> 
                                                        <option value="">Select Brand</option>
                                                        @foreach($brands as $item)
                                                          <option value="{{$item->id}}" @if($item->id==$items->brand) selected='' @endif>{{$item->brandname}}</option>
                                                        @endforeach
                                                      </select>

                                                </div>

                                            </div>

                                            <!-- <div class="col-lg-3" style="padding-left:15px;padding-right: 15px">

                                                <div class="form-group m-b-20" >

                                                    <label>Parent Categories</label>

                                                        <select name="parentcategory[]" class="form-control select2" id="parentcategory_select" multiple="">

                                                            @foreach($parentcategory as $item)

                                                                 @php $pcat=explode(',',$items->parentcategory) @endphp

                                                                <option value="{{$item->id}}" {{(in_array($item->id,$pcat))? 'selected' : ''}}>{{$item->catname}}</option>

                                                            @endforeach

                                                        </select>

                                                </div>

                                            </div> -->

                                            <div class="col-lg-4" style="padding-left:15px;padding-right: 15px">

                                                <div class="form-group m-b-20" >

                                                    <label>Categories</label>

                                                        <select name="category" class="form-control select2" id="category_select" >
                                                          <option value="">Select Category</option>
                                                            @foreach($category as $item)
                                                                <option value="{{$item->id}}" @if($item->id==$items->category) selected='' @endif>{{$item->catname}}</option>
                                                            @endforeach

                                                        </select>

                                                </div>

                                            </div>

                                            <div class="col-lg-4" style="padding-left:15px;padding-right: 15px">

                                                <div class="form-group m-b-20" >

                                                    <label>Subcategories</label>

                                                      <select name="subcategory" class="form-control select2" id="subcategory_select">
                                                         <option value="">Select Subcategory</option>
                                                        @foreach($subcategory as $item)
                                                            <option value="{{$item->id}}" @if($item->id==$items->subcategory) selected='' @endif>{{$item->catname}}</option>
                                                        @endforeach

                                                      </select> 

                                                </div>

                                            </div>

                                        <div class="row">

                                            

                                            </div>

                                            <!-- <div class="col-lg-3 col-md-offset-1">

                                                <div class="form-group m-b-20" >

                                                    <label>Child Subcategories</label>

                                                        <select name="childsubcategory[]" class="form-control select2" id="childsubcategory_select" multiple="">

                                                            @foreach($childsubcategory as $item)

                                                                 @php $childsubcat=explode(',',$items->childsubcategory) @endphp

                                                                <option value="{{$item->id}}" {{(in_array($item->id,$childsubcat))? 'selected' : ''}}>{{$item->catname}}</option>

                                                            @endforeach

                                                        </select>

                                                </div>

                                            </div> -->

                                        </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>

                                     <!--  <div class="form-group m-b-20">

                                        <input type="checkbox" name="refurbished_product" id="refurbished_product" value="yes" @if($items->refurbished_product=="yes") checked @endif>

                                        <label for="refurbished_product">Refurbished Product</label>

                                      </div> -->

                                        <div class="form-group m-b-20">

                                            <label>Product name <span class="text-danger">*</span></label>

                                                <input type="text" required name="name" id="productname" class="form-control" placeholder="e.g : Apple iMac" value="{{$items->name}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Subheading <span class="text-danger">*</span></label>

                                                <input type="text" required name="subheading" class="form-control" placeholder="Supporting headline below product name" value="{{$items->subheading}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                          <label>Model No</label>

                                          <input type="text"  class="form-control" placeholder="SW1000" name="modelno" readonly="" value="{{$items->modelno}}">

                                        </div>

                                          <div class="form-group m-b-20" >
                                            <label>Product Color</label>
                                            <select  name="product_colors[]" class="selectpicker" multiple data-style="btn-white">
                                              @php 
                                                if(!empty($items->colors))
                                                {
                                                   $product_color=explode(',',$items->colors);
                                                }
                                              @endphp

                                              @if(!empty($items->colors))
                                                @foreach($color as $val)
                                                  <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                  </option>   
                                                @endforeach  
                                              @else
                                                @foreach($color as $val)
                                                  <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                  </option>   
                                                @endforeach  
                                              @endif
                                                                  
                                            </select>  
                                          </div>

                                          <div class="form-group m-b-20" >
                                            <label>Branding Options</label>
                                            <select  name="branding_options[]" class="selectpicker" multiple data-style="btn-white">
                                              @php
                                                $selected_branding = !empty($items->branding_options) ? explode(',', $items->branding_options) : array();
                                              @endphp
                                              @foreach($branding_options as $bo)
                                                <option value="{{ $bo->name }}" @if(in_array($bo->name, $selected_branding)) selected @endif>{{ $bo->name }}
                                                </option>
                                              @endforeach
                                            </select>
                                          </div>

                                       <!--  <div class="form-group m-b-20">

                                            <label>SKU</label>

                                                <input type="text"  class="form-control" placeholder="TF1001" name="sku" value="{{$items->sku}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Weight</label>

                                            <input type="number"  step="any" class="form-control" placeholder="2.5" value ="{{$items->weight}}" name="weight">
                                            <p style="color:blue">Please Enter Weight in kg. (Eg. For 2.5 Kg enter 2.5)</p>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Price <span class="text-danger">*</span></label>

                                                <input type="number" required name="price" class="form-control" value="{{$items->price}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Sale Price </label>

                                                <div class="row">

                                                   <div class="col-md-10">

                                                        <input type="number" name="sprice" class="form-control" value="{{$items->sprice}}">

                                                    </div>

                                                     @if( (!empty($items->date_sale_price_start)) &&(!empty($items->date_sale_price_ends)) )

                                                    <div class="col-md-2" style="padding-top: 8px">

                                                        <a href="javascript:void(0);" style="text-decoration: underline;" id="hide_schedule">Cancel</a>

                                                         <a href="javascript:void(0);" style="text-decoration: underline;display: none" id="show_schedule">Schedule</a>

                                                    </div>

                                                    @else

                                                     <div class="col-md-2" style="padding-top: 8px">

                                                        <a href="javascript:void(0);" style="text-decoration: underline;" id="show_schedule">Schedule</a>

                                                        <a href="javascript:void(0);" style="text-decoration: underline;display: none" id="hide_schedule">Cancel</a>

                                                    </div>

                                                    @endif

                                                </div>

                                        </div>

                                        @if( (!empty($items->date_sale_price_start) )&&(!empty($items->date_sale_price_ends)) )

                                        <div class="form-group m-b-20" id="sale_date_div">

                                            <label>Sale Price Dates</label>

                                                <div class="row">

                                                   <div class="col-lg-6">

                                                        From: <input type="date" name="date_sale_price_start" class="form-control" value="{{$items->date_sale_price_start}}" id="sale_price_start">

                                                    </div>

                                                    <div class="col-lg-6">

                                                        To: <input type="date"  name="date_sale_price_ends" class="form-control" value="{{$items->date_sale_price_ends}}" id="sale_price_ends">

                                                    </div>

                                                </div>

                                        </div>

                                        

                                        @else



                                          <div class="form-group m-b-20" id="sale_date_div" style="display: none">

                                            <label>Sale Price Dates</label>

                                                <div class="row">

                                                   <div class="col-lg-6">

                                                        From: <input type="date" name="date_sale_price_start" class="form-control" value="{{$items->date_sale_price_start}}" id="sale_price_start">

                                                    </div>

                                                    <div class="col-lg-6">

                                                        To: <input type="date"  name="date_sale_price_ends" class="form-control" value="{{$items->date_sale_price_ends}}" id="sale_price_ends">

                                                    </div>

                                                </div>

                                            </div>

                                        

                                        @endif

                                        <div class="form-group m-b-20">

                                            <label>Product Warranty</label>

                                                <select class="form-control select2" name="product_warranty">

                                                    <option value="">Select Warranty</option>

                                                    <option value="No Warranty" @if($items->product_warranty=="No Warranty")selected=""@endif>No Warranty</option>

                                                    <option value="1 Month Warranty" @if($items->product_warranty=="1 Month Warranty")selected=""@endif>1 Month Warranty</option>

                                                    <option value="2 Month Warranty"@if($items->product_warranty=="2 Month Warranty")selected=""@endif>2 Month Warranty</option>

                                                    <option value="3 Month Warranty"@if($items->product_warranty=="3 Month Warranty")selected=""@endif>3 Month Warranty</option>

                                                    <option value="6 Month Warranty" @if($items->product_warranty=="6 Month Warranty")selected=""@endif>6 Month Warranty</option>

                                                    <option value="1 Year Warranty" @if($items->product_warranty=="1 Year Warranty")selected=""@endif>1 Year Warranty</option>

                                                    <option value="2 Year Warranty" @if($items->product_warranty=="2 Year Warranty")selected=""@endif>2 Year Warranty</option>

                                                </select>

                                        </div> -->

                                        <div class="form-group m-b-20">

                                            <label class="m-b-15">Status <span class="text-danger">*</span></label>  <br/>

                                                <div class="radio radio-inline">

                                                    <input type="radio" id="inlineRadio1" value="online" name="is_active" @if($items->is_active=="online")checked=""@endif>

                                                        <label for="inlineRadio1"> Online </label>

                                                </div>

                                                <div class="radio radio-inline">

                                                    <input type="radio" id="inlineRadio2" value="offline" name="is_active" @if($items->is_active=="offline")checked=""@endif>

                                                        <label for="inlineRadio2"> Offline </label>

                                                </div>

                                        </div>

                                </div>

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Ratings &amp; Reviews</b></h5>

                                        <div class="form-group m-b-20">

                                            <label>Rating</label>

                                                <input type="number" step="0.1" min="0" max="5" class="form-control" placeholder="4.5" name="rating" value="{{$items->rating}}">

                                            <p style="color:blue">Enter rating between 0.0 - 5.0 (Eg. 4.5)</p>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Review Count</label>

                                                <input type="number" min="0" class="form-control" placeholder="120" name="review_count" value="{{$items->review_count}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <input type="checkbox" name="reviews_enabled" id="reviews_enabled" value="yes" @if($items->reviews_enabled=="yes" || empty($items->reviews_enabled)) checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small">

                                            <label for="reviews_enabled"> Enable Reviews</label>

                                        </div>

                                </div>

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0"><b>Product Thumbnail Image</b></h5>

                                    <p>Image Size Should be 300px X 300px</p>

                                    @if($items->brand!='42')

                                      @if($items->thumbnail)

                                      <img id="imagethumbpreview" class="img-rounded" src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                    @else

                                      <img id="imagethumbpreview" class="img-rounded" src="{{URL::asset('assets/images/upload.png')}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">

                                    @endif

                                    <input type="hidden" name="oldthumbimage" value="{{$items->thumbnail}}">

                                    <input type="file" id="thumbnailval" name="thumbnailval" class=""  >

                                    @else

                                    <img id="imagethumbpreview" class="img-rounded" src="{{$items->thumbnail}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                    @endif


                                </div>  

                                 <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>

                                        <p>Image size should be 650px X 650px </p>
                                        @if($items->brand!='42')
                                          @if(!empty($items->bulk_image))
                                            <div class="row" style="margin-bottom: 10px;">
                                              @php
                                                $bulkimg=explode(',',$items->bulk_image)
                                              @endphp

                                              @foreach($bulkimg as $img)
                                              <div class="col-lg-4">
                                                <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/product/'.$img) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;border: 1px solid #ddd">  
                                                <center> 
                                                  <a style="background-color:white; position: absolute; top:-5px; right:49px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;" onclick="deletebulkimage('{{$img}}')"  >X</a> 
                                                </center>
                                              </div>
                                              @endforeach
                                            </div>
                                            @endif
                                            <input type="file" id="bulk_image" name="bulk_image[]" class=""  multiple="">
                                             <input type="hidden" id="bulk_image" name="old_bulk_image" class="" value="{{$items->bulk_image}}"  >
                                            
                                          @else

                                              @if(!empty($items->bulk_image))
                                            <div class="row" style="margin-bottom: 10px;">
                                              @php
                                                $bulkimg=explode(',',$items->bulk_image)
                                              @endphp

                                              @foreach($bulkimg as $img)
                                              <div class="col-lg-4">
                                                <img id="imagepreview1" class="img-rounded" src="{{$img}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;border: 1px solid #ddd">  
                                              </div>
                                              @endforeach
                                            </div>
                                            @endif


                                          @endif
                                            

                                        </div>

                                 <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Video</b></h5>

                                        @if(!empty($items->video))
                                        <div class="form-group m-b-20">
                                          <label>Current Video</label>
                                          <p>
                                          @if(Str::startsWith($items->video,'http'))
                                            <a href="{{$items->video}}" target="_blank">{{$items->video}}</a>
                                          @else
                                            <a href="{{URL::asset('upload/product/video/'.$items->video)}}" target="_blank">{{$items->video}}</a>
                                          @endif
                                          </p>
                                        </div>
                                        @endif

                                        <div class="form-group m-b-20">

                                            <label>Video URL</label>

                                                <input type="text" name="video" class="form-control" placeholder="e.g : https://youtube.com/watch?v=..." value="@if(Str::startsWith($items->video,'http')){{$items->video}}@endif">

                                            <p style="color:blue">Paste a video URL or upload a video file below.</p>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Upload Video File</label>

                                            <input type="file" name="video_file" accept="video/*">

                                            <p style="color:blue">If both are provided, the uploaded file will be used.</p>

                                        </div>

                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Meta Data</b></h5>

                                        <div class="form-group m-b-20">

                                            <label>Meta title</label>

                                                <input type="text" name="metatitle" class="form-control" placeholder="Enter title" value="{{$items->metatitle}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Meta Keywords</label>

                                                <input type="text" name="metakey" class="form-control" placeholder="Enter keywords" value="{{$items->metakey}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Meta Description </label>

                                                <textarea class="form-control" name="metadesc" rows="5" placeholder="Please enter description">{{$items->metadesc}}</textarea>

                                        </div>

                                        <div class="form-group m-b-20">
                                          <label>Title</label>
                                          <input type="text" class="form-control" name="title" placeholder="Enter title" value="{{$items->title}}" >
                                        </div>

                                        <div class="form-group m-b-20">
                                          <label>OG Type</label>
                                          <input type="text" class="form-control" name="og_type" placeholder="Enter Og Type" value="{{$items->og_type}}" >
                                        </div>
                                        
                                        <div class="form-group m-b-20">
                                          <label>Og Url</label>
                                          <input type="text" class="form-control" name="og_url" placeholder="Enter Og Url" value="{{$items->og_url}}" >
                                        </div>

                                        <div class="form-group m-b-20">
                                          <label>Twitter Card</label>
                                          <input type="text" class="form-control" name="twitter_card" placeholder="Enter Twitter Card" value="{{$items->twitter_card}}" >
                                        </div>

                                        <div class="form-group m-b-20">
                                          <label>Twitter Url</label>
                                          <input type="text" class="form-control" name="twitter_url" placeholder="Enter Twitter Url" value="{{$items->twitter_url}}" >
                                          </div>
                                        </div>

                                </div>

                               <!--  <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0"><b>Inventory</b></h5>

                                        <div class="form-group m-b-20">

                                            <label>Quantity<span class="text-danger"></span></label>

                                                <input type="number" class="form-control" name="quantity" value="{{$items->quantity}}">

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label class="m-b-15">Stock </label><br/>

                                                <div class="radio radio-inline">

                                                    <input type="radio" id="radio1" value="1" name="stock" @if($items->stock=='1')checked="" @endif>

                                                        <label for="radio1"> In Stock </label><br>

                                                </div>

                                                <div class="radio radio-inline">

                                                    <input type="radio" id="radio2" value="0" name="stock" @if($items->stock=='0')checked=""@endif>

                                                    <label for="radio2"> Out of Stock </label>

                                                </div>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Low Stock<span class="text-danger"></span></label>

                                                <input type="number" class="form-control" name="low_stock" value="{{$items->low_stock}}">

                                        </div>

                                </div> -->  

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Minimum Order Quantity (MOQ)</b></h5>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-group m-b-20">

                                                    <label>Minimum Order Quantity</label>

                                                        <input type="number" min="0" class="form-control" name="min_order_quantity" placeholder="e.g : 50" value="{{$items->min_order_quantity}}">

                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group m-b-20">

                                                    <label>Unit</label>

                                                        <input type="text" class="form-control" name="moq_unit" value="{{$items->moq_unit ? $items->moq_unit : 'Pieces'}}">

                                                </div>

                                            </div>

                                        </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Tier Pricing</b></h5>

                                        <div class="form-group m-b-20">

                                            <input type="checkbox" name="tier_pricing_enabled" id="tier_pricing_enabled" value="yes" @if($items->tier_pricing_enabled=="yes") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small">

                                            <label for="tier_pricing_enabled"> Enable Tier Pricing for this product</label>

                                        </div>

                                        <div id="tier_pricing_body" @if($items->tier_pricing_enabled!="yes") style="display:none;" @endif>

                                            <div class="row" style="font-weight:bold;margin-bottom:10px;">

                                                <div class="col-md-2">Min Qty</div>

                                                <div class="col-md-2">Max Qty</div>

                                                <div class="col-md-3">Price / Piece</div>

                                                <div class="col-md-3">Savings</div>

                                                <div class="col-md-2"></div>

                                            </div>

                                            <div id="tier_rows">

                                                @if(!empty($tier_prices))

                                                    @foreach($tier_prices as $tier)

                                                    <div class="row tier-row" style="margin-bottom:10px;">

                                                        <div class="col-md-2"><input type="number" min="1" name="tier_min[]" class="form-control" value="{{$tier->min_qty}}" placeholder="e.g : 50"></div>

                                                        <div class="col-md-2"><input type="number" min="1" name="tier_max[]" class="form-control" value="{{$tier->max_qty}}" placeholder="Unlimited"></div>

                                                        <div class="col-md-3"><input type="number" step="0.01" min="0" name="tier_price[]" class="form-control" value="{{$tier->price}}" placeholder="e.g : 45"></div>

                                                        <div class="col-md-3 tier-savings" style="padding-top:8px;">-</div>

                                                        <div class="col-md-2"><a href="javascript:void(0);" class="tier-remove btn btn-danger btn-sm" title="Remove">X</a></div>

                                                    </div>

                                                    @endforeach

                                                @endif

                                            </div>

                                            <a href="javascript:void(0);" id="add_tier_row" class="btn btn-purple btn-sm waves-effect waves-light">Add Tier</a>

                                            <p style="color:blue;margin-top:10px;">Leave Max Qty blank for "Unlimited". Savings % is auto-calculated against the base product price.</p>

                                        </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-box">

                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Occasions, Preferences &amp; Search Tags</b></h5>

                                        <div class="form-group m-b-20">

                                            <label>Occasion Tags</label>

                                            <input type="text" name="occasion_tags" class="form-control" data-role="tagsinput" value="{{ $items->occasion_tags }}" placeholder="e.g : Eid Gifts, Corporate Gifts">

                                            <div style="margin-top:8px;">

                                              @foreach($occasion_masters as $o)

                                                <a href="javascript:void(0);" class="tag-quick-add label label-info" data-field="occasion_tags" style="margin-right:5px;display:inline-block;margin-bottom:5px;">{{ $o->name }}</a>

                                              @endforeach

                                            </div>

                                            <p style="color:blue">Click an occasion above to add it, or type a new one and press Enter.</p>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>Preferences</label>

                                            <input type="text" name="preferences" class="form-control" data-role="tagsinput" value="{{ $items->preferences }}" placeholder="e.g : Eco Friendly, Premium Look">

                                            <div style="margin-top:8px;">

                                              @foreach($preference_masters as $p)

                                                <a href="javascript:void(0);" class="tag-quick-add label label-info" data-field="preferences" style="margin-right:5px;display:inline-block;margin-bottom:5px;">{{ $p->name }}</a>

                                              @endforeach

                                            </div>

                                            <p style="color:blue">Click a preference above to add it, or type a new one and press Enter.</p>

                                        </div>

                                        <div class="form-group m-b-20">

                                            <label>AI / Search Tags</label>

                                            <input type="text" name="ai_tags" class="form-control" data-role="tagsinput" value="{{ $items->ai_tags }}" placeholder="e.g : corporate bottle, executive gift">

                                            <p style="color:blue">Free-form tags for AI/search indexing. Not visible to customers.</p>

                                        </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">

                                

                               

                            </div>

                            

                                </div>

                             <!--    <div class="row">

                                    <div class="col-lg-12">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Support</b></h5>

                                                <div class="row">

                                                    <div class="col-lg-4">

                                                        <label>Heading </label>

                                                        <input type="text"  name="support_heading1" class="form-control" placeholder="eg: Heading" ><br>

                                                        <label>Upload Pdf </label><br>

                                                        <div class="row">

                                                          @if(!empty($items->support_pdf1))

                                                          <div class="col-lg-6">

                                                             <input type="text" class="form-control" value="{{$items->support_pdf1}}" readonly="">

                                                          </div>

                                                          @endif

                                                          <div class="col-lg-6">

                                                            <input type="file" name="support_pdf1" accept=".pdf">

                                                            <input type="hidden" name="oldpdf1" value="{{$items->support_pdf1}}" > 

                                                          </div> 

                                                        </div>   

                                                    </div>

                                                    <div class="col-lg-4">

                                                    <label>Heading </label>

                                                        <input type="text"  name="support_heading2" class="form-control" placeholder="eg: Heading" ><br>

                                                        <label>Upload Pdf </label>

                                                        <div class="row">

                                                          @if(!empty($items->support_pdf2))

                                                          <div class="col-lg-6">

                                                             <input type="text" class="form-control" value="{{$items->support_pdf2}}" readonly="">

                                                          </div>

                                                          @endif

                                                          <div class="col-lg-6">

                                                            <input type="file" name="support_pdf2" accept=".pdf">

                                                            <input type="hidden" name="oldpdf2" value="{{$items->support_pdf2}}" > 

                                                          </div> 

                                                        </div>   

                                                    </div>

                                                    <div class="col-lg-4">

                                                    <label>Heading </label>

                                                        <input type="text"  name="support_heading3" class="form-control" placeholder="eg: Heading" ><br>

                                                        <label>Upload Pdf </label>

                                                        <div class="row">

                                                          @if(!empty($items->support_pdf3))

                                                          <div class="col-lg-6">

                                                             <input type="text" class="form-control" value="{{$items->support_pdf3}}" readonly="">

                                                          </div>

                                                          @endif

                                                          <div class="col-lg-6">

                                                            <input type="file" name="support_pdf3" accept=".pdf">

                                                            <input type="hidden" name="oldpdf3" value="{{$items->support_pdf3}}" > 

                                                          </div> 

                                                        </div>   

                                                    </div>

                                                </div>

                                        </div>    

                                    </div>

                                </div>  --> 

                                <div class="row">

                                    <div class="col-lg-12">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5>

                                                <textarea class="summernote form-control" name="specification">{!! $items->specification !!}</textarea> 

                                                <hr>

                                                <label><b>Specification Builder</b></label>

                                                <p style="color:blue">Add specification name and value pairs. Rows can be reordered and will be shown as a table on the product page.</p>

                                                @php
                                                  $spec_rows = !empty($items->specifications) ? json_decode($items->specifications, true) : array();
                                                  if(empty($spec_rows)) { $spec_rows = array(array('name'=>'','value'=>'')); }
                                                @endphp

                                                <div id="spec_builder">

                                                  @foreach($spec_rows as $spec_row)

                                                  <div class="row spec-row" style="margin-bottom:10px;">

                                                    <div class="col-md-4">

                                                      <input type="text" name="spec_name[]" class="form-control" placeholder="e.g : Capacity" value="{{ $spec_row['name'] }}">

                                                    </div>

                                                    <div class="col-md-4">

                                                      <input type="text" name="spec_value[]" class="form-control" placeholder="e.g : 500ml" value="{{ $spec_row['value'] }}">

                                                    </div>

                                                    <div class="col-md-4">

                                                      <a href="javascript:void(0);" class="spec-up btn btn-default btn-sm" title="Move up"><i class="fa fa-arrow-up"></i></a>

                                                      <a href="javascript:void(0);" class="spec-down btn btn-default btn-sm" title="Move down"><i class="fa fa-arrow-down"></i></a>

                                                      <a href="javascript:void(0);" class="spec-remove btn btn-danger btn-sm" title="Remove">X</a>

                                                    </div>

                                                  </div>

                                                  @endforeach

                                                </div>

                                                <a href="javascript:void(0);" id="add_spec_row" class="btn w-sm btn-default waves-effect waves-light m-t-10">Add Specification</a>

                                        </div>    

                                    </div>

                                </div>  

                                <div class="row">

                                    <div class="col-lg-12">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Short Description</b></h5>

                                            <textarea  class="summernote form-control" name="shortdescription" rows="5" placeholder="Please enter description">{!! $items->shortdescription !!}</textarea>

                                        </div>    

                                    </div>

                                </div>    

                                <div class="row">

                                    <div class="col-lg-12">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Description</b></h5>

                                                <textarea class="summernote form-control" name="description">{!! $items->description !!} </textarea> 

                                        </div>    

                                    </div>

                                </div>  

                             <!--    <div class="row">

                                    <div class="col-lg-6">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Installation Button</b></h5>

                                            <label>Title</label>



                                                <input type="text" name="installation_title" class="form-control" value="@if(!empty($installation[0]->installation_title)){{$installation[0]->installation_title}}@endif">

                                              

                                             

                                                <br>

                                                    <div class="field_wrapper">

                                                        <div class="row form-elements">

                                                              @foreach ($installation as $item) 

                                                                <div class="col-md-5" style="margin-bottom:20px">

                                                                    <input type="text" name="installation_opt_title[]" class="form-control" placeholder="Enter an option" value="{{$item->installation_opt_title}}">

                                                                </div>

                                                                <div class="col-md-5" style="margin-bottom:20px">

                                                                    <input type="number" name="installation_opt_price[]" class="form-control" placeholder="0.00" value="{{$item->installation_opt_price}}">

                                                                </div>

                                                                <div class="col-md-2" style="padding-top: 8px">

                                                                    <a href="javascript:void(0);" class="remove-option" title="Remove field" onclick="delete_installation('{{$item->id}}')">X</a>

                                                                </div><br>

                                                              @endforeach

                                                            <br>

                                                        </div>

                                                        <br>

                                                    </div>

                                                    <a href="javascript:void(0);" class="add_button btn w-sm btn-default waves-effect waves-light" title="Add field" >Add Option</a>

                                        </div>    

                                    </div>

                                    <div class="col-lg-6">

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Related Products</b></h5>

                                                <label>Select Products</label>

                                                    <select class="form-control select2" multiple name="related_product[]">

                                                        <option value="">Select Product</option>

                                                        @foreach($product as $product)

                                                         @php $related_product=explode(',',$items->related_product) @endphp

                                                        <option value="{{$product->id}}" {{(in_array($product->id,$related_product))? 'selected' : ''}}>{{$product->name}}</option>

                                                        @endforeach

                                                    </select>

                                        </div>   

                                    </div>

                                </div>   -->

                                 <!-- @if(!empty(json_decode($items->variations))) 

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card-box">


                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Colors</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <select id="colors" name="colors[]" class="selectpicker" multiple data-style="btn-white">
                                                           @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)

                                                            @php 
                                                                if( strpos($color->code, ',') !== false ) 
                                                                {
                                                                   $var=explode(',',$color->code) ;
                                                                   $color1=$var[0];
                                                                   $color2=$var[1];
                                                            @endphp
                                                                <option style="background: linear-gradient(
                                                        90deg,{{$color1}} 50%,{{$color2}} 50%) " value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($items->colors))) echo 'selected'?>>{{ $color->name }}</option>
                                                             @php   }
                                                        else
                                                        { @endphp 

                                                                <option style="background-color:{{ $color->code }} " value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($items->colors))) echo 'selected'?>>{{ $color->name }}</option>
                                                          @php    } @endphp
                                                            @endforeach
                                                        </select>
                                                    </div>    
                                                       
                                                    <div class="col-md-3">
                                                        
                                                        <label>Enable/Disable Color</label>
                                                        <input type="checkbox"   value="1" type="checkbox" name="colors_active" data-plugin="switchery" data-color="#f05050"   data-size="small" <?php if(count(json_decode($items->colors)) > 0) echo "checked";?>/>
                                                
                                                    </div>    
                                                </div>    
                                                <br>
                                                @if(!empty($items->variations))

                                          
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="customer_choice_options" id="customer_choice_options" >
                                                            
                                                            @foreach (json_decode($items->choice_options) as $key => $choice_option)
                                                            
                                                            <div class="row remove1">
                                                                <div class="col-lg-2">
                                                                    <div class="form-group">
                                                                    <input type="hidden" name="choice_no[]" id="choiceval" value="{{ explode('_', $choice_option->name)[1] }}">
                                                                    <input type="text" class="form-control" name="choice[]" value="{{ $choice_option->title }}" placeholder="Choice Title">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-8">
                                                                    <div class="tags-default">
                                                                    <input type="text" class="form-control" name="choice_options_{{ explode('_', $choice_option->name)[1] }}[]" placeholder="Enter choice values" value="{{ implode(',', $choice_option->options) }}" data-role="tagsinput" onchange="update_sku()">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    
                                                                   <button type="button" onclick="delete_row(this)"  class="btn btn-danger btn-rounded waves-effect waves-light">Delete</button>
                                                             
                                                                </div>
                                                            </div>
                                                             @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card-box">


                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>Colors</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <select id="colors" name="colors[]" class="selectpicker" multiple data-style="btn-white">
                                                                   @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)

                                                            @php 
                                                                if( strpos($color->code, ',') !== false ) 
                                                                {
                                                                   $var=explode(',',$color->code) ;
                                                                   $color1=$var[0];
                                                                   $color2=$var[1];
                                                            @endphp
                                                                <option style="background: linear-gradient(
                                                        90deg,{{$color1}} 50%,{{$color2}} 50%) " value="{{ $color->code }}">{{ $color->name }}</option>
                                                             @php   }
                                                        else
                                                        { @endphp 

                                                                <option style="background-color:{{ $color->code }} " value="{{ $color->code }}">{{ $color->name }}</option>
                                                          @php    } @endphp
                                                            @endforeach
                                                                </select>
                                                            </div>    
                                                               
                                                            <div class="col-md-3">
                                                            
                                                               
                                                                <label>Enable/Disable Color</label>
                                                                <input type="checkbox"   value="1" type="checkbox" name="colors_active" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                             
                                                            
                                                        
                                                            </div>    
                                                        </div>    
                                                        <br>  
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="customer_choice_options" id="customer_choice_options" ></div>
                                                            </div>
                                                        </div>         
                                                        <br>   
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-warning btn-rounded waves-effect waves-light" onclick="add_more_customer_choice_option()">Add More Choice</button>
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">&nbsp;</div>
                                                        </div>
                                                         <hr>   
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                               <div class="sku_combination" id="sku_combination"></div>
                                                            </div>
                                                        </div>   
                                                    </div>        

                                                </div>  
                                                @endif   

                                                <br>  
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-warning btn-rounded waves-effect waves-light" onclick="add_more_customer_choice_option()">Add More Choice</button>
                                                        
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                                 <hr>   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       <div class="sku_combination" id="sku_combination"></div>
                                                    </div>
                                                </div>   
                                            </div>        

                                        </div> 

                                        @else

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Variations</b></h5>


                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Colors</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <select id="colors" name="colors[]" class="selectpicker" multiple data-style="btn-white">
                                                            @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)

                                                            @php 
                                                                if( strpos($color->code, ',') !== false ) 
                                                                {
                                                                   $var=explode(',',$color->code) ;
                                                                   $color1=$var[0];
                                                                   $color2=$var[1];
                                                            @endphp
                                                                <option style="background: linear-gradient(
                                                        90deg,{{$color1}} 50%,{{$color2}} 50%) " value="{{ $color->code }}">{{ $color->name }}</option>
                                                             @php   }
                                                        else
                                                        { @endphp 

                                                                <option style="background-color:{{ $color->code }} " value="{{ $color->code }}">{{ $color->name }}</option>
                                                          @php    } @endphp
                                                            @endforeach
                                                        </select>
                                                    </div>    
                                                       
                                                    <div class="col-md-3">
                                                    
                                                      
                                                        <label>Enable/Disable Color</label>
                                                        <input type="checkbox"   value="1" type="checkbox" name="colors_active" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                     
                                                    
                                                
                                                    </div>    
                                                </div>    
                                                <br>  
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="customer_choice_options" id="customer_choice_options" ></div>
                                                    </div>
                                                </div>         
                                                <br>   
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-warning btn-rounded waves-effect waves-light" onclick="add_more_customer_choice_option()">Add More Choice</button>
                                                        
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                                 <hr>   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       <div class="sku_combination" id="sku_combination"></div>
                                                    </div>
                                                </div>   
                                            </div>        

                                        </div>  

                                        @endif -->

                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="text-center p-20">

                                            <button type="Submit" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                        </div>

                                    </div>

                                </div>

                    </form>

            </div>

        </div>

    </div> <!-- container -->

</div> <!-- content -->











@push('header-scripts')



<style>

   .remove-option{

    color: #2271b1;

    border-color: #2271b1;

    background: #dfe7e7;

    vertical-align: top;

    padding: 7px;

}

</style>











@endpush















@push('custom-scripts')







<script>



                $('.summernote').summernote({



                    height: 250,                 // set editor height



                    minHeight: null,             // set minimum height of editor



                    maxHeight: null,             // set maximum height of editor



                    focus: false                 // set focus to editable area after initializing summernote



                });



                



                $('.inline-editor').summernote({



                    airMode: true            



                });







</script>



<!-- Parent Category -->

<script>

$('#parentcategory_select').on('change',function(e) {



    

    var parentcat_id= $("#parentcategory_select").val();



     $.ajax({



                url:"{{ route('findcategoryforproduct') }}",



                method:"POST",



                data: {"_token": "{{ csrf_token() }}","parentcat_id": parentcat_id},



                beforeSend: function(){

                    $("#loading").show();

                  },



                success:function (data) 



                {

                    $("#loading").hide();

                    console.log(data);

                   

                    var totalcount=data.category.length;

                    if(totalcount>0)

                    {   

                        $('#category_select').empty();

                        $('#category_select').append('<option value="">Select Category</option>');

                        $.each(data.category,function(index,category){

                        $('#category_select').append('<option value="'+category.id+'">'+category.catname+'</option>');

                        })   

                    }



                    else



                    {

                        

                      $('#category_select').empty();

                      $('#category_select').append('<option value="">There is No Categories !</option>');                      

                    }   

                    

                    

                      

                }

            })



});

</script> 





<!-- Category -->



<script>

    $('#category_select').on('change',function(e) {



         var cat_id= $("#category_select").val();

         $.ajax({



                url:"{{ route('findsubcategoryforproduct') }}",



                method:"POST",



                data: {"_token": "{{ csrf_token() }}","cat_id": cat_id},



                beforeSend: function(){

                    $("#loading").show();

                  },



                success:function (data) 



                {

                    $("#loading").hide();

                    console.log(data);

                   

                    var totalcount=data.subcategory.length;

                    if(totalcount>0)

                    {   

                        $('#subcategory_select').empty();

                        $('#subcategory_select').append('<option value="">Select Subcategory</option>');

                        $.each(data.subcategory,function(index,subcategory){

                        $('#subcategory_select').append('<option value="'+subcategory.id+'">'+subcategory.catname+'</option>');

                        })   

                    }



                    else



                    {

                        

                      $('#subcategory_select').empty();

                      $('#subcategory_select').append('<option value="">There is No Subcategories !</option>');                      

                    }   

                    

                    

                      

                }

            })

    });



</script>



<!-- Subcategory -->

<!-- <script>

    $('#subcategory_select').on('change',function(e) {



         var subcat_id= $("#subcategory_select").val();

         $.ajax({



                url:"{{ route('findchildsubcategoryforproduct') }}",



                method:"POST",



                data: {"_token": "{{ csrf_token() }}","subcat_id": subcat_id},



                beforeSend: function(){

                    $("#loading").show();

                  },



                success:function (data) 



                {

                    $("#loading").hide();

                    console.log(data);

                   

                    var totalcount=data.childsubcategory.length;

                    if(totalcount>0)

                    {   

                        $('#childsubcategory_select').empty();

                        $('#childsubcategory_select').append('<option value="">Select Child Subcategory</option>');

                        $.each(data.childsubcategory,function(index,childsubcategory){

                        $('#childsubcategory_select').append('<option value="'+childsubcategory.id+'">'+childsubcategory.catname+'</option>');

                        })   

                    }



                    else



                    {

                        

                      $('#childsubcategory_select').empty();

                      $('#childsubcategory_select').append('<option value="">There is No Child Subcategories !</option>');                      

                    }   

                    

                    

                      

                }

            })

    });



</script> -->



<script>

$("#thumbnailval").on("change", function () {



            if (typeof ($("#thumbnailval")[0].files) != "undefined") 



            {



                var size = parseFloat($("#thumbnailval")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagethumbpreview").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });

  // $("body").on("change", ".image", function(e){



    $("#files1").on("change", function () {



            if (typeof ($("#files1")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files1")[0].files[0].size / 1024).toFixed(2);



           

                    var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview1").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);



            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });







    $("#files2").on("change", function () {



            if (typeof ($("#files2")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files2")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview2").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);



            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });











    $("#files3").on("change", function () {



            if (typeof ($("#files3")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files3")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview3").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });



    $("#files4").on("change", function () {



            if (typeof ($("#files4")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files4")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview4").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });







    $("#files5").on("change", function () {



            if (typeof ($("#files5")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files5")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview5").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });





    $("#files6").on("change", function () {



            if (typeof ($("#files6")[0].files) != "undefined") 



            {



                var size = parseFloat($("#files6")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview6").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });



</script>

<script>

    $('#show_schedule').on('click',function(e){



        $('#sale_date_div').show();

        $('#hide_schedule').show();

         $('#show_schedule').hide();



    });



    $('#hide_schedule').on('click',function(e){



        $('#sale_date_div').hide();

        $('#hide_schedule').hide();

        $('#show_schedule').show();

        $("input[type=date]").val('');

    });



</script>

<script type="text/javascript">



    $(document).ready(function () {



       var x = 1;



      var addButton =$('.add_button'); 



      var wrapper = $('.field_wrapper'); 



      var fieldHTML ='<div class="row form-elements"><div class="col-md-5"><input type="text" name="new_installation_opt_title[]" class="form-control" placeholder="Enter an option"></div><div class="col-md-5"><input type="number" name="new_installation_opt_price[]" class="form-control" placeholder="0.00"></div><div class="col-md-2" style="padding-top: 8px"><a href="javascript:void(0);" class="remove_button remove-option" title="Add field" >X</a></div></div><br>';

      

      $(addButton).click(function () {

          x++; 

          $(wrapper).append(fieldHTML);

        

        });

          

      $(wrapper).on('click', '.remove_button', function (e) {

        e.preventDefault();

        $(this).parent().closest(".form-elements").remove();

        x--;

      });





            

    });



</script>

<script>

    function deleteimage1($id)

    {

       

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage1') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }



    function deleteimage2($id)

    {

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage2') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }



    function deleteimage3($id)

    {

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage3') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }



    function deleteimage4($id)

    {

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage4') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }



    function deleteimage5($id)

    {

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage5') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }







    function deleteimage6($id)

    {

     $.ajax({

            type:"POST",

            url:"{{ route('deleteimage6') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":$id

            },

            success:function (data)

            {

                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }


function deletebulkimage($img)
{
   // alert($img);
   var id=$('#product_id').val();
     $.ajax({

            type:"POST",

            url:"{{ route('deletebulkimage') }}",

            method:"POST",

            data: {"_token": "{{ csrf_token() }}","id":id,"img":$img

            },

            success:function (data)

            {
                // console.log(data);
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");

                location.reload();

            } 

        });

    }


  

</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>

function delete_installation($id)

{

     swal({

              title: "Are you sure?",

              text: "Once deleted, you will not be able to recover it!",

              icon: "warning",

              buttons: true,

              dangerMode: true,

            })

            .then((willDelete) => {

              if (willDelete) {

             $.ajax({

                    type:"POST",

                    url:"{{route('delete_installation')}}",

                    method:"POST",

                    data: {"_token": "{{ csrf_token() }}","id":$id

                    },

                    success:function (data)

                    {

                        console.log(data);

                        $.Notification.notify('success','top right', 'Success ', "Installation Deleted Successfully !");

                        $( ".field_wrapper" ).load(window.location.href + " .field_wrapper" );

                    } 

                });

              } else {

                

              }

            });

}

</script>
<script>
$('#radio1').on('change',function(e) 
{
  $('#variant_name').hide();
  $('#color').hide();
  $('#master_product').hide();
});

$('#radio2').on('change',function(e) 
{
  $('#variant_name').show();
  $('#color').show();
  $('#master_product').show(); 
});
</script>
<script type="text/javascript">
   
   $( window ).load(function() {
        
             update_sku();
    });
    
    function add_more_customer_choice_option(){
        
        var i = $('input[name="choice_no[]"').last().val();
    if(isNaN(i)){
        i =0;
    }


        i++;
      
        $('#customer_choice_options').append('<div class="row remove1"><div class="col-lg-2"><div class="form-group"><input type="hidden" name="choice_no[]" class="form-control" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="" placeholder="Choice Title"></div></div><div class="col-lg-8"><div class="tags-default"><input type="text" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div></div><div class="col-md-2"><button onclick="delete_row(this)"  class="btn btn-danger btn-rounded waves-effect waves-light">Delete</button></div></div>');
      
        $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
    }




    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')){
            $('#colors').prop('disabled', true);
        }
        else{
            $('#colors').prop('disabled', false);
        }
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="price"]').on('keyup', function() {
        update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.remove1').remove();
        update_sku();
    }

    function delete_tr(em){
        $(em).closest('tr').remove();
        //update_sku();
    }
  


    function update_sku(){

        var variations_status='{{$variationstatus}}';

        if(variations_status=="on")
        {   
            $.ajax({
            type:"POST",
            url:'{{ route('sku_combination_edit') }}',
            data:$('#choice_form').serialize(),
            success: function(data)
            {

               console.log(data);
               $('#sku_combination').html(data);
            }
            });
        }
        else
        {
            $.ajax({
            type:"POST",
            url:'{{ route('sku_combination') }}',
            data:$('#choice_form').serialize(),
            success: function(data)
            {

               console.log(data);
               $('#sku_combination').html(data);
            }
            });
        }    
    }

    
</script>

<script>
function delete_variation_image($value)
{
  var product_id=$('#product_id').val();
  $.ajax({
           type:"POST",
           url:"{{ route('delete_variation_image') }}",
           method:"POST",
           data: {"_token": "{{ csrf_token() }}","value":$value,"product_id" : product_id
           },
          success:function (data)
          {
            console.log(data);
            // $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
            // location.reload();
          } 
       });
}
</script>

<script type="text/javascript">

    var specRowHtml = '<div class="row spec-row" style="margin-bottom:10px;"><div class="col-md-4"><input type="text" name="spec_name[]" class="form-control" placeholder="e.g : Capacity"></div><div class="col-md-4"><input type="text" name="spec_value[]" class="form-control" placeholder="e.g : 500ml"></div><div class="col-md-4"><a href="javascript:void(0);" class="spec-up btn btn-default btn-sm" title="Move up"><i class="fa fa-arrow-up"></i></a> <a href="javascript:void(0);" class="spec-down btn btn-default btn-sm" title="Move down"><i class="fa fa-arrow-down"></i></a> <a href="javascript:void(0);" class="spec-remove btn btn-danger btn-sm" title="Remove">X</a></div></div>';

    $('#add_spec_row').on('click', function(){
        $('#spec_builder').append(specRowHtml);
    });

    $(document).on('click', '.spec-remove', function(){
        $(this).closest('.spec-row').remove();
    });

    $(document).on('click', '.spec-up', function(){
        var row = $(this).closest('.spec-row');
        row.prev('.spec-row').before(row);
    });

    $(document).on('click', '.spec-down', function(){
        var row = $(this).closest('.spec-row');
        row.next('.spec-row').after(row);
    });

    var tierRowHtml = '<div class="row tier-row" style="margin-bottom:10px;"><div class="col-md-2"><input type="number" min="1" name="tier_min[]" class="form-control" placeholder="e.g : 50"></div><div class="col-md-2"><input type="number" min="1" name="tier_max[]" class="form-control" placeholder="Unlimited"></div><div class="col-md-3"><input type="number" step="0.01" min="0" name="tier_price[]" class="form-control" placeholder="e.g : 45"></div><div class="col-md-3 tier-savings" style="padding-top:8px;">-</div><div class="col-md-2"><a href="javascript:void(0);" class="tier-remove btn btn-danger btn-sm" title="Remove">X</a></div></div>';

    function tierBasePrice(){
        var p = parseFloat($('input[name="price"]').val());
        return (!isNaN(p) && p > 0) ? p : 0;
    }

    function recalcTierSavings(){
        var base = tierBasePrice();
        $('.tier-row').each(function(){
            var tp = parseFloat($(this).find('input[name="tier_price[]"]').val());
            var cell = $(this).find('.tier-savings');
            if(base > 0 && !isNaN(tp) && tp > 0 && tp < base){
                cell.text(Math.round((base - tp) / base * 100) + '%');
            } else {
                cell.text('-');
            }
        });
    }

    function toggleTierBody(){
        if($('input[name="tier_pricing_enabled"]').is(':checked')){
            $('#tier_pricing_body').show();
            if($('#tier_rows .tier-row').length === 0){ $('#tier_rows').append(tierRowHtml); }
        } else {
            $('#tier_pricing_body').hide();
        }
    }

    $('#add_tier_row').on('click', function(){
        $('#tier_rows').append(tierRowHtml);
        recalcTierSavings();
    });

    $(document).on('click', '.tier-remove', function(){
        $(this).closest('.tier-row').remove();
    });

    $(document).on('input change', '.tier-row input, input[name="price"]', function(){
        recalcTierSavings();
    });

    $(document).on('change', 'input[name="tier_pricing_enabled"]', function(){
        toggleTierBody();
    });

    toggleTierBody();
    recalcTierSavings();

    $('input[name="occasion_tags"], input[name="preferences"], input[name="ai_tags"]').tagsinput();

    $(document).on('click', '.tag-quick-add', function(){
        var field = $(this).data('field');
        var tag = $(this).text().trim();
        var $input = $('input[name="'+field+'"]');
        var exists = false;
        $.each($input.tagsinput('items'), function(i, item){
            if($.trim(item).toLowerCase() === tag.toLowerCase()){ exists = true; return false; }
        });
        if(!exists){ $input.tagsinput('add', tag); }
    });

</script>

<style>
    .bootstrap-tagsinput{width: 100%;}
</style>
@endpush



@endsection





