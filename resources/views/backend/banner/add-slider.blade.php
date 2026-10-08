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

                              



                                <h4 class="page-title">Banners</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>



                      







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('updatebanner') }}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            



                                            <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 1</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                        @if($items->image1)

                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image1) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/b1.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files1" name="image1" class=""  >

                                                       

                                                    </div>





                                                    <div class="form-group m-b-20">

                                                        <label>Banner1 Name <span class="text-danger">*</span></label>

                                                        <input type="text" name="bannername1" class="form-control" placeholder="e.g : Home Slider" value="{{$items->bannername1}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner1 Link <span class="text-danger">*</span></label>

                                                        <input type="text" name="image1link" class="form-control" placeholder="e.g : Home Slider" value="{{$items->image1link}}">

                                                    </div>



                                                    



                                                </div>

                                            </div>



                                            <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 2</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                        @if($items->image2)

                                                        <img id="imagepreview2" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image2) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview2" class="img-rounded" src="/assets/images/b2.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files2" name="image2" class=""  >

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Banner2 Name <span class="text-danger">*</span></label>

                                                        <input type="text" name="bannername2" class="form-control" placeholder="e.g : Home Slider" value="{{$items->bannername2}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner2 Link <span class="text-danger">*</span></label>

                                                        <input type="text" name="image2link" class="form-control" placeholder="e.g : Home Slider" value="{{$items->image2link}}">

                                                    </div>



                                                    



                                                </div>

                                            </div>



                                           <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 3</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                         @if($items->image3)

                                                        <img id="imagepreview3" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image3) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview3" class="img-rounded" src="/assets/images/b3.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files3" name="image3" class=""  >

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner3 Name <span class="text-danger">*</span></label>

                                                        <input type="text" value="{{$items->bannername3}}" name="bannername3" class="form-control" placeholder="e.g : Home Slider">

                                                    </div>





                                                    <div class="form-group m-b-20">

                                                        <label>Banner3 Link <span class="text-danger">*</span></label>

                                                        <input type="text" value="{{$items->image3link}}" name="image3link" class="form-control" placeholder="e.g : Home Slider">

                                                    </div>



                                                    



                                                </div>

                                            </div>     





                                        </div>

                                        <div class="row">

                                            



                                            <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 4</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                        @if($items->image4)

                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image4) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview4" class="img-rounded" src="/assets/images/b1.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files4" name="image4" class=""  >

                                                       

                                                    </div>





                                                    <div class="form-group m-b-20">

                                                        <label>Banner4 Name <span class="text-danger">*</span></label>

                                                        <input type="text" name="bannername4" class="form-control" placeholder="e.g : Home Slider" value="{{$items->bannername4}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner4 Link <span class="text-danger">*</span></label>

                                                        <input type="text" name="image4link" class="form-control" placeholder="e.g : Home Slider" value="{{$items->image4link}}">

                                                    </div>



                                                    



                                                </div>

                                            </div>



                                            <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 5</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                        @if($items->image5)

                                                        <img id="imagepreview5" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image5) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview5" class="img-rounded" src="/assets/images/b2.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files5" name="image5" class=""  >

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Banner5 Name <span class="text-danger">*</span></label>

                                                        <input type="text" name="bannername5" class="form-control" placeholder="e.g : Home Slider" value="{{$items->bannername5}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner5 Link <span class="text-danger">*</span></label>

                                                        <input type="text" name="image5link" class="form-control" placeholder="e.g : Home Slider" value="{{$items->image5link}}">

                                                    </div>



                                                    



                                                </div>

                                            </div>



                                           <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b> Banner 6</b></h5>

                                                    



                                                    <div class="form-group m-b-20">

                                                            

                                                         @if($items->image6)

                                                        <img id="imagepreview6" class="img-rounded" src="{{ URL::asset('upload/banners/'.$items->image6) }}" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview6" class="img-rounded" src="/assets/images/b3.jpg" alt="your image" style="width:325px;margin-bottom: 20px;">

                                                        @endif

                                                         <input type="file" id="files6" name="image6" class=""  >

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Banner6 Name <span class="text-danger">*</span></label>

                                                        <input type="text" value="{{$items->bannername6}}" name="bannername6" class="form-control" placeholder="e.g : Home Slider">

                                                    </div>





                                                    <div class="form-group m-b-20">

                                                        <label>Banner6 Link <span class="text-danger">*</span></label>

                                                        <input type="text" value="{{$items->image6link}}" name="image6link" class="form-control" placeholder="e.g : Home Slider">

                                                    </div>



                                                    



                                                </div>

                                            </div>     





                                        </div>

                                        <div class="row">

                                            <div class="col-sm-12">

                                                <hr />

                                                <div class="text-center p-20">

                                                   

                                                     <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button>

                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>



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

     $('#subcat_alter').empty();

    var my_id= this.value;

    //alert(my_id);



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

                        $('#subcategoryonproduct').append(' <label style="margin-top:20px;">Subcategories<span class="text-danger">*</span></label><select name="subcategory" class="form-control" id="subcategoryonproduct_select">');

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

  // $("body").on("change", ".image", function(e){

    $("#files1").on("change", function () {

            if (typeof ($("#files1")[0].files) != "undefined") 

            {

                var size = parseFloat($("#files1")[0].files[0].size / 1024).toFixed(2);

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                } 



                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview1").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }   

                //alert(size + " KB.");

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

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                } 

                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview2").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }   

                //alert(size + " KB.");

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

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                }  

                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview3").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }  

                //alert(size + " KB.");

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

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                }  

                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview4").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }  

                //alert(size + " KB.");

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

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                }  

                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview5").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }  

                //alert(size + " KB.");

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

                

                if(size>350)

                {

                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");



                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");



                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")

                }  

                else

                {

                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview6").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

                }  

                //alert(size + " KB.");

            } 

            else 

            {

                alert("This browser does not support HTML5.");

            }

        });

</script>



@endpush

@endsection

