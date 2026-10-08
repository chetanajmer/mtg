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
              <a href="{{url('add-portfolio')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Portfolio</button></a>
          </div>
          <h4 class="page-title">All Portfolio's</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>

      <div class="row" id="filterproducts">
        <div class="col-lg-12">
          <div class="card-box">
            <form method="POST" action="{{url('list-portfolio')}}" >  {{ csrf_field() }}
              <div class="row">
                <div class="col-lg-8"></div>
                  <div class="col-lg-3">
                    <p style="float: right;margin: 0;">
                      <input  type="search" id="post-search-input" name="search" value="" style="float: left;margin: 0 4px 0 0;border:1px solid #E3E3E3" class="form-control" placeholder="Search Portfolio">
                    </p>
                  </div>
                  <div class="col-lg-1"> 
                    <input type="submit" id="search-submit" class="btn btn-primary" value="Search">
                  </div>
            </div></form> <br>
            <div class="table-responsive">
              <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Logo</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Subcategory</th>
                  <th style="text-align: center;">Active</th>
                  <th style="text-align: center;">Featured</th>
                  <th style="min-width: 80px;text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($items_array as $items)
                  <tr>
                  @if(!empty($items->logo))
                  <td >
                    <img  src="{{ URL::asset('upload/portfolio/logo/'.$items->logo) }}" class=" img-rounded" style="width:50px;height: 50px">
                  </td>
                  @else
                  <td>No Image Available</td>
                  @endif
                  <td>{{$items->name}}</td>
                  @php
                    $category=App\Models\Portfolio_category::where('id',$items->category)->first();
                  @endphp
                  <td>{{$category->name}}</td>
                  @php
                    $subcategory=App\Models\Portfolio_subcategory::where('id',$items->subcategory)->first();
                    print_r($subcategory);
                  @endphp
                  <td>
                    @if(!empty($items->subcategory))
                      {{$subcategory->name}}
                    @else
                         No Subcategory Available!
                    @endif
                   
                  </td>
                  <td style="text-align:center;">
                    <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->
                     <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                  </td>
                   <td style="text-align:center;">
                    <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->
                     <input type="checkbox" id="featured" class="featured" name="featured"  value="{{$items->slug}}" @if($items->featured=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                  </td>
                  <td style="text-align:center;">
                  <a href="{{url('edit-portfolio/'.$items->slug.'/'.$pageid)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                  <a href="{{url('delete-portfolio/'.$items->slug.'/'.$pageid)}}" class="table-action-btn"><i class="md md-close" ></i></a>
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
  url:"{{ route('do_featured_portfolio') }}",
  method:"POST",
  type:'JSON',
  data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
  success:function (data) 
  {
    console.info(data.status);
    if(data.status=='error')
    {
      $.Notification.notify('error','top right', 'Error ', 'You Cannot featured portfolio more than 9');
    }
    if(data.status=='success')
    {
      $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
    }
    
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
    url:"{{ route('do_active_portfolio') }}",
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