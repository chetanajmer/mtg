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



                                    <a href="{{url('add-coupon')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Coupon</button></a>



                                  



                                </div>







                                <h4 class="page-title">All Coupons</h4>



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

                                                    <th>Code</th>



                                                    <th>Coupon Type</th>



                                                    <th>Coupon Amount</th>



                                                    <th>Expiry Date</th>



                                                    <th>Limits</th>



                                                    <th>Status</th>



                                                    <th style="min-width: 80px;">Action</th>



                                                </tr>



                                            </thead>







                                            <tbody>







                                                @foreach($items_array as $items)



                                                <tr>



                                                    <td>

                                                        

                                                       {{$items->coupon_code}}



                                                    </td>



                                                    <td>

                                                        {{$items->coupon_type}}

                                                       

                                                    </td>



                                                    <td>{{$items->coupon_amount}}</td>

                                                     @php

                                                    $Date =$items->expiry_date;

                                                    $date = date("d-M-Y", strtotime($Date));

                                                    @endphp

                                                    @if(!empty($items->expiry_date))

                                                    <td>{{$date}}</td>

                                                    @else

                                                     <td>No Date Available</td>

                                                    @endif



                                                     @if(!empty($items->limits))

                                                    <td>{{$items->limits}}</td>

                                                    @else

                                                     <td>No Limit Available</td>

                                                    @endif



                                                    <td>@if($items->is_active=="online")  <span class="label label-success">Active</span> @else<span class="label label-danger">In-Active</span> @endif</td>



                                                     <td>



                                                        <a href="{{url('edit-coupon/'.$items->slug)}}"  class="table-action-btn"><i class="md md-edit"></i></a>



                                                        <a href="{{url('deletecoupon/'.$items->slug)}}" class="table-action-btn"><i class="md md-close"></i></a>



                                                    </td>



                                                </tr>



                                                @endforeach







                                            </tbody>



                                        </table>

                                        {!! $items_array->appends(Request::except('page'))->render() !!}

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