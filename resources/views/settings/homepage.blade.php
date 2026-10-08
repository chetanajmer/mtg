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

                              



                                <h4 class="page-title">Store Settings</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('homepageupdate') }}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">

                                            



                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Site Settings</b></h5>



                                                    <div class="form-group m-b-20">

                                                        <label>Whats App Number <span class="text-danger">*</span></label>

                                                        <input type="text" name="whatsappno" value="{{$items->whatsappno}}" class="form-control" placeholder="Custom Links">

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>Currency <span class="text-danger">*</span></label>

                                                        <input type="text" name="currencysymbol" value="{{$items->currencysymbol}}" class="form-control" placeholder="Custom Links">

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>VAT<span class="text-danger">*</span></label>
                                                        <p>Please Enter Value in Percentage, Eg: 5,10</p>
                                                        <input type="number" name="vat" value="{{$items->vat}}" class="form-control" placeholder="Custom Links">

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Weight Type<span class="text-danger">*</span></label>
                                                        <p>Eg: Kg, gms, Pieces, Boxes etc.</p>
                                                        <input type="text" name="weighttype" value="{{$items->weighttype}}" class="form-control" placeholder="Custom Links">

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Weight Base Shipping    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($items->shippingtype=="on") checked="" @endif  name="shippingtype" data-plugin="switchery" data-color="#f05150"   data-size="small"/>

                                                    </div>

                                                    
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Theme Color</b></h5>

                                                    <div class="form-group m-b-20">

                                                        <label>Theme Primary (Base) Color</label>

                                                        <div data-color-format="rgb" data-color="{{$items->themecolor}}" class="colorpicker-default input-group">

                                                            <input type="text" name="themecolor"  value="{{$items->themecolor}}" class="form-control">

                                                            <span class="input-group-btn add-on">

                                                                <button class="btn btn-white" type="button">

                                                                    <i style="background-color: rgb(124, 66, 84);margin-top: 2px;"></i>

                                                                </button> 

                                                            </span>

                                                        </div>

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>Theme Secondary Color</label>
                                                        <p style="color:#3c7bbe">Color triggered when the user hovers over an element with the cursor.</p>
                                                        <div data-color-format="rgb" data-color="{{$items->themesecondarycolor}}" class="colorpicker-default input-group">

                                                            <input type="text" name="themesecondarycolor" value="{{$items->themesecondarycolor}}" class="form-control">

                                                            <span class="input-group-btn add-on">

                                                                <button class="btn btn-white" type="button">

                                                                    <i style="background-color: rgb(124, 66, 84);margin-top: 2px;"></i>

                                                                </button> 

                                                            </span>

                                                        </div>

                                                    </div>



                                                </div>
                                            </div>

                                            <div class="col-lg-6">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Sale Section</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Sale section    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($items->salestatus=="on") checked="" @endif  name="salestatus" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>Sale Product Limit <span class="text-danger">*</span></label>

                                                        <input type="number" name="saleproductlimit" value="{{$items->saleproductlimit}}" class="form-control" placeholder="Custom Links">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Sale Background Color</label>

                                                        <div data-color-format="rgb" data-color="{{$items->salecolor}}" class="colorpicker-default input-group">

                                                            <input type="text" name="salecolor" value="{{$items->salecolor}}" class="form-control">

                                                            <span class="input-group-btn add-on">

                                                                <button class="btn btn-white" type="button">

                                                                    <i style="background-color: rgb(124, 66, 84);margin-top: 2px;"></i>

                                                                </button> 

                                                            </span>

                                                        </div>

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Sale Background Opacity<span class="text-danger">*</span></label>

                                                        <input type="number" name="saleimageopacity" value="{{$items->saleimageopacity}}" step="0.001"  class="form-control" placeholder="Custom Links">

                                                    </div>

                                                    <div class="form-group m-b-0">
                                                            
                                                        

                                                         @if($items->salebackground)
                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->salebackground) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">
                                                        @else
                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">
                                                        @endif
                                                         <input type="file" id="files1" name="salebackground" class=""  >


                                                    </div>

                                                    


                                                </div>
                                            </div>

                                         

                                        </div>


                                        <div class="row">
                                              <div class="col-lg-6">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>New Arrival Section</b></h5>

                                                   
                                                    <div class="form-group m-b-20">

                                                        <label>New Arrival Product Limit <span class="text-danger">*</span></label>

                                                        <input type="number" name="newarrivalproductlimit" value="{{$items->newarrivalproductlimit}}" class="form-control" placeholder="Custom Links">

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>New Arrival Background Color</label>

                                                        <div data-color-format="rgb" data-color="{{$items->newarrivalcolor}}" class="colorpicker-default input-group">

                                                            <input type="text" name="newarrivalcolor"  value="{{$items->newarrivalcolor}}" class="form-control">

                                                            <span class="input-group-btn add-on">

                                                                <button class="btn btn-white" type="button">

                                                                    <i style="background-color: rgb(124, 66, 84);margin-top: 2px;"></i>

                                                                </button> 

                                                            </span>

                                                        </div>

                                                    </div>
                                                    <div class="form-group m-b-20">

                                                        <label>New Arrival Background Opacity<span class="text-danger">*</span></label>

                                                        <input type="number" name="newarrivalimageopacity" value="{{$items->newarrivalimageopacity}}" class="form-control" step="0.001"  placeholder="Custom Links">

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                          

                                                        @if($items->newarrivalbackground)

                                                        <img id="imagepreview2" class="img-rounded" src="{{ URL::asset('upload/extra/'.$items->newarrivalbackground) }}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @else

                                                        <img id="imagepreview2" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">

                                                        @endif

                                                        <input type="file" id="files2" name="newarrivalbackground" class=""  >   
                                                                                                                    
                                                    </div>


                                                </div>

                                            </div>
                                            
                                        </div>    


                                        <div class="row">

                                            <div class="col-sm-12">

                                             

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

var $modal = $('#modal');

var image = document.getElementById('image');

var cropper;

$("body").on("change", ".image", function(e){

var files = e.target.files;



if(this.files[0].size>350000)

{

    alert("Image Size Cannot Be More Then 350 Kb !");

    

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

        $modal.modal('hide');

        //var mylength=base64data.[]size;

        //var mS_totalBytes = base64data.files.size;

        alert("Crop image successfully uploaded"+ mS_totalBytes);



        }





});





            $("#save1").click(function(){







            alert(base64data);

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

            });



        });    





})

</script>





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


</script>        

@endpush

@endsection

