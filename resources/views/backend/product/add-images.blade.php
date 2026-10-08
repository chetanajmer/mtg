@php 

    



    $product=\App\Models\Product::orderBy('id', 'asc')->get();

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



                                <h4 class="page-title">Add Image</h4>



                                <ol class="breadcrumb">



                                </ol>



                            </div>



                        </div>





                        <div class="row">



                           <div class="col-sm-12">



                            <form action="{{ route('addimage') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">



                            {{ csrf_field() }}



                            <div class="row">



                                



                                     </div>  



                            <div class="row">



                                <div class="col-lg-12 card-box">


                                <div class="col-lg-6 ">

                                  

                                  <label>Products <span class="text-danger">*</span></label>



                                    <select class="form-control"  required="" id="product_model" name="product_model">



                                        <option value="">Select</option>



                                        @foreach ($product as $product)



                                        <option value="{{$product->modelno}}">{{$product->modelno}}</option>



                                        @endforeach



                                    </select>
                                   <br><br>
                                    <div class="">

                                        <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>

                                            <p>Image Size Should be 300px X 300px</p>

                                              

                                        <div class="row">

                                            <div class="col-xs-6">

                                                <label class="cabinet center-block">

                                                    <figure> <img src="" class="gambar img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" /><figcaption><i class="fa fa-camera"></i></figcaption></figure>

                                                    <input type="file" name="thumbs" class="item-img file center-block" name="file_photo" required="" /> </label>



                                                    <input type="hidden" name="thumbnailval" id="thumbnailval">

                                            </div>

                                        </div>        

                                    </div>

                                    </div>
                                    <div class="col-lg-6">

                                    <div class="">

                                        <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>

                                            <p>Image size should be 650px X 650px </p>

                                                <div class="row" style="margin-bottom: 60px;">

                                                    <div class="col-lg-4">

                                                       <img id="imagepreview1" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px; margin-bottom: 20px; display: inline-block;">

                                                         <input type="file" id="files1" name="image1" class=""  >



                                                     </div>



                                                     <div class="col-lg-4"> 

                                                        <img id="imagepreview2" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px; margin-bottom: 20px;display: inline-block;">

                                                        <input type="file" id="files2" name="image2" class=""  >

                                                    </div>







                                                    <div class="col-lg-4"> 

                                                       <img id="imagepreview3" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">

                                                         <input type="file" id="files3" name="image3" class=""  >

                                                    </div>

                                                    </div>



                                                    <hr>

                                                            

                                                    <div class="row">

                                                        <div class="col-lg-4">

                                                            <img id="imagepreview4" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px; margin-bottom: 20px; display: inline-block;">

                                                         <input type="file" id="files4" name="image4" class=""  >



                                                        </div>



                                                        <div class="col-lg-4"> 

                                                        <img id="imagepreview5" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px; margin-bottom: 20px;display: inline-block;">



                                                         <input type="file" id="files5" name="image5" class=""  >

                                                        </div>



                                                        <div class="col-lg-4"> 

                                                       <img id="imagepreview6" class="img-rounded" src="assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">



                                                         <input type="file" id="files6" name="image6" class=""  >



                                                        </div>

                                                    </div>

                                                </div>

                                        </div>
                                  <div class="col-lg-6">

                                        

                                </div>

                                

                                



                                    </div>



                                     </div>     

                  

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







<!-- <script>

    $(document).ready(function () {

        $('.selectpicker').selectpicker();

    })

</script> -->







@endpush



@endsection





