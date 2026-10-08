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
    <div class="container" >
    <!-- Page-Title -->
      <div class="row">
        <div class="col-sm-12">
           <div class="btn-group pull-right m-t-15" style="display:flex;">
              <a href="{{url('add-portfolio-category')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Category</button></a>
          </div>
          <h4 class="page-title">All Categories</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>

      <div class="row" id="filterproducts">
        <div class="col-lg-12">
          <div class="card-box">
          
            <div class="table-responsive">
              <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th >Name</th>
                  <th style="text-align: center;">Active</th>
                  <th style="min-width: 80px;text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($items_array as $items)
                  <tr>
                  <td>{{$items->name}}</td>
                  <td style="text-align:center;">
                    <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->
                     <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                  </td>
                  <td style="text-align:center;">
                  <a href="{{url('edit-portfolio-category/'.$items->slug.'/'.$pageid)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                  <a href="{{url('delete-portfolio-category/'.$items->slug.'/'.$pageid)}}" class="table-action-btn"><i class="md md-close" ></i></a>
                  <!-- <a  class="table-action-btn"><i class="md md-close" onclick="deletemyproduct('{{$items->slug}}')"></i></a> -->
                  </td>
                 
                  </tr>
                @endforeach
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
    var status="online";  
    //alert("Checked");
  }
  else
  {
    var status="offline";
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
    var status="online";  
    //alert("Checked");
  }
  else
  {
    var status="offline";
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
    var status="online";  
    //alert("Checked");
  }
  else
  {
    var status="offline";
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

$(document).on('change', '.featured', function() {
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
  url:"{{ route('featured') }}",
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
    url:"{{ route('do_active_portfolio_category') }}",
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