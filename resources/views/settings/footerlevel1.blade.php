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

                              



                                <h4 class="page-title">Footer Level 1 Settings</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('updatefooterlevel1') }}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Settings</b></h5>

                                                    <div class="form-group m-b-20">

                                                        @if($items->image1)

                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->image1) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif
                                                        <input type="file" id="files1" name="image1" class=""  >
                                                    </div>    

                                                    <div class="form-group m-b-20">

                                                        <label>Heading 1<span class="text-danger">*</span></label>

                                                        <input type="text" name="h1" value="{{$items->h1}}" class="form-control" placeholder="Custom Links">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Heading 1 Content<span class="text-danger">*</span></label>

                                                        <textarea class="form-control" name="h1desc">{{$items->h1desc}}</textarea>

                                                    </div>

                                                 </div>

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Settings</b></h5>

                                                   <div class="form-group m-b-20">
                                                        @if($items->image2)

                                                        <img id="imagepreview2" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->image2) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview2" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files2" name="image2" class=""  >
                                                     </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Heading 2<span class="text-danger">*</span></label>

                                                        <input type="text" name="h2" value="{{$items->h2}}" class="form-control" placeholder="Custom Links">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Heading 2 Content<span class="text-danger">*</span></label>

                                                        <textarea class="form-control" name="h2desc">{{$items->h2desc}}</textarea>

                                                    </div>

                                                </div>

                                            

                                            </div>





                                            <div class="col-lg-6">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Heading 3 Settings </b></h5>

                                                   <div class="form-group m-b-20">
                                                        @if($items->image3)

                                                        <img id="imagepreview3" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->image3) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview3" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files3" name="image3" class=""  >
                                                     </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Heading 3<span class="text-danger">*</span></label>

                                                        <input type="text" name="h3" value="{{$items->h3}}" class="form-control" placeholder="Custom Links">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Heading 3 Content<span class="text-danger">*</span></label>

                                                        <textarea class="form-control" name="h3desc">{{$items->h3desc}}</textarea>

                                                    </div>

                                                </div>

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Heading 4 Settings</b></h5>

                                                   <div class="form-group m-b-20">
                                                        @if($items->image4)

                                                        <img id="imagepreview4" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->image4) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview4" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files4" name="image4" class=""  >

                                                     </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Heading 4<span class="text-danger">*</span></label>

                                                        <input type="text" name="h4" value="{{$items->h4}}" class="form-control" placeholder="Custom Links">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Heading 4 Content<span class="text-danger">*</span></label>

                                                        <textarea class="form-control" name="h4desc">{{$items->h4desc}}</textarea>

                                                    </div>

                                                </div>

                                  
                                            </div>



                                            

                                             </div>

                                        </div>





                                        <div class="row">

                                            <div class="col-sm-12">

                                                <hr />

                                                <div class="text-center p-20">

                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->

                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                                    <!--  <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete</button> -->

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>



<!-- Image Popup  -->

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



<script>

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

</script>

@endpush

@endsection

