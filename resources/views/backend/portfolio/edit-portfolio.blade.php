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
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">Edit Portfolio</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <form action="{{ route('do_update_portfolio') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="{{$items->id}}" name="id" id="id">


            <div class="row">
            	<div class="col-lg-12">
            		<div class="card-box">
            			<div class="row">
            			 	<div class="col-lg-3">
            			 		 <label>Portfolio Division <span class="text-danger">*</span></label>
            			 			<div class="form-group m-b-20">
		                      <select class="brand form-control select2" id="division" name="division" required="">
		                      	<option value="">Select Division</option>
		                        @foreach($division as $division)
		                          <option value="{{$division->id}}" @if($division->id==$items->division) selected @endif >{{$division->name}}</option>
		                        @endforeach
		                      </select>
                  			</div>
            			 	</div>
            			 	<div class="col-lg-3 col-md-offset-1">
            			 		<label>Portfolio Category <span class="text-danger">*</span></label>
            			 		<div class="form-group m-b-20">
		                     <select class="brand form-control select2" id="category" name="category" required="" >
		                       <option value="">Select Category</option>
		                        @foreach($category as $category)
		                          <option value="{{$category->id}}" @if($category->id==$items->category) selected @endif>{{$category->name}}</option>
		                        @endforeach
		                      </select>
                  		</div>
            			 	</div>
            			 	<div class="col-lg-3 col-md-offset-1">
            			 		<label>Portfolio Subcategory</label>
            			 		<div class="form-group m-b-20">
		                     <select class="brand form-control select2" id="subcategory" name="subcategory" >
		                     	 <option value="">Select Subcategory</option>
		                       @php
		                        $subcategory=App\Models\Portfolio_subcategory::where('cat_id',$items->category)->where('is_active','online')->get();
		                       @endphp
		                        @foreach($subcategory as $subcategory)
		                          <option value="{{$subcategory->id}}" @if($subcategory->id==$items->subcategory) selected @endif>{{$subcategory->name}}</option>
		                        @endforeach
		                      </select>
                 			 </div>
            			 	</div>
            			 </div>
            		</div>
            	</div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="card-box">
                  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>
                  <div class="form-group m-b-20">
                    <label>Project name <span class="text-danger">*</span></label>
                    <input type="text" required name="name"  class="form-control" value="{{$items->name}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Project website </label>
                    <input type="text"  name="website"  class="form-control" value="{{$items->website}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Project Launch Date </label>
                    <input type="date"  name="launch_date" class="form-control" value="{{$items->launch_date}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Client Name</label>
                    <input type="text"  name="client_name" class="form-control" value="{{$items->client_name}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Event Name</label>
                    <input type="text"  name="event_name" class="form-control" value="{{$items->event_name}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Location</label>
                    <input type="text"  name="location" class="form-control" value="{{$items->location}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label>Ranking</label>
                    <input type="number"  name="ranking" class="form-control" value="{{$items->ranking}}">
                  </div>
                  <div class="form-group m-b-20">
                    <label class="m-b-15">Status <span class="text-danger">*</span></label>
                    <br/>
                    <div class="radio radio-inline">
                      <input type="radio" id="inlineRadio1" value="online" name="is_active" @if($items->is_active=='online') checked="" @endif>
                      <label for="inlineRadio1"> Online </label>
                    </div>
                    <div class="radio radio-inline">
                      <input type="radio" id="inlineRadio2" value="offline" name="is_active" @if($items->is_active=='offline') checked="" @endif>
                      <label for="inlineRadio2"> Offline </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="card-box">
                <h5 class="text-muted text-uppercase m-t-0"><b>Project Logo</b></h5>
                @if(!empty($items->logo))
                   <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/portfolio/logo/'.$items->logo) }}" alt="your image" style="width:100px; height:100px;margin-bottom: 20px;display: inline-block;">
                   <center> 
                    <a style="background-color:white; position: absolute; top:39px; right:400px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;"  onclick="delete_logo('{{$items->logo}}')"  >X</a> 
                  </center>
                @else
                   <img id="imagepreview1" class="img-rounded" src="{{URL::asset('assets/images/upload.png')}}" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;display: inline-block;">
                @endif
                  <input type="file" id="files1" name="logo" class="" accept="image/*">
                  <input type="hidden"  name="old_logo" class="" value="{{$items->logo}}"  >
                </div>  
              </div>

              <div class="col-lg-6">
                <div class="card-box">
                <h5 class="text-muted text-uppercase m-t-0"><b>Project Multiple Images</b></h5>
                 @if(!empty($items->images))
                  <div class="row" style="margin-bottom: 10px;">
                    @php
                      $bulkimg=explode(',',$items->images)
                    @endphp

                    @foreach($bulkimg as $img)
                      <div class="col-lg-4">
                        <img  class="img-rounded" src="{{ URL::asset('upload/portfolio/'.$img) }}" alt="your image" style="width:100px; height:100px;margin-bottom: 20px;border: 1px solid #ddd">  
                        <center> 
                          <a style="background-color:white; position: absolute; top:-5px; right:49px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;" onclick="deletebulkimage('{{$img}}')"  >X</a> 
                        </center>
                      </div>
                    @endforeach
                  </div>
                  @endif
                    <input type="file" id="bulk_image" name="bulk_image[]" class=""  multiple="" accept="image/*">
                    <input type="hidden" id="bulk_image" name="old_bulk_image" class="" value="{{$items->images}}"  >
                </div>  
              </div>
            </div>

            <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Project Description</b></h5>
                <textarea class="summernote form-control" name="description">{{$items->description}} </textarea> 
              </div>    
            </div>
          </div> 

           <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Services</b></h5>
                <div class="row">
									<div class="col-lg-4 col-md-4 col-sm-6">
										<input type="text" class="form-control" name="service_name" id="service_name" style="height: 32px" placeholder="Enter Service">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6" >
										<button class="btn btn-primary btn-sm " type="button" id="add_service">Add Service</button>
									</div>
								</div><br>
								@if(!empty($items->services))
									@php
										 $services=explode(',',$items->services);
										 $count_service=count($services);
										 $k=0;
									@endphp
								<div class="row">
									@foreach($services as $service)
									<div class="col-md-4 mb-3" id="service_col">
										<div class="card-box">
											<div class="row">
												<div class="col-md-10">
													<input class="form-control" type="text"  name="old_service[]" value="{{$service}}">
												</div>
												<div class="col-md-1 cp" >
													<a onclick="remove_service('{{$k}}')">
														<i class="fa fa-trash " style="font-size: 20px;margin-top: 10px;"></i>
													</a>
													
												</div>
											</div>
										</div>
									</div>
									@php $k++; @endphp
									@endforeach
								</div>
								@endif
								<div class="row" id="service_list">
								
										
								</div>
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



<style>

   .remove-option{

    color: #2271b1;

    border-color: #2271b1;

    background: #dfe7e7;

    vertical-align: top;

    padding: 7px;

}

</style>











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



<!-- Parent Category -->

<script>

$('#parentcategory_select').on('change',function(e) {



    

    var parentcat_id= $("#parentcategory_select").val();



     $.ajax({



                url:"{{ route('findcategoryforproduct') }}",



                method:"POST",



                data: {"_token": "{{ csrf_token() }}","parentcat_id": parentcat_id},



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

                        $('#category_select').empty();

                        $('#category_select').append('<option value="">Select Category</option>');

                        $.each(data.category,function(index,category){

                        $('#category_select').append('<option value="'+category.id+'">'+category.catname+'</option>');

                        })   

                    }



                    else



                    {

                        

                      $('#category_select').empty();

                      $('#category_select').append('<option value="">There is No Categories !</option>');                      

                    }   

                    

                    

                      

                }

            })



});

</script> 





<!-- Category -->



<script>
$('#category').on('change',function(e) {
var cat_id= $("#category").val();
$.ajax({
          url:"{{ route('find_subcategory_for_portfolio') }}",
          method:"POST",
          data: {"_token": "{{ csrf_token() }}","cat_id": cat_id},
          beforeSend: function(){
            $("#loading").show();
          },
          success:function (data) 
          {
            $("#loading").hide();
            console.log(data);
            var totalcount=data.subcategory.length;
            if(totalcount>0)
            {   
              $('#subcategory').empty();
              $.each(data.subcategory,function(index,subcategory){
              $('#subcategory').append('<option value="'+subcategory.id+'">'+subcategory.name+'</option>');
              })   
            }
            else
            {
              $('#subcategory').empty();
              $('#subcategory').append('<option value="">There is No Subcategories !</option>');                      
            }   
          }
})
});



</script>



<!-- Subcategory -->

<!-- <script>

    $('#subcategory_select').on('change',function(e) {



         var subcat_id= $("#subcategory_select").val();

         $.ajax({



                url:"{{ route('findchildsubcategoryforproduct') }}",



                method:"POST",



                data: {"_token": "{{ csrf_token() }}","subcat_id": subcat_id},



                beforeSend: function(){

                    $("#loading").show();

                  },



                success:function (data) 



                {

                    $("#loading").hide();

                    console.log(data);

                   

                    var totalcount=data.childsubcategory.length;

                    if(totalcount>0)

                    {   

                        $('#childsubcategory_select').empty();

                        $('#childsubcategory_select').append('<option value="">Select Child Subcategory</option>');

                        $.each(data.childsubcategory,function(index,childsubcategory){

                        $('#childsubcategory_select').append('<option value="'+childsubcategory.id+'">'+childsubcategory.catname+'</option>');

                        })   

                    }



                    else



                    {

                        

                      $('#childsubcategory_select').empty();

                      $('#childsubcategory_select').append('<option value="">There is No Child Subcategories !</option>');                      

                    }   

                    

                    

                      

                }

            })

    });



</script> -->



<script>



  // $("body").on("change", ".image", function(e){

$("#thumbnailval").on("change", function () {



            if (typeof ($("#thumbnailval")[0].files) != "undefined") 



            {



                var size = parseFloat($("#thumbnailval")[0].files[0].size / 1024).toFixed(2);



                 var reader = new FileReader();



                    reader.onload = function (e) {



                    document.getElementById("imagethumbpreview").src = e.target.result;



                    };



                    reader.readAsDataURL(this.files[0]);

            } 



            else 



            {



                alert("This browser does not support HTML5.");



            }



        });

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

    $('#show_schedule').on('click',function(e){



        $('#sale_date_div').show();

        $('#hide_schedule').show();

         $('#show_schedule').hide();



    });



    $('#hide_schedule').on('click',function(e){



        $('#sale_date_div').hide();

        $('#hide_schedule').hide();

        $('#show_schedule').show();

        $("input[type=date]").val('');

    });



</script>

<script type="text/javascript">



    $(document).ready(function () {



       var x = 1;



      var addButton =$('.add_button'); 



      var wrapper = $('.field_wrapper'); 



      var fieldHTML ='<div class="row form-elements"><div class="col-md-5"><input type="text" name="installation_opt_title[]" class="form-control" placeholder="Enter an option"></div><div class="col-md-5"><input type="number" name="installation_opt_price[]" class="form-control" placeholder="0.00"></div><div class="col-md-2" style="padding-top: 8px"><a href="javascript:void(0);" class="remove_button remove-option" title="Add field" >X</a></div></div><br>';

      

      $(addButton).click(function () {

          x++; 

          $(wrapper).append(fieldHTML);

        

        });

          

      $(wrapper).on('click', '.remove_button', function (e) {

        e.preventDefault();

        $(this).parent().closest(".form-elements").remove();

        x--;

      });





            

    });



</script>
<script>
$('#radio1').on('change',function(e) 
{
  $('#variant_name').hide();
  $('#color').hide();
  $('#master_product').hide();
});

$('#radio2').on('change',function(e) 
{
  $('#variant_name').show();
  $('#color').show();
  $('#master_product').show(); 
});
</script>
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


function deletebulkimage($img)
{
  var id=$('#id').val();
  $.ajax({
          type:"POST",
          url:"{{ route('delete_portfolio_bulkimage') }}",
          method:"POST",
          data: {"_token": "{{ csrf_token() }}","id":id,"img":$img},
          success:function (data)
          {
            // console.log(data);
            $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
            location.reload();
          } 
        });
}
function delete_logo($img)
{
  var id=$('#id').val();
  $.ajax({
          type:"POST",
          url:"{{ route('delete_portfolio_logo') }}",
          method:"POST",
          data: {"_token": "{{ csrf_token() }}","id":id,"img":$img},
          success:function (data)
          {
            // console.log(data);
            $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
            location.reload();
          } 
        });
}
function remove_service(index)
{
	$portfolio_id=$('#id').val();
	
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
                    url:"{{route('delete_portfolio_service')}}",
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}","index":index,"id":$portfolio_id
                    },
                    success:function (data)
                    {
                        console.log(data);
                        $.Notification.notify('success','top right', 'Success ', "Service Deleted Successfully !");
                       location.reload();
                    } 
                });
              } else {
                
              }
            });
	
}
</script>
<script>
$(document).ready(function () {
  var x = 0;
  $('#add_service').click(function () 
  {
    x++; 
    var service_name=$('#service_name').val();
    if(service_name!='')
    {
    	$('#service_list').append('<div class="col-md-4 mb-3" id="service_col"><div class="card-box"><div class="row"><div class="col-md-10"><h5 class="fs-6 text-truncate mb-0">'+service_name+'</h5></div><div class="col-md-1 cp" ><a href="javascript:void(0)" id="remove_service"><i class="fa fa-trash "></i></a></div></div></div><input type="hidden" name="service[]" id="service[]" value="'+service_name+'"></div>');   
    	$('#service_name').val('');
    }
    else
    {
    	dangerClick("Please Enter Service Name");
    }
            
  });
          
  $('#service_list').on('click', "#remove_service", function (e) 
  {
    e.preventDefault();
    $(this).parent().closest("#service_col").remove();
    x--;
   });
});
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

</script>
@endpush



@endsection





