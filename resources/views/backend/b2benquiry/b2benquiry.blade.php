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
          <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15" style="display:flex;">
              <a href="{{url('exportb2benquiry')}}"> 
                <button type="button" class="btn btn-info waves-effect waves-light">Export</button>
              </a>
          </div>
          <h4 class="page-title">All B2B Enquiry</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="card-box">
            <div class="row m-t-10 m-b-10">
              <div class="col-sm-10 col-lg-10">
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-actions-bar">
                <thead>
                <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Quantity</th>
                <th>Products</th>
                </tr>
                </thead>
                <tbody>
                @if(count($items_array)>0)
                  @foreach($items_array as $items)
                  <tr>
                  <td>{{$items->name}}</td>
                  <td>{{$items->email}}</td>
                  <td>{{$items->phone}}</td>
                  <td>{{$items->quantity}}</td>
                  <td><a href="https://silvergiftz.com/product/{{$items->product}}">View Product</a></td>
                  </tr>
                  @endforeach
                @else
                  <tr><td>No Enquiry Found!</td></tr>
                @endif
                </tbody>
              </table>
                {{$items_array->links('pagination::bootstrap-4')}}
            </div>
          </div>
        </div> <!-- end col -->
      </div>
    </div> <!-- container -->
  </div> <!-- content -->
 </div>
</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection