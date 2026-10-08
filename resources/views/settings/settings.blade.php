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

                              



                                <h4 class="page-title">Store CMS Settings</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('env_key_update') }}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">

                                                 <div class="card-box">



                                                                                                       

                                                   

                                                   <!--  <div class="form-group m-b-20">

                                                        <label>System Title<span class="text-danger">*</span></label>

                                                      

                                                        <input type="text" class="form-control" name="site_name" placeholder="e.g : Apple iMac" value="{{$items->site_name}}">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>System Short Name<span class="text-danger">*</span></label>

                                                        <p>Upto 8 Words</p>

                                                        <input type="text" class="form-control" name="cmsshortname" placeholder="e.g : Apple iMac" value="{{$items->cmsshortname}}">

                                                    </div> -->



                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_MAILER">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL DRIVER')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <select class="demo-select2 form-control" name="MAIL_MAILER">
                                <option value="sendmail" @if (env('MAIL_MAILER') == 'sendmail') selected @endif>Sendmail</option>
                                <option value="smtp" @if (env('MAIL_MAILER') == 'smtp') selected @endif>SMTP</option>
                            </select>
                        </div>
                    </div>                               
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_HOST">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL HOST')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_HOST" value="{{  env('MAIL_HOST') }}" placeholder="MAIL HOST">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_PORT">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL PORT')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_PORT" value="{{  env('MAIL_PORT') }}" placeholder="MAIL PORT">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_USERNAME">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL USERNAME')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_USERNAME" value="{{  env('MAIL_USERNAME') }}" placeholder="MAIL USERNAME" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL PASSWORD')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_PASSWORD" value="{{  env('MAIL_PASSWORD') }}" placeholder="MAIL PASSWORD">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL ENCRYPTION')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_ENCRYPTION" value="{{  env('MAIL_ENCRYPTION') }}" placeholder="MAIL ENCRYPTION">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL FROM ADDRESS')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_FROM_ADDRESS" value="{{  env('MAIL_FROM_ADDRESS') }}" placeholder="MAIL FROM ADDRESS" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL FROM NAME')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_FROM_NAME" value="{{  env('MAIL_FROM_NAME') }}" placeholder="MAIL FROM NAME" required>
                        </div>
                    </div>
                   



                                                 

                                                </div>



                                             



                                               <!--  <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-30"><b>Category Thumbnail Image</b></h5>

                                                   

                                                    

                                                    <input type="file" name="image" class="image filestyle"   data-buttonbefore="true">

                                                     <input type="hidden" name="cropped"  id="cropped" >



                                                    <img id="imagepreview" class="img-rounded" src="assets/images/upload.png" alt="your image" style="width:120px;margin-top: 20px;">



                                                   <a class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal">Full width Modal</a>

                                                    

                                                </div> -->



                                                <a href="{{url('dashboard')}}" ><button type="button" class="btn w-sm btn-white waves-effect">Cancel</button></a>

                                                <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>





                                            </div>





                                            <div class="col-lg-6">

                                               

                                                   <div class="card-box">



                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Instruction</b></h5>



                                                    <p style="color:red;">Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.</p>



                                                

                                                    <h5 class="text-muted text-uppercase " style="color:#BE8E79;font-weight: 600;">For Non-SSL</h5>

                                                    

                                                    <ul>

                                                        <li>Enter smtp as Mail Driver.</li>

                                                        <li>Set Mail Host according to your server Mail Client Manual Settings.</li>

                                                        <li>Set Mail port as 587.</li>

                                                        <li>Set Mail Encryption as ssl if you face issue with tls.</li>

                                                    </ul>





                                                    <h5 class="text-muted text-uppercase " style="color:#BE8E79;font-weight: 600;">For SSL</h5>

                                                    

                                                    <ul>

                                                        <li>Enter smtp as Mail Driver.</li>

                                                        <li>Set Mail Host according to your server Mail Client Manual Settings.</li>

                                                        <li>Set Mail port as 465.</li>

                                                        <li>Set Mail Encryption as ssl if you face issue with tls.</li>

                                                    </ul>


                                                    <h5 class="text-muted text-uppercase " style="color:#BE8E79;font-weight: 600;">Sample Mail Configuration</h5>
                                                        
                                                    <ul>

                                                        <li>MAIL DRIVER: SMTP</li>

                                                        <li>MAIL HOST: mail.easyappz.com</li>

                                                        <li>MAIL PORT: 465</li>

                                                        <li>MAIL USERNAME: testing@easyappz.com</li>

                                                        <li>MAIL PASSWORD: easyappz123</li>
                                                        <li>MAIL ENCRYPTION: SSL</li>
                                                        <li>MAIL FROM ADDRESS: testing@easyappz.com</li>
                                                        <li>MAIL FROM NAME: Online Store</li>

                                                    </ul>

                                                        



                                            </div>



                                            

                                        </div>





                                        <div class="row">

                                            <div class="col-sm-12">

                                                <hr />

                                                <div class="text-center p-20">

                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->

                                                    <!--  <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button> -->

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

@endpush

@endsection

