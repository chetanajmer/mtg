@php 
    

    $variation=\App\Models\Category_filter::orderBy('id', 'asc')->get();
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

                              



                                <h4 class="page-title">Add Product</h4>

                                 <a href="{{route('add_image')}}"><button type="button" class="btn w-sm btn-default waves-effect waves-light" style="float: right;">Upload Image</button></a><br>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

<form action="{{ route('addproduct') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}

      <div class="row">
          
          <div class="col-sm-12">

            <div class="row">

              <div class="col-lg-12">

                <div class="card-box">

                        <div class="row">

                          <div class="col-lg-3">

                              <div class="form-group m-b-20" >

                                  <label>Brands <span class="text-danger">*</span></label>

                                    <select class="form-control select2"  required="" id="brand" name="brand">

                                       <option value="">Select</option>

                                         @foreach ($brands as $brand)

                                       <option value="{{$brand->id}}">{{$brand->brandname}}</option>

                                         @endforeach

                                     </select>
                                </div>
                          </div>

                          <div class="col-lg-3 col-lg-offset-1">

                            <div class="form-group m-b-20" >

                                <div id="categoryonproduct" >
                                    <label>Categories</label>

                                    <select name="category" class="form-control select2" id="categoryonproduct_select">
                                        <option value="">Select</option>
                                    </select>
                                                        
                                </div>

                              </div>
                          </div>


                          <div class="col-lg-3 col-lg-offset-1">

                              <div class="form-group m-b-20" >

                                <div id="subcategoryonproduct">

                                  <label>Subcategories</label>

                                   <select name="subcategory" class="form-control select2" id="subcategoryonproduct_select">

                                       <option value="">Select</option>

                                   </select>
                                                        
                                </div>

                              </div>

                          </div>

                                                        
                          <!-- <div class="col-lg-2 col-lg-offset-1">

                            <div class="form-group m-b-20">

                              <label class="m-b-15">Status <span class="text-danger">*</span></label>

                              <br/>

                              <div class="radio radio-inline">

                                  <input type="radio" id="inlineRadio1" value="online" name="is_active[]" checked="">

                                    <label for="inlineRadio1"> Online </label>

                              </div>

                              <div class="radio radio-inline">

                                <input type="radio" id="inlineRadio2" value="offline" name="is_active[]">

                                    <label for="inlineRadio2"> Offline </label>

                              </div>

                            </div>
                      </div> -->


                        </div> <!-- General Row End -->
                                                     
                         
                </div>  <!-- General Col End -->

                   </div>     <!-- General Main Row End -->

                    </div>        <!-- General Main Col End -->  
          </div>
      </div>




                        <div class="row field_wrapper" >

                                <div class="col-sm-12">

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>

                                                    <div class="row">

                                                        <div class="col-lg-2 ">

                                                            <div class="form-group m-b-20">

                                                                <label>Master Product</label>

                                                                <input type="text" id="master_product[]" name="master_product[]" class="form-control" placeholder="e.g : Apple iMac">

                                                            </div>
                                                        </div>

                                                      <div class="col-lg-2 col-lg-offset-1">

                                                        <div class="form-group m-b-20">

                                                            <label>Variant Name</label>

                                                            <input type="text" name="variant_name[]" id="variant_name[]" class="form-control" placeholder="e.g : Apple iMac">

                                                        </div>

                                                      </div>

                                                    <div class="col-lg-2 col-lg-offset-1">

                                                    <div class="form-group m-b-20">

                                                        <label>Product name <span class="text-danger">*</span></label>

                                                        <input type="text" required name="name[]" id="productname[]" class="form-control" placeholder="e.g : Apple iMac">

                                                    </div>

                                                    </div>

                                                    <div class="col-lg-2 col-lg-offset-1">

                                                      <div class="form-group m-b-20">

                                                          <label>Part Number <span class="text-danger">*</span></label>

                                                          <input type="text"  class="form-control" placeholder="TF1001" value="" name="modelno[]" required="" id="partnumber[]">
                                                      </div>
                                                    </div>

                                                      <div class="col-lg-2 ">

                                                      <div class="form-group m-b-20">

                                                          <label>Price<span class="text-danger">*</span></label>

                                                          <input type="number"  class="form-control" placeholder="10" value="" name="price[]" required="" id="price[]">
                                                      </div>
                                                    </div>

                                                    <div class="col-lg-2 col-lg-offset-1">

                                                      <div class="form-group m-b-20">

                                                          <label>Sale Price</label>

                                                          <input type="number"  class="form-control" placeholder="10" value="" name="sprice[]" id="sprice[]">
                                                      </div>
                                                    </div>

                                                    <div class="col-lg-2 col-lg-offset-1">

                                                      <div class="form-group m-b-20">

                                                          <label>Customized Price</label>

                                                          <input type="number"  class="form-control" placeholder="10" value="" name="cprize[]"  id="cprize[]">
                                                      </div>
                                                    </div>
                                                </div>

                                                
                                                     
                          <hr>
                              <div class="row">

                                            
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="">

                                        <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Support</b></h5>

                                         <div class="row">
                                                    <div class="col-lg-4">
                                                        <label>Heading </label>
                                                        <input type="text"  name="support_heading1[]" class="form-control" placeholder="eg: Heading" ><br>
                                                        <label>Upload Pdf </label>
                                                        <input type="file" name="support_pdf1[]" accept=".pdf">
                                                       
                                                    </div>

                                                    <div class="col-lg-4">
                                                    <label>Heading </label>
                                                        <input type="text"  name="support_heading2[]" class="form-control" placeholder="eg: Heading" ><br>
                                                        <label>Upload Pdf </label>
                                                        <input type="file" name="support_pdf2[]" accept=".pdf">
                                                    </div>

                                                    <div class="col-lg-4">
                                                    <label>Heading </label>
                                                        <input type="text"  name="support_heading3[]" class="form-control" placeholder="eg: Heading" ><br>
                                                        <label>Upload Pdf </label>
                                                        <input type="file" name="support_pdf3[]" accept=".pdf">
                                                    </div>
                                                </div>
                                                </div>    
                                            </div>
                                </div>
                                <hr>

                              <div class="row">

                                   <div class="col-lg-4">

                                     <div class="">

                                        <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5>
                                            <textarea class="summernote form-control" name="specification[]" > </textarea> 
                                      </div>    
                                    </div>

                                   <div class="col-lg-4">

                                        <div class="">

                                          <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Short Description</b></h5>
                                                       
                                           <textarea  class="summernote form-control" name="shortdescription[]" rows="5" placeholder="Please enter description"></textarea>
                                         </div>    

                                   </div>

                                   <div class="col-lg-4">

                                        <div class="">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Description</b></h5>
                                            <textarea class="summernote form-control" name="description[]" > </textarea> 
                                         </div>    
                                    </div>

                            </div>    
                                          
                   </div> <!-- General Card Box End -->

                </div>  <!-- General Col End -->

                   </div>     <!-- General Main Row End -->

                    </div>        <!-- General Main Col End -->               
                                                 
                    </div>
                    </div>
                    </div> <!-- container -->

                               

            <div class="row">
              <div class="col-sm-12">
                <div class="text-center p-20">
                  <button type="Submit" class="btn w-sm btn-default waves-effect waves-light">Save</button>
                  <a href="javascript:void(0);" class="add_button btn w-sm btn-default waves-effect waves-light" title="Add field" >+ Add More</a>
                                                
                </div>
              </div>
            </div>
       <input type="text" id="filterslug" value="" style="display: none">  
       <input type="text" id="filterslug_category" value="" style="display: none"> 
        
</form>
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
 
$('#brand').on('change',function(e) {

    var id= this.value;

    // alert(id);
            $.ajax({


                url:"{{ route('findcategoryforproduct') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

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
                        $('#categoryonproduct_select').empty();
                        $('#categoryonproduct_select').append('<option value="">Select Category</option>');
                        $.each(data.category,function(index,category){
                        $('#categoryonproduct_select').append('<option value="'+category.id+'">'+category.catname+'</option>');
                        })

                        $('#subcategoryonproduct_select').empty();
                    }

                    else

                    {
                        
                      $('#categoryonproduct_select').empty();
                      $('#categoryonproduct_select').append('<option value="">There is No Categories !</option>');
                      $('#subcategoryonproduct_select').append('<option value="">There is No Subcategories !</option>');
                      $('#subcategoryonproduct_select').empty();
                    }    



                }



            })
    });
</script>

<script>
$('#categoryonproduct_select').on('change',function(e) {

    
    var my_id= this.value;

    // alert(my_id);
        $.ajax({

                url:"{{ route('findsubcat') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","my_id": my_id},

                beforeSend: function(){
                    $("#loading").show();
                  },

                success:function (data) 

                {
                    $("#loading").hide();
                    console.log(data);
                    var totalcount=data.subcategories.length;

                // alert(totalcount);
                    if(totalcount>0)
                    {   
                        $('#subcategoryonproduct_select').empty();
                        $('#subcategoryonproduct_select').append('<option value="">Select Subcategory</option>');
                        $.each(data.subcategories,function(index,subcategory){
                        $('#subcategoryonproduct_select').append('<option value="'+subcategory.id+'">'+subcategory.catname+'</option>');
                        })   
                    }

                    else

                    {
                        
                      // $('#subcategoryonproduct').show();
                      $('#subcategoryonproduct_select').empty();
                      $('#subcategoryonproduct_select').append('<option value="">There is No Subcategories !</option>');                      
                    }    
                }
            })

});
</script> 

<script type="text/javascript">

    $(document).ready(function () {

       var x = 1;

      var addButton =$('.add_button'); 

      var wrapper = $('.field_wrapper'); 

      var fieldHTML ='<div class="form-elements"><div class="col-sm-12"><div class="row"><div class="col-lg-12"><div class="card-box"><h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5><div class="row"><div class="col-lg-2 "><div class="form-group m-b-20"><label>Master Product</label><input type="text" id="master_product[]" name="master_product[]" class="form-control" placeholder="e.g : Apple iMac"></div></div><div class="col-lg-2 col-lg-offset-1"><div class="form-group m-b-20"><label>Variant Name</label><input type="text" name="variant_name[]" id="variant_name[]" class="form-control" placeholder="e.g : Apple iMac"></div></div><div class="col-lg-2 col-lg-offset-1"><div class="form-group m-b-20"><label>Product name <span class="text-danger">*</span></label><input type="text" required name="name[]" id="productname[]" class="form-control" placeholder="e.g : Apple iMac"></div></div><div class="col-lg-2 col-lg-offset-1"><div class="form-group m-b-20"><label>Part Number <span class="text-danger">*</span></label><input type="text"  class="form-control" placeholder="TF1001" value="" name="modelno[]" required="" id="partnumber[]"></div></div></div><hr><div class="row"><div class="row"><div class="col-lg-12"><div class=""><h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Support</b></h5><div class="row"><div class="col-lg-4"><label>Heading </label><input type="text"  name="support_heading1[]" class="form-control" placeholder="eg: Heading" ><br><label>Upload Pdf </label><input type="file" name="support_pdf1[]" accept=".pdf"></div><div class="col-lg-4"><label>Heading </label><input type="text"  name="support_heading2[]" class="form-control" placeholder="eg: Heading" ><br><label>Upload Pdf </label><input type="file" name="support_pdf2[]" accept=".pdf"></div><div class="col-lg-4"><label>Heading </label><input type="text"  name="support_heading3[]" class="form-control" placeholder="eg: Heading" ><br><label>Upload Pdf </label><input type="file" name="support_pdf3[]" accept=".pdf"></div></div></div></div></div><hr><div class="row"><div class="col-lg-4"><div class=""><h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Specification</b></h5><textarea class="textarea form-control" name="specification[]" > </textarea></div></div><div class="col-lg-4"><div class=""><h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Short Description</b></h5><textarea  class="textarea form-control" name="shortdescription[]" rows="5" placeholder="Please enter description"></textarea></div></div><div class="col-lg-4"><div class=""><h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Product Description</b></h5><textarea class="textarea form-control" name="description[]" > </textarea></div></div></div><div class="form-group"><a href="javascript:void(0);" class="remove_button btn w-sm btn-danger waves-effect waves-light" title="Add field" style="margin-left:16px;">Remove</a></div></div> <!-- General Card Box End --> </div><!-- General Col End --></div><!-- General Main Row End --></div><!-- General Main Col End --> </div></div>';
      
     

        $(document).ready(function() {
        $('.textarea').summernote();
      });

      
      
      $(addButton).click(function () {
          x++; 
          $(wrapper).append(fieldHTML);
          $(wrapper).find('.textarea').summernote();
        });
          
      $(wrapper).on('click', '.remove_button', function (e) {
        e.preventDefault();
        $(this).parent().closest(".form-elements").remove();
        x--;
      });


            
    });

</script>
<!-- <script>

$('select[id=subcategoryonproduct_select]').on('change',function() {

    var id= this.value;

         $.ajax({

                url:"{{ route('find_subcat_filter') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                beforeSend: function(){
                    $("#loading").show();
                  },

                success:function (data) 

                {
                  $("#loading").hide();
                  console.log(data);
                  $('#filterforsubcategory').show();
                  $('#filter_subcat_checkbox').empty();
                  
                  $.each(data.subcat_filters,function(index,subcat_filters){
                    $('#filter_subcat_checkbox').append('<div class="row"><div class="col-sm-12"><div class="field_wrapper"><div class="col-sm-2"><input type="checkbox"  name="checkbox[]" value="'+subcat_filters.id+'"></div><div class="col-sm-4"><input type="text" class="form-control" placeholder="Filter Name" name="filter_name[]" value="'+subcat_filters.filter_name+'"></div><div class="col-sm-6"><input type="text" class="form-control" placeholder="Filter Value"name="filter_value[]" value="'+subcat_filters.filter_value+'"></div></div> </div></div><br>');
                    })
                  $('#filterforcategory').hide();
                  $('#filter_cat_checkbox').empty();
                }

            })

});
</script> --> 




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

<script>
$('#productname').on('change',function(e){

    var productname=$('#productname').val();
    var product_name=productname.replace(/[^A-Z0-9]/ig, "-");
    var part_number=$('#part_number').val();

    if(part_number!='')
    {
        var meta=product_name+'-'+part_number;
    }
    else
    {
        var meta=product_name;
    }

    $('#product_name').val(product_name);
    $('input[name=metatitle]').val(meta);
    $('input[name=metakey]').val(meta);
    $('textarea[name=metadesc]').val(meta);
});
</script>

<script>
$('#partnumber').on('change',function(e){

    var partnumber=$('#partnumber').val();
    var part_number=partnumber.replace(/[^A-Z0-9]/ig, "-");
    var product_name=$('#product_name').val();


    if(product_name!='')
    {
        var meta=product_name+'-'+part_number;
    }
    else
    {
        var meta=part_number;
    }

    $('#part_number').val(part_number);
    $('input[name=metatitle]').val(meta);
    $('input[name=metakey]').val(meta);
    $('textarea[name=metadesc]').val(meta);

});
</script>

<!-- <script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker();
    })
</script> -->



@endpush

@endsection


