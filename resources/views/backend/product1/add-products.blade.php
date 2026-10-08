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

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>



                      







                        <div class="row">

                            <div class="col-sm-12">





                                    <form action="{{ route('addproduct') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>



                                                    <div class="form-group m-b-20">

                                                        <label>Product name <span class="text-danger">*</span></label>

                                                        <input type="text" required name="name" class="form-control" placeholder="e.g : Apple iMac">

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Model Number <span class="text-danger">*</span></label>

                                                        <input type="text"  class="form-control" placeholder="e.g : Apple iMac" value={{$modelno}} disabled="">



                                                        <input type="hidden" name="modelno" class="form-control" placeholder="e.g : Apple iMac" value={{$modelno}} >



                                                    </div>



                                                 





                                                    <div class="form-group m-b-20" >

                                                        <label>Categories <span class="text-danger">*</span></label>



                                                        <select class="form-control"  required="" id="categoryonproduct" name="category">

                                                            <option value="">Select</option>

                                                            @foreach ($categories as $category)

                                                            <option value="{{$category->id}}">{{$category->catname}}</option>

                                                            @endforeach

                                                         



                                                        </select>



                                                        <div id="subcategoryonproduct"></div>



                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Price <span class="text-danger">*</span></label>

                                                        <input type="number" required name="price" class="form-control" value="">

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Offer Price <span class="text-danger"></span></label>

                                                        <input type="number"  name="sprice" class="form-control" value="">

                                                    </div>



                                                  <!--    <div class="form-group m-b-20">

                                                        <label>VAT(In Percent)<span class="text-danger"></span></label>

                                                        <input type="number" name="tax" class="form-control" value="">

                                                    </div>
 -->


                                                     <div class="form-group m-b-20">

                                                        <label>Quantity<span class="text-danger"></span></label>

                                                        <input type="number" class="form-control" name="quantity">

                                                    </div>



                                                     <div class="form-group m-b-20">

                                                        <label>Weight<span class="text-danger"></span></label>

                                                        <input type="number" class="form-control" name="weight">

                                                    </div>

                                                     @php 
                                                    $rewards=\App\Models\Reward_setting::where('product_status',"on")->get();
                                                    @endphp

                                                    @if(count($rewards)>0)
                                                    <div class="form-group m-b-20">

                                                        <label>Reward Points<span class="text-danger"></span></label>

                                                        <input type="number" class="form-control" name="reward_points">

                                                    </div>
                                                    @endif



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

                                                    <div class="form-group m-b-20">

                                                        <label>Product Short Description <span class="text-danger">*</span></label>
                                                        <p>Short Description is Limited to 3 Lines/ 300 Characters</p>
                                                        <textarea  class="summernote form-control" name="shortdescription" rows="5" placeholder="Please enter description"></textarea>

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



                                                </div>



                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>
                                                    <p>Image Size Should be 300px X 300px</p>
                                              
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
                                             
                                                </div>    



                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0"><b>Product Gallery Image</b></h5>
                                                    <p>Image size should be 650px X 650px </p>
                                                     

                                                 

                                                     <!--    <label class="active">Photos</label>

                                                        <div class="input-images-1" style="padding-top: .5rem;"></div> -->

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

                                        </div>

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5>



                                                    <textarea class="summernote form-control" name="specification"> </textarea> 

                                               

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

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Variations</b></h5>


                                                   <!--  <p style="color: red;"> This Feature is not Availaible in this Package</p> -->
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

<script type="text/javascript">
    

    var i = 0;
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

@endpush

@endsection


