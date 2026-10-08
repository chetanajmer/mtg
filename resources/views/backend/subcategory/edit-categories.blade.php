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



                              







                                <h4 class="page-title">Edit Sub Category</h4>



                                <ol class="breadcrumb">



                                    



                                </ol>



                            </div>



                        </div>















                        <div class="row">



                            <div class="col-sm-12">











                                    <form action="{{ route('updatesubcat') }}" method="post" class="form-horizontal" enctype="multipart/form-data">



                                                       {{ csrf_field() }}



                                        <div class="row">



                                            <div class="col-lg-6">



                                                <div class="card-box">



                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>



                                                    @if(Session::has('successMsg'))



                                                <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('successMsg') }}</div>



                                             @endif





                                                    <div class="form-group m-b-20">



                                                        <label>Category <span class="text-danger">*</span></label>



                                                        <select class="form-control select2" name="catid" required="" >
                                                            @foreach($categories as $item)
                                                            <option value="{{$item->id}}" 
                                                              @if($item->id==$items->catid)

                                                                selected=""

                                                                @endif>{{$item->catname}}</option>
                                                            @endforeach
                                                        </select>







                                                    </div>







                                                    <div class="form-group m-b-20">



                                                        <label>Subcategory Name <span class="text-danger">*</span></label>



                                                        <input type="text" class="form-control" name="name" placeholder="e.g : Apple iMac" value="{{$items->catname}}" required="">



                                                    </div>



                                                     <div class="form-group m-b-20">



                                                        <label>Subcategory Slug <span class="text-danger">*</span></label>



                                                        <input type="text" class="form-control" name="slug" placeholder="e.g : Apple iMac" value="{{$items->slug}}" readonly="">



                                                    </div>





                                                    <div class="form-group m-b-20">



                                                        <label>Ranking<span class="text-danger">*</span></label>



                                                        <input type="number" class="form-control" name="ranking" placeholder="Ranking" value="{{$items->ranking}}" required="">



                                                    </div>

                                                  







                                                  







                                               







                                                    







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







                                                







                                                </div>







                                                    <div class="card-box">



                                                    <h5 class="text-muted text-uppercase m-t-0"><b>Subcategory Thumbnail Image</b></h5>



                                                     <p>Image Size Should be 650px X 650px</p>    

                                                   



                                                    <div class="row">

                                                        <div class="col-xs-12">

                                                            <label class="cabinet center-block">

                                                            <div  id="subcategorythumbdiv">

                                                              @if($items->image1)



                                                            <div style="position: relative;">

                                                              <img src="{{ URL::asset('upload/subcategory/'.$items->image1) }}" alt="your image" style="width:120px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                  <center> 

                                                                <a href="javascript:void(0)" style="background-color:white; position: absolute; top:-5px; right:357px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;" onclick="deletesubcategory_thumbnail('{{$items->id}}')"  >X</a> </center>

                                                            </div>



                                                        @else



                                                        <img id="imagepreview" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="thumbs" name="thumbs" class=""  >

                                                      <input type="hidden" name="oldimage1" value="{{$items->image1}}">

                                                        </div>

                                                    </div>





                                              



                                                </div>


                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0"><b>SubCategory Banner</b></h5>

                                                     <p>Image Size Should be 1920px X 500px</p>



                                                    <div id="subcategorybannerdiv">

                                                    @if($items->bannerimage)



                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/subcategory/'.$items->bannerimage) }}" alt="your image" style="width:120px;height:120px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                  <center> 

                                                                <a href="javascript:void(0)" style="background-color:white; position: absolute; top:-5px; right:357px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;" onclick="deletesubcategory_banner('{{$items->id}}')"  >X</a> </center>

                                                            </div>



                                                        @else



                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files1" name="bannerimage" class=""  >

                                                      <input type="hidden" name="oldbannerimage" value="{{$items->bannerimage}}">



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



                                                 



                                            </div>





                                             <!-- @php 

                                                $filters=\App\Models\Category_filter::where('subcat_slug',$items->slug)->get();

                                            @endphp

                                             <div class="row">

                                            <div class="col-sm-12">

                                                <div class="card-box">

                                                    @foreach($filters as $items)

                                                <div class="field_wrapper1">

                                                    

                                                <div class="col-sm-4">

                                                    <label for="product_name">Filter Name:</label>

                                                    <input type="text" class="form-control" placeholder="Filter Name"

                                                        name="filter_name[]" value="{{$items->filter_name}}">

                                                </div>

                                                <div class="col-sm-4">

                                                    <label for="amount">Filter Value:</label>

                                                    <input type="text" class="form-control" placeholder="Filter Value"

                                                        name="filter_value[]" value="{{$items->filter_value}}">

                                                </div>

                                                 

                                                <div class="form-group">

                                                    <a href="javascript:void(0);" class="remove_button btn w-sm btn-default waves-effect waves-light" title="Add field" style="margin-top:26px;" onclick="removefilter('{{$items->id}}')"> Remove</a>

                                                </div>

                                                <input type="hidden" name="filter_id[]" value="{{$items->id}}">

                                                </div>

                                                    @endforeach

                                                   

                                                     <div class="field_wrapper">

                                               

                                                 

                                                <div class="form-group">

                                                    <a href="javascript:void(0);" class="add_button btn w-sm btn-default waves-effect waves-light" title="Add field" style="margin-top:26px;">+ Add More</a>

                                                </div>

                                                </div>

                                               

                                            </div>

                                          </div>

                                        </div> -->





                                        </div>



                                        <div class="row">
                                          <div class="col-sm-12">
                                            <div class="card-box">
                                              <div class="form-group m-b-20">
                                                 <h5 class="text-muted text-uppercase m-t-0"><b>Subcategory Description </b></h5>
                                                  <textarea class="form-control summernote" rows="5" name="desc" placeholder="Please enter description" >{{$items->catdescription}}</textarea>
                                              </div>
                                            </div>
                                          </div>
                                        </div>







                                        <div class="row">



                                            <div class="col-sm-12">



                                            



                                                <div class="text-center p-20">



                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->



                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>



                                                    <!-- <a href="{{url('deletesubcat/'.$items->slug)}}" target="_self"> <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete</button></a> -->



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



</script>

<script>

  

    $("#thumbs").on("change", function () {



            if (typeof ($("#thumbs")[0].files) != "undefined") 



            {



                var size = parseFloat($("#thumbs")[0].files[0].size / 1024).toFixed(2);



           

                    var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagepreview").src = e.target.result;



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



        $(document).ready(function () {



            var addButton = $('.add_button'); 



            var wrapper = $('.field_wrapper'); 



            var fieldHTML = `<div class="form-elements">

                    <div class="col-sm-4">

                    <label for="product_name">Filter Name:</label>

                    <input type="text" class="form-control" placeholder="Enter name" name="new_filter_name[]">

                    </div>

                    <div class="col-sm-4">

                    <label for="amount">Filter Value:</label>

                    <input type="text" class="form-control" placeholder="Enter value" name="new_filter_value[]">

                    </div>

                    <div class="form-group">

                    <a href="javascript:void(0);" class="remove_button btn w-sm btn-default waves-effect waves-light" title="Add field" style="margin-top:26px;">Remove</a>

                    </div>

                </div>`;



            var x = 1;



            $(addButton).click(function () {

                    x++; 

                    $(wrapper).append(fieldHTML);

            });



            //Once remove button is clicked

            $(wrapper).on('click', '.remove_button', function (e) {

                e.preventDefault();

                $(this).parent().closest(".form-elements").remove();

                x--;

            });





            // $('.field_wrapper1').on('click', '.remove_button', function (e) {

            //     e.preventDefault();

            //     $(this).parent().closest(".field_wrapper1").remove();

            //     x--;

            // });

        });

</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>

function removefilter($id)

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

                    url:"{{ route('deletefilter') }}",

                    method:"POST",

                    data: {"_token": "{{ csrf_token() }}","id":$id

                    },



                    success:function (data)

                    {

                        $.Notification.notify('success','top right', 'Success ', "Filter Delete Successfully !");

                        location.reload();

                    } 

                });

               

              } else {

                

              }

            });

}







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
@endpush



@endsection



