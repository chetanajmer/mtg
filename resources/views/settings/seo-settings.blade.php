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
                              

                                <h4 class="page-title">SEO Settings</h4>
                                <ol class="breadcrumb">
                                    
                                </ol>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-12">


                                    <form action="{{ route('updateseosettings') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                                       {{ csrf_field() }}
                                        <div class="row">
                                          


                                            <div class="col-lg-6">
                                               
                                                <div class="card-box">



                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Global SEO Settings</b></h5>
                                                    
                                                    
                                                   
                                                    <div class="form-group m-b-20">
                                                        <label>Meta Title<span class="text-danger">*</span></label>
                                                      
                                                        <input type="text" class="form-control" name="metatitle" placeholder="e.g : Apple iMac" value="{{$items->metatitle}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Meta Keywords<span class="text-danger">*</span></label>
                                                        
                                                        <input type="text" class="form-control" name="metakey" placeholder="e.g : Apple iMac" value="{{$items->metakey}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Meta Description<span class="text-danger">*</span></label>
                                                       
                                                        <input type="text" class="form-control" name="metadesc" placeholder="e.g : Apple iMac" value="{{$items->metadesc}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Site Tag Line <span class="text-danger">*</span></label>
                                                      
                                                        <input type="text" class="form-control" name="sitetagline" placeholder="e.g : Apple iMac" value="{{$items->sitetagline}}">
                                                    </div>
                                  

                                                 </div>

                                            
                                            <a href="{{url('dashboard')}}" ><button type="button" class="btn w-sm btn-white waves-effect">Cancel</button></a>
                                                <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button> 
                                            </div>

                                      
                                            
                                            <div class="col-lg-6">
                                               
                                                <div class="card-box">



                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Custom Scripts</b></h5>
                                                       
                                                    <div class="form-group m-b-20">
                                                        <label>Header Custom Script -- Before &lt;/head &gt;  </label>
                                                        <textarea class="form-control" name="headerscript" rows="5" placeholder="<script> ..... </script>"></textarea>
                                                        <p>Write Script with &lt;/script &gt; Tags </p>
                                                    </div>   
                                                    
                                                   
                                                     <div class="form-group m-b-20">
                                                        <label>Footer Custom Script -- Before &lt;/body &gt;  </label>
                                                        <textarea class="form-control" name="bodyscript" rows="5" placeholder="<script> ..... </script>"></textarea>
                                                        <p>Write Script with &lt;/script &gt; Tags </p>
                                                    </div>   

                                                 </div>

                                         
                                            </div> 
                                        </div>    
                                     

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <hr />
                                                <div class="text-center p-10">
                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->
                                                     <!-- <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button> -->
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
