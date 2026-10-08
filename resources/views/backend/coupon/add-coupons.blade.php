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
        <h4 class="page-title">Add Coupon</h4>
        <ol class="breadcrumb"></ol>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
        <form action="{{ route('add') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-lg-5">
            <div class="card-box">
            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>
            @if(Session::has('successMsg'))
            <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('successMsg') }}</div>
            @endif
            <div class="form-group m-b-20">
              <label>Coupon Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="coupon_code"  id="coupon_code" placeholder="Coupon Code" required="" value=""><br>
              <button type="button"  id="generate_coupon_code" class="btn w-sm btn-primary  waves-effect waves-light" >Generate Coupon Code</button>
            </div>
            <div class="form-group m-b-20">
              <label>Coupon Type<span class="text-danger">*</span></label>
              <select class="form-control" name="coupon_type" id="coupon_type">
              <option value="per">Percentage Discount</option>
              <option value="fixed" selected="">Fixed Cart Discount</option>
              </select>
            </div>
            <div class="form-group m-b-20">
              <label>Coupon Amount<span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="coupon_amount" placeholder="0" required="">
            </div>
            <div class="form-group m-b-20" style="display: none" id="max_amount">
              <label>Maximum Amount</label>
              <input type="number" class="form-control" name="coupon_max_amount" placeholder="0">
            </div>
             <div class="form-group m-b-20">
              <label>Coupon Expiry Date</label>
              <input type="date" class="form-control" name="expiry_date">
            </div>
            <div class="form-group m-b-20">
              <input type="checkbox" name="free_delivery" value="1">
             <label for="free_delivery">Allow Free Delivery</label>
            </div>
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
            </div>
          </div>
          <div class="col-lg-7">
            <div class="card-box">
            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Coupon Based On</b></h5>
              <div class="form-group m-b-20">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio1" value="parentcategory" name="active_coupon" >
                      <label for="Radio1"> Parentcategories</label>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio2" value="category" name="active_coupon" >
                      <label for="Radio2">Categories</label>
                    </div>
                  </div>

                   <div class="col-lg-3">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio3" value="subcategory" name="active_coupon" >
                      <label for="Radio3"> Subcategories</label>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio4" value="product" name="active_coupon">
                      <label for="Radio4">Products </label>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio5" value="none" name="active_coupon" checked="">
                      <label for="Radio5">None</label>
                    </div>
                  </div>

                </div>
              </div>

            <div class="form-group " id="coupon" ></div>

            <div class="form-group ">
              <label>Limit per coupon</label>
              <input type="text" class="form-control" name="limit">
            </div>
          
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
          <div class="text-center p-20">
          <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>
          </div>
          </div>
        </div>
      </form>
    </div>
  </div>     
</div> <!-- container -->
</div> <!-- content -->
</div>

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
$('input[type="radio"]').click(function(){
    var inputValue = $(this).attr("value");
    $.ajax({
            url:"{{ route('getinputvalue') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","inputvalue": inputValue},
            beforeSend: function()
            {
              $("#loading").show();
            },
            success:function (data) 
            {
              $("#loading").hide();
              console.log(data);
              var totalcount=data.data.length;
							if(totalcount>0)
              {   
                $('#coupon').empty();
                $('#coupon').append('<label>Coupon Based on '+data.labelname+' </label><select id="select2" class="form-control select2" name="cat[]"  multiple="" >');
                $.each(data.data,function(index,data){
                $('#select2').append('<option value="'+data.id+'">'+data.name+'</option>');
                }) 
                $('#coupon').append('</select>');  
                $('.select2').select2();
              }
              else
              {
              	 $('#coupon').empty();
              }
            }
     }); 
 });


$("#generate_coupon_code").click(function()
{
  var result=''
  var length=7;
  var chars="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
  $('#coupon_code').val(result);
});

$("#coupon_type").change(function()
{
  // alert(this.value);
  if(this.value=='per')
  {
    $('#max_amount').show();
  }
 else
  {
    $('#max_amount').hide();
  }

});

</script>
@endpush
@endsection



