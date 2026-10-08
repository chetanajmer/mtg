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
          <h4 class="page-title">Instock Notifier</h4>
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
              <th>Product</th>
              <th>Status</th>
              </tr>
              </thead>
              <tbody>
              @if(count($items_array)>0)
                @foreach($items_array as $items)
                <tr>
                <td>{{$items->name}}</td>
                <td>{{$items->email}}</td>
                <td>{{$items->product}}</td>
                @if($items->status=='subscribed')
                  <td><span class="label label-primary">Subscribed</span></td>
                @else
                  <td><span class="label label-success">Email Sent</span></td>
                @endif
                </tr>
                @endforeach
              @else
                <tr>
                  <td>No Subscriber</td>
                </tr>
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



            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->





@endsection