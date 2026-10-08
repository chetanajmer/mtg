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
        <h4 class="page-title">Edit Coupon</h4>
        <ol class="breadcrumb"></ol>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
        <form action="{{route('updatecoupon')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
           <input type="hidden" class="form-control" name="slug" value="{{$items->slug}}">
        <div class="row">
          <div class="col-lg-5">
            <div class="card-box">
            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>
            @if(Session::has('successMsg'))
            <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('successMsg') }}</div>
            @endif
            <div class="form-group m-b-20">
              <label>Coupon Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="coupon_code"  id="coupon_code" placeholder="Coupon Code" required="" value="{{$items->coupon_code}}"><br>
              <button type="button"  id="generate_coupon_code" class="btn w-sm btn-primary  waves-effect waves-light" >Generate Coupon Code</button>
            </div>
            <div class="form-group m-b-20">
              <label>Coupon Type<span class="text-danger">*</span></label>
              <select class="form-control" name="coupon_type" id="coupon_type">
              <option value="per" @if($items->coupon_type=="per") selected="" @endif>Percentage Discount</option>
              <option value="fixed" @if($items->coupon_type=="fixed") selected="" @endif>Fixed Cart Discount</option>
              </select>
            </div>
            <div class="form-group m-b-20">
              <label>Coupon Amount<span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="coupon_amount" placeholder="0" required="" value="{{$items->coupon_amount}}">
            </div>

            @if(!empty($items->coupon_max_amount))
            <div class="form-group m-b-20" id="max_amount1">
              <label>Maximum Amount</label>
              <input type="number" class="form-control" name="coupon_max_amount" placeholder="0" value="{{$items->coupon_max_amount}}"> 
            </div>
            @else
            <div class="form-group m-b-20" style="display: none" id="max_amount">
              <label>Maximum Amount</label>
              <input type="number" class="form-control" name="coupon_max_amount" placeholder="0">
            </div>
            @endif
             <div class="form-group m-b-20">
              <label>Coupon Expiry Date</label>
              <input type="date" class="form-control" name="expiry_date" value="{{$items->expiry_date}}">
            </div>
            <div class="form-group m-b-20">
              <input type="checkbox" name="free_delivery" value="1" @if($items->free_delivery=="1") checked @endif>
             <label for="free_delivery">Allow Free Delivery</label>
            </div>
            <div class="form-group m-b-20">
              <label class="m-b-15">Status <span class="text-danger">*</span></label>
            <br/>
            <div class="radio radio-inline">
              <input type="radio" id="inlineRadio1" value="online" name="is_active"  @if($items->is_active=='online') checked @endif>
              <label for="inlineRadio1"> Online </label>
            </div>
            <div class="radio radio-inline">
              <input type="radio" id="inlineRadio2" value="offline" name="is_active" @if($items->is_active=='offline') checked @endif>
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
                      <input type="radio" id="Radio1" value="parentcategory" name="active_coupon" @if($items->coupon_based=='parentcategory') checked @endif>
                      <label for="Radio1"> Parentcategories</label>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio2" value="category" name="active_coupon" @if($items->coupon_based=='category') checked @endif>
                      <label for="Radio2">Categories</label>
                    </div>
                  </div>

                   <div class="col-lg-3">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio3" value="subcategory" name="active_coupon" @if($items->coupon_based=='subcategory') checked @endif>
                      <label for="Radio3"> Subcategories</label>
                    </div>
                  </div>

                   <div class="col-lg-2">
                     <div class="radio radio-inline">
                  <input type="radio" id="Radio4" value="product" name="active_coupon" @if($items->coupon_based=='product') checked @endif>
                  <label for="Radio4">Products </label>
                </div>
                  </div>

                    <div class="col-lg-2">
                    <div class="radio radio-inline">
                      <input type="radio" id="Radio5" value="none" name="active_coupon" @if($items->coupon_based=='none') checked @endif>
                      <label for="Radio5">None</label>
                    </div>
                  </div>
                  
                </div>
              </div>

              @if($items->coupon_based=='parentcategory')
                <div class="form-group m-b-20" id="coupon">
                <label>Coupon Based on Parent Categories </label>
                @php
                  $pcat = explode(",", $items->category);
                @endphp
                  <select class="form-control select2" name="cat[]"  multiple="">
                  @foreach($parentcategories as $item)
                    <option value="{{$item->id}}" {{ (in_array($item->id, $pcat)) ? 'selected' : '' }}>{{$item->catname}}</option>
                  @endforeach
                  </select>
              </div>
              @endif
                
              @if($items->coupon_based=='category')
            <div class="form-group m-b-20" id="coupon">
              <label>Coupon Based on Categories </label>
              @php
                $cat = explode(",", $items->category);
                @endphp
                <select class="form-control select2" name="cat[]"  multiple="">
                @foreach($categories as $item)
                <option value="{{$item->id}}" {{ (in_array($item->id, $cat)) ? 'selected' : '' }}>{{$item->catname}}</option>
                @endforeach
              </select>
            </div> 
            @endif

             @if($items->coupon_based=='subcategory')
            <div class="form-group m-b-20" id="coupon">
              <label>Coupon Based on SubCategories </label>
              @php
                $subcat = explode(",", $items->category);
                @endphp
                <select class="form-control select2" name="cat[]"  multiple="">
                @foreach($subcategories as $item)
                <option value="{{$item->id}}" {{ (in_array($item->id, $subcat)) ? 'selected' : '' }}>{{$item->catname}}</option>
                @endforeach
              </select>
            </div>
            @endif

             @if($items->coupon_based=='product')
              <div class="form-group m-b-20" id="coupon">
                <label>Coupon Based on Products </label>
                @php
                $prod = explode(",", $items->category);
                @endphp
                <select class="form-control select2" name="cat[]"  multiple="">
                @foreach($products as $item)
                <option value="{{$item->modelno}}" {{ (in_array($item->id, $prod)) ? 'selected' : '' }}>{{$item->name}}</option>
                @endforeach
                </select>
              </div>
              @endif
                <!-- <div class="form-group " id="coupon" ></div> -->
            
           
            <div class="form-group m-b-20">
              <label>Limit per coupon</label>
              <input type="text" class="form-control" name="limit" value="{{$items->limits}}">
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
    $('#max_amount1').show();
  }
 else
  {
    $('#max_amount').hide();
    $('#max_amount1').hide();
  }

});

</script>
@endpush
@endsection



