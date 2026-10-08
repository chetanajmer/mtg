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

                              

                                 <div class="btn-group pull-right m-t-15">

                                    <a href="{{url('show-slider')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Slider</button></a>

                                  

                                </div>

                                <h4 class="page-title">All Sliders</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                        	     



                        			<div class="table-responsive">

                                        <table class="table table-actions-bar">

                                            <thead>

                                                <tr>

                                                    <th>Image</th>

                                                    <th>Name</th>

                                                    <th>Ranking</th>

                                                    

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody>



                                                @foreach($items_array as $items)

                                                <tr>

                                                    <td><img  src="{{ URL::asset('upload/slider/'.$items->sliderimage) }}" class=" img-rounded" style="width:80px;"> </td>

                                                    <td>{{$items->slidername}}</td>

                                                    <td>{{$items->ranking}}</td>

                                                     <td>

                                                        <a href="{{url('edit-slider/'.$items->slug)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                                                        <a href="{{url('deleteslider/'.$items->slug)}}" class="table-action-btn"><i class="md md-close"></i></a>

                                                    </td>

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



            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->





@endsection