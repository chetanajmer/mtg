@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif

@php
//$pageid=

if(isset($_GET['page']))
{
    $pageid=$_GET['page'];
}
else
{
    $pageid=1;
}

$email=session()->get('username');
@endphp
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
          <!--  <div class="btn-group pull-right m-t-15" style="display:flex;">
              <a href="{{url('add_product')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Product</button></a>

              @if($email=='appliances@gmail.com')
              <a href="{{url('exportappliances')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Export Appliances</button></a>

              @elseif($email=='accessories@gmail.com')
              <a href="{{url('exportaccessories')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Export Accessories</button></a>

              @else
              <a href="{{url('exportproduct')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Export</button></a>
              @endif

              <form method="post" action="{{ url('importproduct') }}" enctype="multipart/form-data"style="display:flex;">
               <input type="file" name="product" required />
                <button type="submit" class="btn btn-info waves-effect waves-light m-l-5">Import</button>
                  @csrf
               </form>
          </div> -->
          <h4 class="page-title">All Products</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card-box">
           <form method="POST" action="{{url('products')}}" >  

            {{ csrf_field() }}
            
           
            <div class="row">
              
              <div class="col-lg-3">
                <select class="form-control select2" name="brand">
                  <option value="">Select Supplier</option>
                  @foreach($brand as $item)
                    <option value="{{$item->id}}">{{$item->brandname}}</option>
                  @endforeach
                </select>
              </div>
              <!-- <div class="col-lg-3">
                <select class="form-control select2" name="category">
                  <option value="">Select Category</option>
                  @foreach($category as $item)
                    <option value="{{$item->id}}">{{$item->catname}}</option>
                  @endforeach
                </select>
              </div> -->
             <!--  <div class="col-lg-3">
                <select class="form-control select2" name="subcategory">
                  <option value="">Select Subcategory</option>
                  @foreach($category as $cat)
                    @php
                      $subcategory=App\Models\Subcategory::where('catid',$cat->id)->first();
                    @endphp
                     @if(!empty($subcategory))
                     <option value="{{$subcategory['id']}}">{{$subcategory['catname']}}</option>
                     @endif
                  @endforeach
                </select>
              </div> -->
              
              <!-- <div class="col-lg-2">
                <p style="float: right;margin: 0;">
                  <input  type="search" id="post-search-input" name="search" value="" style="float: left;margin: 0 4px 0 0;border:1px solid #E3E3E3" class="form-control" placeholder="Search Product">
                </p>
              </div> -->
              <div class="col-lg-1"> 
                <input type="submit" id="search-submit" class="btn btn-primary" value="Search">
              </div>
            </div>
           
          </form> <br>


            <div class="table-responsive">
              <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th style="text-align: center;">Image</th>
                  <th style="text-align: center;">Name </th>
                  <!-- <th style="text-align: center;">Category</th>
                  <th style="text-align: center;">Status</th> -->
                  <th style="text-align: center;">Active</th>
                  <th style="text-align: center;">New Arrival</th>
                  <th style="text-align: center;">Sale</th>
                  <th style="text-align: center;">Most Selling</th>
                  <th style="min-width: 80px;text-align: center;">Action</th>
                  <!-- <th style="min-width: 80px;text-align: center;">View Product</th> -->
                </tr>
              </thead>
              <tbody>
                @if(count($items_array)>0)
                @foreach($items_array as $items)
                  <tr>
                  @if(!empty($items->thumbnail))
                  <td style="text-align:center;"><img  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" class=" img-rounded" style="width:50px;height: 50px"> </td>
                  @else
                  <td>No Image Available</td>
                  @endif
                  <td style="width:200px">{{$items->name}}</td>
                  <!-- <td style="text-align:center;">
                  @if(!empty($items->category))
                  @php
                  $cat_data=\App\Models\Category::where('id',$items->category)->first();
                  if(!empty($cat_data))
                  {
                    echo $cat_data->catname;
                  }
                  else
                  {
                    echo "No Category Assigned ";
                  }
                  @endphp
                  @endif
                  </td> -->
                  <!--   <td style="text-align:center;">@if($items->is_active=="online")  <span class="label label-success">Active</span> @else<span class="label label-danger">In-Active</span> @endif</td> -->
                  <td style="text-align:center;">
                    <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->
                     <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <input type="checkbox" id="newarrival" class="newarrival" name="newarrival"  value="{{$items->slug}}" @if($items->newarrival=="on") checked="" @endif data-plugin="switchery" data-color="#f05050" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <!--  <input type="checkbox" checked data-plugin="switchery" data-color="#1E539C" data-size="small"/> -->
                    <input type="checkbox" id="sale" class="sale" name="sale"  value="{{$items->slug}}" @if($items->sale=="on") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <input type="checkbox" id="most_selling" class="most_selling" name="most_selling"  value="{{$items->slug}}" @if($items->most_selling=="on") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                  <a href="{{url('edit-products/'.$items->slug.'/'.$pageid)}}"  class="table-action-btn"><i class="md md-edit"></i></a>
                  <a href="{{url('deletemyproduct/'.$items->slug)}}" class="table-action-btn"><i class="md md-close"></i></a>
                  </td>
                  <!--   <td style="text-align:center;">
                  <a href="#"  target="_blank">View Products</a>
                  </td> -->
                  </tr>
                  @endforeach
                  @else
                  <tr><td>No Products Found!</td></tr>
                  @endif
                  </tbody>
                  </table>
                
                  <!-- {!! $items_array->appends(Request::except('page'))->render() !!} -->
              
                  {{$items_array->links('pagination::bootstrap-4')}}
               
                </div>
            </div>
          </div> <!-- end col -->
        </div>
      </div> <!-- container -->
  </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@push('custom-scripts')
<script>
$(document).on('change', '.newarrival', function() {
  var my_id = $(this).val(); 
  //alert(my_id);
  if(this.checked) 
  {
    var status="on";  
    //alert("Checked");
  }
  else
  {
    var status="off";
    ///alert("UnChecked");
  } 
  $.ajax({
        url:"{{ route('newarrival') }}",
        method:"POST",
        data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
        success:function (data) 
        {
        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
        } 
    });
});
</script>

<script>
$(document).on('change', '.sale', function() {

  var my_id = $(this).val(); 
  //alert(my_id);
  if(this.checked) 
  {
    var status="on";  
    //alert("Checked");
  }
  else
  {
    var status="off";
    ///alert("UnChecked");
  } 
  $.ajax({

    url:"{{ route('sale') }}",
    method:"POST",
    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
    success:function (data) 
    {
       $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
    } 
  });
});


$(document).on('change', '.most_selling', function() {
  var my_id = $(this).val();
  //alert(my_id);
  if(this.checked) 
  {
    var status="on";  
    //alert("Checked");
  }
  else
  {
    var status="off";
    ///alert("UnChecked");
  } 
$.ajax({
  url:"{{ route('most_selling') }}",
  method:"POST",
  data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
  success:function (data) 
  {
    $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
  } 
  });
});
</script>



<script>

$(document).on('change', '.active', function() {
  var my_id = $(this).val(); 
  //alert(my_id);
  if(this.checked) 
  {
    var status="online";  
    //alert("Checked");
  }
  else
  {
    var status="offline";
     ///alert("UnChecked");
  } 
$.ajax({
    url:"{{ route('active') }}",
    method:"POST",
    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
    success:function (data) 
    {
      $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
    } 
  });
});
</script>

@endpush
@endsection