

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
                                
                                <h4 class="page-title">Date Report</h4>
                                <ol class="breadcrumb">
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-12">
                        		<div class="card-box">
                        	       <form name="filters" action="{{url('date_report')}}"  method="post" id="filters">
                                                {{ csrf_field() }} 
                                                <div class="row">
                                                  
                                                   <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="date" name="from_date" id="from_date" class="form-control" >
                                                    </div>
                                                  </div>
                                                   <div class="col-md-4">
                                                    <div class="form-group">
                                                         <input type="date" name="to_date" id="to_date" class="form-control" >
                                                    </div>
                                                  </div>
                                                  <div class="col-md-4" style="text-align:right;">      
                                                    <button type="submit" class="btn btn-primary " >Search</button>
                                                  </div>  

                                    </form>
                                </div>

                        			<div class="table-responsive" >
                                            
                                       <table class="table table-actions-bar" >
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Order Id</th>
                                                    <th style="text-align: center;">Order Date</th>
                                                    <th style="text-align: center;">UserName</th>
                                                    <th style="text-align: center;">Order Status</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($orders)>0)
                                                @foreach($orders as $items)
                                                <tr>
                                                    <td style="text-align:center;">{{$items->orderid}}</td>
                                                    <td style="text-align:center;">{{$items->orderdate}}</td>
                                                    <td style="text-align:center;">{{$items->customer_name}}</td>
                                                    <td style="text-align:center;">{{$items->orderstatus}}</td>
                                                    <td style="text-align:center;">
                                                        <a href=""  class="table-action-btn"><i class="md md-edit"></i></a>
                                                        <a href="{{url('printinvoice/'.$items->orderid)}}" class="table-action-btn"><i class="md md-local-print-shop"></i></a>
                                                        <a href="{{url('deleteorder/'.$items->orderid)}}" class="table-action-btn"><i class="md md-close"></i></a>
                                                    </td>
                                                </tr>

                                                @endforeach

                                                @else

                                                <tr>
                                                    <td>No Report Found !</td>
                                                </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                            {{$orders->links('pagination::bootstrap-4')}}     
                                    </div>
                        		</div>
                            </div> <!-- end col -->
                    </div>
                </div> <!-- container -->         
            </div> <!-- content -->
            




            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->



@push('custom-scripts')




@endpush

@endsection