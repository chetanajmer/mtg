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
                                <div class="btn-group pull-right m-t-15" style="display:flex;">
                                <a href="{{url('exportstoreuser')}}"> 
                                  <button type="button" class="btn btn-info waves-effect waves-light">Export</button>
                                </a>
                            </div>
                                <h4 class="page-title">All Store Users</h4>
                                <ol class="breadcrumb"></ol>
                            </div>
                        </div>
                        
                        <div class="row">
                        	<div class="col-lg-12">
                        		<div class="card-box">
                        	        <div class="row m-t-10 m-b-10">
                                        <div class="col-sm-10 col-lg-10">
                                           <!-- <form role="form">
                                                <div class="form-group contact-search m-b-30">
                                                    <input type="text" id="search" class="form-control" placeholder="Search...">
                                                    <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                                                </div>
                                            </form> -->
                                        </div>

                                        <!-- <div class="col-sm-2 col-lg-2">
                                            <div class="h5 m-0">
                                               <a href="{{url('show-slider')}}"> <button type="button" class="btn btn-info waves-effect waves-light"></button></a>
                                            </div>
                                        </div> -->
                                    </div>


                        			<div class="table-responsive">
                                        <table class="table table-actions-bar">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($items_array as $items)
                                                <tr>
                                                    <td>{{$items->fname}} {{$items->lname}}</td>
                                                    <td>{{$items->email}}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                        		</div>
                                
                            </div> <!-- end col -->

                            
                        </div>

                        
                        
                        

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                

            </div>

            
          



    

@push('custom-scripts')



@endpush
@endsection