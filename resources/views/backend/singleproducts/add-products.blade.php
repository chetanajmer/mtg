@php 

  $brands=\App\Models\Brand::get();

  $parentcategory=\App\Models\Parentcategory::get();

  $category=\App\Models\Category::get();

  $subcategory=\App\Models\Subcategory::get();

  $variation=\App\Models\Category_filter::orderBy('id', 'asc')->get();

  $product=\App\Models\Product::all();

  $color=\App\Models\Color::all();

  $branding_options=\App\Models\Branding_option::get();

  $occasion_masters=\App\Models\Occasion::orderBy('name')->get();

  $preference_masters=\App\Models\Preference::orderBy('name')->get();

  $master_product=\App\Models\Product::where('master_product', '=', '')->orWhereNull('master_product')->get();

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

          <h4 class="page-title">Add Product</h4>

          <ol class="breadcrumb"></ol>

        </div>

      </div>

      <div class="row">

        <div class="col-sm-12">

          <form action="{{ route('single_product_add') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">

            {{ csrf_field() }}

            <div class="row">

              <div class="col-lg-12">

                <div class="card-box">

                  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Supplier/Category</b></h5>

                  <div class="row">

                    <div class="col-lg-4" style="padding-left:15px;padding-right: 15px">

                      <div class="form-group m-b-20" >

                        <label>Supplier</label>

                        <select class="brand form-control select2" id="brand" name="brand" >
                            <option>Select Supplier</option>
                          @foreach($brands as $items)

                            <option value="{{$items->id}}">{{$items->brandname}}</option>

                          @endforeach

                        </select>

                      </div>

                    </div>

                    <!-- <div class="col-lg-3" style="padding-left:15px;padding-right: 15px">

                      <div class="form-group m-b-20" >

                        <label>Parent Categories</label>

                        <select name="parentcategory[]" class="form-control select2" id="parentcategory_select" multiple="">

                          @foreach($parentcategory as $items)

                            <option value="{{$items->id}}">{{$items->catname}}</option>

                          @endforeach

                        </select>

                      </div>

                    </div> -->

                    <div class="col-lg-4 " style="padding-left:15px;padding-right: 15px">

                      <div class="form-group m-b-20" >

                        <label>Categories</label>

                        <select name="category" class="form-control select2" id="category_select">
                          <option>Select Category</option>
                         @foreach($category as $items)
                          <option value="{{$items->id}}">{{$items->catname}}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>

                    <div class="col-lg-4" style="padding-left:15px;padding-right: 15px">

                      <div class="form-group m-b-20" >

                        <label>Subcategories</label>

                        <select name="subcategory" class="form-control select2" id="subcategory_select"></select>                        

                       </div>

                    </div>

                  </div>

                  <!-- <div class="row"> -->

                    <!--   <div class="col-lg-3 col-md-offset-1">

                      <div class="form-group m-b-20" >

                      <label>Child Subcategories</label>

                      <select name="childsubcategory[]" class="form-control select2" id="childsubcategory_select" multiple=""></select>

                      </div>

                      </div> -->

                  <!-- </div> -->

                </div>

              </div>

            </div>

            <div class="row">

              <div class="col-lg-6">

                <div class="card-box">

                  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>

                 <!--  <div class="form-group m-b-20">

                    <input type="checkbox" name="refurbished_product" id="refurbished_product" value="yes">

                    <label for="refurbished_product">Refurbished Product</label>

                  </div> -->

                  <div class="form-group m-b-20">

                    <label>Product name <span class="text-danger">*</span></label>

                    <input type="text" required name="name" id="productname" class="form-control" placeholder="e.g : Apple iMac">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Subheading <span class="text-danger">*</span></label>

                    <input type="text" required name="subheading" class="form-control" placeholder="Supporting headline below product name">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Model No <span class="text-danger">*</span></label>

                    <input type="text"  class="form-control" placeholder="TF1001" value="{{$modelno}}" name="modelno" readonly="" >

                  </div>

                  <div class="form-group m-b-20" >
                    <label>Product Color</label>
                    <select  name="product_colors[]" class="selectpicker" multiple data-style="btn-white">
                      @foreach($color as $val)
                      <option style="background-color:{{ $val->code }} " value="{{ $val->code }}">{{ $val->name }}
                      </option>   
                      @endforeach                       
                    </select>  
                  </div>

                  <div class="form-group m-b-20" >
                    <label>Branding Options</label>
                    <select  name="branding_options[]" class="selectpicker" multiple data-style="btn-white">
                      @foreach($branding_options as $bo)
                      <option value="{{ $bo->name }}">{{ $bo->name }}
                      </option>   
                      @endforeach                       
                    </select>  
                  </div>

                  <!-- <div class="form-group m-b-20">

                    <label>SKU</label>

                    <input type="text"  class="form-control" placeholder="TF1001" value="" name="sku">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Weight</label>

                    <input type="number"  step="any" class="form-control" placeholder="2.5" name="weight">
                    <p style="color:blue">Please Enter Weight in kg. (Eg. For 2.5 Kg enter 2.5)</p>

                  </div>

                  <div class="form-group m-b-20">

                    <label>Price <span class="text-danger">*</span></label>

                    <input type="number"  name="price" class="form-control" value="">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Sale Price </label>

                    <div class="row">

                      <div class="col-md-10">

                        <input type="number" name="sprice" class="form-control" value="">

                      </div>

                      <div class="col-md-2" style="padding-top: 8px">

                        <a href="javascript:void(0);" style="text-decoration: underline;" id="show_schedule">Schedule</a>

                        <a href="javascript:void(0);" style="text-decoration: underline;display: none" id="hide_schedule">Cancel</a>

                      </div>

                    </div>

                  </div>

                  <div class="form-group m-b-20" style="display: none" id="sale_date_div">

                    <label>Sale Price Dates</label>

                    <div class="row">

                      <div class="col-lg-6">

                        From: <input type="date" name="date_sale_price_start" class="form-control" value="" id="date_sale_price_start">

                      </div>

                      <div class="col-lg-6">

                       To: <input type="date"  name="date_sale_price_ends" class="form-control" value="" id="date_sale_price_ends">

                      </div>

                    </div>

                  </div>

                  <div class="form-group m-b-20">

                    <label>Product Warranty</label>

                    <select class="form-control select2" name="product_warranty">

                      <option value="">Select Warranty</option>

                      <option value="No Warranty">No Warranty</option>

                      <option value="1 Month Warranty">1 Month Warranty</option>

                      <option value="2 Month Warranty">2 Month Warranty</option>

                      <option value="3 Month Warranty">3 Month Warranty</option>

                      <option value="6 Month Warranty">6 Month Warranty</option>

                      <option value="1 Year Warranty">1 Year Warranty</option>

                      <option value="2 Year Warranty">2 Year Warranty</option>

                    </select>

                  </div> -->

                  <div class="form-group m-b-20">

                    <label class="m-b-15">Status <span class="text-danger">*</span></label>

                    <br/>

                    <div class="radio radio-inline">

                      <input type="radio" id="inlineRadio1" value="online" name="is_active" checked="">

                      <label for="inlineRadio1"> Online </label>

                    </div>

                    <div class="radio radio-inline">

                      <input type="radio" id="inlineRadio2" value="offline" name="is_active">

                      <label for="inlineRadio2"> Offline </label>

                    </div>

                  </div>

                </div>

                <div class="card-box">

                  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Ratings &amp; Reviews</b></h5>

                  <div class="form-group m-b-20">

                    <label>Rating</label>

                    <input type="number" step="0.1" min="0" max="5" class="form-control" placeholder="4.5" name="rating">

                    <p style="color:blue">Enter rating between 0.0 - 5.0 (Eg. 4.5)</p>

                  </div>

                  <div class="form-group m-b-20">

                    <label>Review Count</label>

                    <input type="number" min="0" class="form-control" placeholder="120" name="review_count">

                  </div>

                  <div class="form-group m-b-20">

                    <input type="checkbox" name="reviews_enabled" id="reviews_enabled" value="yes" checked="" data-plugin="switchery" data-color="#81C868" data-size="small">

                    <label for="reviews_enabled"> Enable Reviews</label>

                  </div>

                </div>

                <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0"><b>Product Thumbnail Image</b></h5>

                <p>Image Size Should be 300px X 300px</p>

                 <img id="imagethumbpreview" class="img-rounded" src="{{URL::asset('assets/images/upload.png')}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">

                <input type="file" id="thumbnailval" name="thumbnailval" class=""  >

                </div>


                 <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>

                <p>Image size should be 650px X 650px </p>

                 <img id="imagethumbpreview" class="img-rounded" src="{{URL::asset('assets/images/upload.png')}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">

                <input type="file" id="bulk_image" name="bulk_image[]" class=""  multiple="">

              </div>

                 <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Video</b></h5>

                <div class="form-group m-b-20">

                  <label>Video URL</label>

                  <input type="text" name="video" class="form-control" placeholder="e.g : https://youtube.com/watch?v=...">

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

                    <input type="text" name="metatitle" class="form-control" placeholder="Enter title">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Meta Keywords</label>

                    <input type="text" name="metakey" class="form-control" placeholder="Enter keywords">

                  </div>

                  <div class="form-group m-b-20">

                    <label>Meta Description </label>

                    <textarea class="form-control" name="metadesc" rows="5" placeholder="Please enter description"></textarea>

                  </div>

                  <div class="form-group m-b-20">



                                                        <label>Title</label>



                                                        <input type="text" class="form-control" name="title" placeholder="Enter title" value="" >



                                                    </div>


                                                     <div class="form-group m-b-20">



                                                        <label>OG Type</label>



                                                        <input type="text" class="form-control" name="og_type" placeholder="Enter Og Type" value="" >



                                                    </div>

                                                     <div class="form-group m-b-20">



                                                        <label>Og Url</label>



                                                        <input type="text" class="form-control" name="og_url" placeholder="Enter Og Url" value="" >



                                                    </div>

                                                     <div class="form-group m-b-20">



                                                        <label>Twitter Card</label>



                                                        <input type="text" class="form-control" name="twitter_card" placeholder="Enter Twitter Card" value="" >



                                                    </div>

                                                     <div class="form-group m-b-20">



                                                        <label>Twitter Url</label>



                                                        <input type="text" class="form-control" name="twitter_url" placeholder="Enter Twitter Url" value="" >



                                                    </div>
                                                </div>

                </div>

                <!-- <div class="card-box">

                  <h5 class="text-muted text-uppercase m-t-0"><b>Inventory</b></h5>

                  <div class="form-group m-b-20">

                    <label>Quantity<span class="text-danger"></span></label>

                    <input type="number" class="form-control" name="quantity">

                  </div>

                  <div class="form-group m-b-20">

                    <label class="m-b-15">Stock </label><br/>

                    <div class="radio radio-inline">

                      <input type="radio" id="radio1" value="1" name="stock" >

                      <label for="radio1"> In Stock </label><br>

                    </div>

                    <div class="radio radio-inline">

                      <input type="radio" id="radio2" value="0" name="stock">

                      <label for="radio2"> Out of Stock </label>

                    </div>

                  </div>

                  <div class="form-group m-b-20">

                    <label>Low Stock<span class="text-danger"></span></label>

                    <input type="number" class="form-control" name="low_stock">

                  </div>

                </div>   -->

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

                        <input type="number" min="0" class="form-control" name="min_order_quantity" placeholder="e.g : 50">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group m-b-20">

                        <label>Unit</label>

                        <input type="text" class="form-control" name="moq_unit" value="Pieces">

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

                    <input type="checkbox" name="tier_pricing_enabled" id="tier_pricing_enabled" value="yes" data-plugin="switchery" data-color="#81C868" data-size="small">

                    <label for="tier_pricing_enabled"> Enable Tier Pricing for this product</label>

                  </div>

                  <div id="tier_pricing_body" style="display:none;">

                    <div class="row" style="font-weight:bold;margin-bottom:10px;">

                      <div class="col-md-2">Min Qty</div>

                      <div class="col-md-2">Max Qty</div>

                      <div class="col-md-3">Price / Piece</div>

                      <div class="col-md-3">Savings</div>

                      <div class="col-md-2"></div>

                    </div>

                    <div id="tier_rows"></div>

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

                    <input type="text" name="occasion_tags" class="form-control" data-role="tagsinput" placeholder="e.g : Eid Gifts, Corporate Gifts">

                    <div style="margin-top:8px;">

                      @foreach($occasion_masters as $o)

                      <a href="javascript:void(0);" class="tag-quick-add label label-info" data-field="occasion_tags" style="margin-right:5px;display:inline-block;margin-bottom:5px;">{{ $o->name }}</a>

                      @endforeach

                    </div>

                    <p style="color:blue">Click an occasion above to add it, or type a new one and press Enter.</p>

                  </div>

                  <div class="form-group m-b-20">

                    <label>Preferences</label>

                    <input type="text" name="preferences" class="form-control" data-role="tagsinput" placeholder="e.g : Eco Friendly, Premium Look">

                    <div style="margin-top:8px;">

                      @foreach($preference_masters as $p)

                      <a href="javascript:void(0);" class="tag-quick-add label label-info" data-field="preferences" style="margin-right:5px;display:inline-block;margin-bottom:5px;">{{ $p->name }}</a>

                      @endforeach

                    </div>

                    <p style="color:blue">Click a preference above to add it, or type a new one and press Enter.</p>

                  </div>

                  <div class="form-group m-b-20">

                    <label>AI / Search Tags</label>

                    <input type="text" name="ai_tags" class="form-control" data-role="tagsinput" placeholder="e.g : corporate bottle, executive gift">

                    <p style="color:blue">Free-form tags for AI/search indexing. Not visible to customers.</p>

                  </div>

                </div>

              </div>

            </div>

            <div class="row">

              <div class="col-lg-6">

                  

              </div>

              <div class="col-lg-6">

               

            </div>

          </div>

        <!--   <div class="row">

            <div class="col-lg-12">

              <div class="card-box">

              <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Support</b></h5>

                <div class="row">

                  <div class="col-lg-4">

                    <label>Heading </label>

                    <input type="text"  name="support_heading1" class="form-control" placeholder="eg: Heading" ><br>

                    <label>Upload Pdf </label>

                    <input type="file" name="support_pdf1" accept=".pdf">

                  </div>

                  <div class="col-lg-4">

                    <label>Heading </label>

                    <input type="text"  name="support_heading2" class="form-control" placeholder="eg: Heading" ><br>

                    <label>Upload Pdf </label>

                    <input type="file" name="support_pdf2" accept=".pdf">

                  </div>

                  <div class="col-lg-4">

                    <label>Heading </label>

                    <input type="text"  name="support_heading3" class="form-control" placeholder="eg: Heading" ><br>

                    <label>Upload Pdf </label>

                    <input type="file" name="support_pdf3" accept=".pdf">

                  </div>

                </div>

              </div>    

            </div>

          </div>  --> 

          <div class="row">

            <div class="col-lg-12">

              <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5>

                <textarea class="summernote form-control" name="specification"> </textarea> 

                <hr>

                <label><b>Specification Builder</b></label>

                <p style="color:blue">Add specification name and value pairs. Rows can be reordered and will be shown as a table on the product page.</p>

                <div id="spec_builder">

                  <div class="row spec-row" style="margin-bottom:10px;">

                    <div class="col-md-4">

                      <input type="text" name="spec_name[]" class="form-control" placeholder="e.g : Capacity">

                    </div>

                    <div class="col-md-4">

                      <input type="text" name="spec_value[]" class="form-control" placeholder="e.g : 500ml">

                    </div>

                    <div class="col-md-4">

                      <a href="javascript:void(0);" class="spec-up btn btn-default btn-sm" title="Move up"><i class="fa fa-arrow-up"></i></a>

                      <a href="javascript:void(0);" class="spec-down btn btn-default btn-sm" title="Move down"><i class="fa fa-arrow-down"></i></a>

                      <a href="javascript:void(0);" class="spec-remove btn btn-danger btn-sm" title="Remove">X</a>

                    </div>

                  </div>

                </div>

                <a href="javascript:void(0);" id="add_spec_row" class="btn w-sm btn-default waves-effect waves-light m-t-10">Add Specification</a>

              </div>    

            </div>

          </div>  

          <div class="row">

            <div class="col-lg-12">

              <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Short Description</b></h5>

                <textarea  class="summernote form-control" name="shortdescription" rows="5" placeholder="Please enter description"></textarea>

              </div>    

            </div>

          </div>    

          <div class="row">

            <div class="col-lg-12">

              <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Description</b></h5>

                <textarea class="summernote form-control" name="description"> </textarea> 

              </div>    

            </div>

          </div>  

          <!-- <div class="row">

            <div class="col-lg-6">

              <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Installation Button</b></h5>

                <label>Title</label>

                <input type="text" name="installation_title" class="form-control"><br>

                <div class="field_wrapper">

                </div>

                <a href="javascript:void(0);" class="add_button btn w-sm btn-default waves-effect waves-light" title="Add field" >Add Option</a>

              </div>    

            </div>

            <div class="col-lg-6">

              <div class="card-box">

                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Related Products</b></h5>

                <label>Select Products</label>

                <select class="form-control select2" multiple name="related_product">

                <option value="">Select Product</option>

                @foreach($product as $product)

                <option value="{{$product->id}}">{{$product->name}}</option>

                @endforeach

                </select>

              </div>    

            </div>

          </div>  --> 

         <!--  <div class="row">
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
                </div> -->

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



  // $("body").on("change", ".image", function(e){

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



      var fieldHTML ='<div class="row form-elements"><div class="col-md-5"><input type="text" name="installation_opt_title[]" class="form-control" placeholder="Enter an option"></div><div class="col-md-5"><input type="number" name="installation_opt_price[]" class="form-control" placeholder="0.00"></div><div class="col-md-2" style="padding-top: 8px"><a href="javascript:void(0);" class="remove_button remove-option" title="Add field" >X</a></div></div><br>';

      

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
    
    function add_more_customer_choice_option(){
        $('#customer_choice_options').append('<div class="row remove1"><div class="col-lg-2"><div class="form-group"><input type="hidden" name="choice_no[]" class="form-control" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="" placeholder="Choice Title"></div></div><div class="col-lg-8"><div class="tags-default"><input type="text" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div></div><div class="col-md-2"><button onclick="delete_row(this)"  class="btn btn-danger btn-rounded waves-effect waves-light">Delete</button></div></div>');
        i++;
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

    $('input[name="unit_price"]').on('keyup', function() {
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
        update_sku();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('sku_combination') }}',
           data:$('#choice_form').serialize(),
           success: function(data){

                console.log(data);
               $('#sku_combination').html(data);
              /* if (!data) {
                   $('#quantity').show();
               }
               else {
                    $('#quantity').hide();
               }*/
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





