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
        <h4 class="page-title"></h4>
        <ol class="breadcrumb">
        </ol>
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
            <form method="post" action="{{ url('dobulkdeleteproducts') }}" enctype="multipart/form-data"style="display:flex;">
               <input type="file" name="product" required />
                <button type="submit" class="btn btn-info waves-effect waves-light m-l-5">Import</button>
                  @csrf
               </form>
          </div>
        </div>
      </div> <!-- end col -->
    </div>
    <div class="row">
    <div class="col-lg-12">
    <a href="{{url('listproduct')}}" class="m-r-5"> <button type="button" class="btn btn-info waves-effect waves-light">Back</button></a>
    </div>
    </div>
    </div> <!-- container -->
  </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->





@endsection