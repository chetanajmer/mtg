@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif

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

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>



                      







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('updateproduct') }}" id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>

                                                       <!-- Hidden Fields -->

                                                       <input type="hidden" name="slug" class="form-control" placeholder="e.g : Apple iMac" value="{{$items->slug}}">

                                                       <input type="hidden" name="id" class="form-control" placeholder="e.g : Apple iMac" value="{{$items->id}}">
                                                       <!-- Hidden Fields -->



                                                    <div class="form-group m-b-20">

                                                        <label>Product name <span class="text-danger">*</span></label>

                                                        <input type="text" name="name" required="" class="form-control" placeholder="e.g : Apple iMac" value="{{$items->name}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Model Number <span class="text-danger">*</span></label>

                                                        <input type="text"  class="form-control" placeholder="e.g : Apple iMac" value="{{$items->modelno}}" disabled="">





                                                    </div>



                                                 





                                                    <div class="form-group m-b-20" >

                                                        <label>Categories <span class="text-danger">*</span></label>



                                                        <select class="form-control" required="" id="categoryonproduct" name="category">

                                                            <option value="">Select</option>

                                                            @foreach ($categories as $category)

                                                            <option value="{{$category->id}}" 

                                                                @if($category->id==$items->category)

                                                                selected=""

                                                                @endif

                                                                >{{$category->catname}}</option>

                                                            @endforeach

                                                            



                                                        </select>



                                                        <div id="subcategoryonproduct"></div>



                                                        @if(!empty($items->subcategory))



                                                            <div id="subcat_alter">

                                                            <label style="margin-top: 20px;">Subcategories <span class="text-danger">*</span></label>

                                                            <select class="form-control" id="" name="subcategory">

                                                            

                                                            @foreach ($subcategories as $category)

                                                            <option value="{{$category->id}}" 

                                                                @if($category->id==$items->subcategory)

                                                                selected=""

                                                                @endif

                                                                >{{$category->catname}}</option>

                                                            @endforeach

                                                            



                                                            </select>

                                                            </div>

                                                        @endif



                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Price <span class="text-danger">*</span></label>

                                                        <input type="number" required="" value="{{$items->price}}" name="price" class="form-control" value="">

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Offer Price <span class="text-danger"></span></label>

                                                        <input type="number" value="{{$items->sprice}}" name="sprice" class="form-control" value="">

                                                    </div>



                                                    <!--  <div class="form-group m-b-20">

                                                        <label>VAT(In Percent)<span class="text-danger"></span></label>

                                                        <input type="number" name="tax" value="{{$items->tax}}" class="form-control" value="">

                                                    </div> -->



                                                     <div class="form-group m-b-20">

                                                        <label>Quantity<span class="text-danger"></span></label>

                                                        <input type="number" value="{{$items->quantity}}" class="form-control" name="quantity">

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Weight<span class="text-danger"></span></label>

                                                        <input type="number" value="{{$items->weight}}" class="form-control" name="weight">

                                                    </div>

                                                    @php 
                                                    $rewards=\App\Models\Reward_setting::where('product_status',"on")->get();
                                                    @endphp

                                                    @if(count($rewards)>0)
                                                    <div class="form-group m-b-20">

                                                        <label>Reward Points<span class="text-danger"></span></label>

                                                        <input type="number" value="{{$items->reward_points}}" class="form-control" name="reward_points">

                                                    </div>
                                                    @endif


                                                   <div class="form-group m-b-20">

                                                        <label class="m-b-15">Status <span class="text-danger">*</span></label>

                                                        <br/>

                                                        <div class="radio radio-inline">

                                                            <input type="radio" id="inlineRadio1" value="online" name="is_active"  @if($items->is_active=='online') checked="" @endif  >

                                                            <label for="inlineRadio1"> Online </label>

                                                        </div>

                                                        <div class="radio radio-inline">

                                                            <input type="radio" id="inlineRadio2" value="Offline" name="is_active" @if($items->is_active=='offline') checked="" @endif>

                                                            <label for="inlineRadio2"> Offline </label>

                                                        </div>

                                                       

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Product Short Description <span class="text-danger">*</span></label>
                                                        <p>Short Description is Limited to 3 Lines/ 300 Characters</p>
                                                       <textarea  class="summernote form-control" name="shortdescription" rows="5" placeholder="Please enter description">{{$items->shortdescription}}</textarea>

                                                    </div>

                                                  



                                                </div>

                                            </div>





                                            <div class="col-lg-6">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Meta Data</b></h5>



                                                    <div class="form-group m-b-20">

                                                        <label>Meta title</label>

                                                        <input type="text" class="form-control" name="metatitle" placeholder="Enter title" value="{{$items->metatitle}}" >

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Meta Keywords</label>

                                                        <input type="text" class="form-control" name="metakey" placeholder="Enter keywords" value="{{$items->metakey}}" >

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Meta Description </label>

                                                        <textarea class="form-control" rows="5" name="metadesc" placeholder="Please enter description" value="">{{$items->metadesc}}</textarea>

                                                    </div>



                                                </div>



                                                <div class="card-box">

                                               

                                                    <h5 class="text-muted text-uppercase m-t-0 "><b> Thumbnail Image</b></h5>
                                                    <p>Image Size Should be 300px X 300px</p>
                                                   

                                                    @if($items->thumbnail)

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <label class="cabinet center-block">
                                                                <figure> <img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="your image" style="width:120px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />
                                                                    <!-- <figcaption><i class="fa fa-camera"></i></figcaption> -->
                                                                </figure>
                                                                <input type="file" name="thumbs" class="item-img file center-block" name="file_photo" /> </label>

                                                                <input type="hidden" name="thumbnailval" id="thumbnailval">
                                                        </div>
                                                    </div>

                                                    @else

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <label class="cabinet center-block">
                                                                <figure> <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />
                                                                    <figcaption><i class="fa fa-camera"></i></figcaption>
                                                                </figure>
                                                                <input type="file" name="thumbs" class="item-img file center-block" name="file_photo" /> </label>

                                                                <input type="hidden" name="thumbnailval" id="thumbnailval">
                                                        </div>
                                                    </div>

                                                    @endif

                                                                                                       

                                                    

                                                </div>



                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>
                                                    <p>Image size should be 650px X 650px </p>
                                                     

                                                 

                                                     <!--    <label class="active">Photos</label>

                                                        <div class="input-images-1" style="padding-top: .5rem;"></div> -->

                                                    <div class="row" style="margin-bottom: 60px;"> 

                                                        

                                                        <div class="col-lg-4">

                                                            

                                                        @if($items->image1)

                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image1) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files1" name="image1" class=""  >

                                                        </div>





                                                        <div class="col-lg-4"> 

                                                        @if($items->image2)

                                                        <img id="imagepreview2" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image2) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview2" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files2" name="image2" class=""  >

                                                        </div>







                                                        <div class="col-lg-4"> 

                                                        @if($items->image3)

                                                        <img id="imagepreview3" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image3) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview3" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files3" name="image3" class=""  >

                                                        </div>



                                                    

                                                    </div>

                                                    <hr>
                                                    
                                                      
                                                    <div class="row">

                                                        

                                                        <div class="col-lg-4">

                                                            

                                                        @if($items->image4)

                                                        <img id="imagepreview4" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image4) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview4" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files4" name="image4" class=""  >

                                                        </div>





                                                        <div class="col-lg-4"> 

                                                        @if($items->image5)

                                                        <img id="imagepreview5" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image5) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview5" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files5" name="image5" class=""  >

                                                        </div>







                                                        <div class="col-lg-4"> 

                                                        @if($items->image6)

                                                        <img id="imagepreview6" class="img-rounded" src="{{ URL::asset('upload/product/'.$items->image6) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview6" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files6" name="image6" class=""  >

                                                        </div>

                                                        

                                                

                                                    </div>
                                                    

                                                </div>

                                            </div>





                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5>



                                                    <textarea class="summernote form-control" name="specification"> {{$items->specification}}</textarea> 

                                               

                                                </div>    



                                            </div>

                                        </div>  
                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Description</b></h5>



                                                    <textarea class="summernote form-control" name="description"> {{$items->description}}</textarea> 

                                               

                                                </div>    



                                            </div>

                                        </div>   

                                        @if(!empty(json_decode($items->variations))) 

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
                                                                <option style="background-color:{{ $color->code }} "  value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($items->colors))) echo 'selected'?> >{{ $color->name }}</option>
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
                                                                    <option style="background-color:{{ $color->code }} " value="{{ $color->code }}">{{ $color->name }}</option>
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
                                                            <option style="background-color:{{ $color->code }} " value="{{ $color->code }}">{{ $color->name }}</option>
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
                                        <div class="row">

                                            <div class="col-sm-12">

                                            

                                                <div class="text-center p-20">

                                                    <!--  <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->

                                                     <button type="Submit" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                                    <!--  <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete</button> -->

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>



                     <!-- 

                     Image Popup --> 

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

<div class="modal-dialog modal-lg" role="document">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title" id="modalLabel">Crop Image</h5>

<button type="button" class="close" data-dismiss="modal" aria-label="Close">

<span aria-hidden="true">×</span>

</button>

</div>

<div class="modal-body">

<div class="img-container">

<div class="row">

<div class="col-md-8">

<img id="image" src="https://avatars0.githubusercontent.com/u/3456749">

</div>

<div class="col-md-4">

<div class="preview"></div>

</div>

</div>

</div>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

<button type="button" class="btn btn-primary" id="crop">Crop</button>

</div>

</div>

</div>

</div>

</div>

                     <!-- Image popup End -->      



                    </div> <!-- container -->

                               

    

                </div> <!-- content -->





@push('header-scripts')



<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">





<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>



<style type="text/css">

img {

display: block;

max-width: 100%;

}

.preview {

overflow: hidden;

width: 160px; 

height: 160px;

margin: 10px;

border: 1px solid red;

}

.modal-lg{

max-width: 1000px !important;

}

</style>



@endpush







@push('custom-scripts')





<script type="text/javascript">

    $(function () {

        $("#upload").bind("click", function () {

            if (typeof ($("#fileUpload")[0].files) != "undefined") {

                var size = parseFloat($("#fileUpload")[0].files[0].size / 1024).toFixed(2);

                alert(size + " KB.");

            } else {

                alert("This browser does not support HTML5.");

            }

        });

    });

</script>





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



<script>







$('#categoryonproduct').on('change',function(e) {

    

    var my_id= this.value;

    //alert(my_id);

     $('#subcat_alter').empty();

        $.ajax({



            url:"{{ route('findsubcat') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","my_id": my_id},

                success:function (data) 

                {

                console.log(data);





                var totalcount=data['subcategories'].length;

                //alert(totalcount);



                    if(totalcount>0)

                    {    

                       

                        $('#subcategoryonproduct').empty();

                        $('#subcategoryonproduct').append(' <label style="margin-top:20px;">Subcategories<span class="text-danger">*</span></label><select name="subcategory" class="form-control" id="subcategoryonproduct_select"><option value="">Select</option>');

                        $.each(data.subcategories,function(index,subcategory){

                        $('#subcategoryonproduct_select').append('<option value="'+subcategory.id+'">'+subcategory.catname+'</option>');

                        })

                        $('#subcategoryonproduct').append('</select>');

                    }

                    else

                    {

                       $('#subcategoryonproduct').empty();

                    $('#subcategoryonproduct').append('<label style="margin-top:20px;">There is No Subcategories !</label>');

                    }    



                }



            })





});





              //subcategory ajax ends

</script> 





<script >

   $('.input-images-1').imageUploader();

</script>



<script>

var $modal = $('#modal');

var image = document.getElementById('image');

var cropper;

$("body").on("change", ".image", function(e){

var files = e.target.files;



if(this.files[0].size>350000)

{

    swal("Error", "Image Size Cannot Be More Then 350 Kb !!")

    //swal("Image Size Cannot Be More Then 350 Kb !");

    

}



else

{



    var done = function (url) {

    image.src = url;

    $modal.modal('show');

    };

    var reader;

    var file;

    var url;

}



if (files && files.length > 0) {



   





file = files[0];

if (URL) {

done(URL.createObjectURL(file));

} else if (FileReader) {

reader = new FileReader();

reader.onload = function (e) {

done(reader.result);

};

reader.readAsDataURL(file);

}

}

});

$modal.on('shown.bs.modal', function () {

cropper = new Cropper(image, {

aspectRatio: 1,

viewMode: 3,

preview: '.preview'

});

}).on('hidden.bs.modal', function () {

cropper.destroy();

cropper = null;

});

$("#crop").click(function(){

canvas = cropper.getCroppedCanvas({

width: 250,

height: 250,

});



//var base64data=0;

canvas.toBlob(function(blob) 

{

        url = URL.createObjectURL(blob);

        var reader = new FileReader();

        reader.readAsDataURL(blob); 



        reader.onloadend = function() {





        var base64data = reader.result; 

        document.getElementById("imagepreview").src = base64data;

        document.getElementById("cropped").value = base64data;

        //

        //swal("Cropped");

        swal("Success", "Image Cropped successfully !")

        $modal.modal('hide');

        //var mylength=base64data.[]size;

        //var mS_totalBytes = base64data.files.size;

        //swal("Crop image successfully uploaded"+ mS_totalBytes);



        }





});





            







          /*  alert(base64data);

            $.ajax({

            type: "POST",

            dataType: "json",

            url: "addcategory",

            data: {'_token': $('meta[name="_token"]').attr('content'), 'image': base64data},

            success: function(data){

            console.log(data);

            $modal.modal('hide');

            alert("Crop image successfully uploaded");

            }

            });*/



 





})

</script>







<!-- Preview -->

 <script>



/*document.getElementById("files1").onchange = function () {

var reader = new FileReader();

reader.onload = function (e) {

document.getElementById("imagepreview1").src = e.target.result;

};

reader.readAsDataURL(this.files[0]);

};*/







document.getElementById("files2").onchange = function () {

var reader = new FileReader();

reader.onload = function (e) {

document.getElementById("imagepreview2").src = e.target.result;

};

reader.readAsDataURL(this.files[0]);

};







document.getElementById("files3").onchange = function () {

var reader = new FileReader();

reader.onload = function (e) {

document.getElementById("imagepreview3").src = e.target.result;

};

reader.readAsDataURL(this.files[0]);

};



</script>



<script>
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

@endpush

@endsection

