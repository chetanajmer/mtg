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
            <div class="btn-group pull-right m-t-15"></div>
              <ol class="breadcrumb"></ol>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="card-box">
              <div class="table-responsive">
                <table class="table table-actions-bar" id="datatable">
                  <thead>
                    <tr>
                    <th>Search</th>
                    <th>Count</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($search)>0)
                      @foreach($search as $record)
                        <tr>
                          <td>{{$record->search_name}}</td>
                          @php
                            $search_count = \App\Models\Searchproduct::where('search_name',$record->search_name)->get();
                          @endphp
                          <td>{{count($search_count)}}</td>
                        </tr>
                      @endforeach
                    @else
                      <p>No data Available !</p>
                    @endif
                  </tbody>
                </table>
              </div>
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
<style>
    .dataTables_length {
 
    display: none!important;
}

#datatable_filter
{
     display: none!important;
}
</style>
@endsection