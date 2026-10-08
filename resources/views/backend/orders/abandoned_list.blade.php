@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif
@php
$homesettings=\App\Models\Homepage_setting::where('id','1')->first();
@endphp
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
                               <!--  <div class="btn-group pull-right m-t-15">
                                    <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu drop-menu-right" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div> -->

                                <h4 class="page-title m-b-10">Orders</h4>
                                <!-- <ol class="breadcrumb">
                                    <li><a href="#">Ubold</a></li>
                                    <li><a href="#">eCommerce</a></li>
                                    <li class="active">Orders</li>
                                </ol> -->
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row m-t-10 m-b-10">
                                        <!-- <div class="col-sm-6 col-lg-8">
                                            <form role="form">
                                                <div class="form-group contact-search m-b-30">
                                                    <input type="text" id="search" class="form-control" placeholder="Search...">
                                                    <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                                                </div> 
                                            </form>
                                        </div> -->

                                      <!--   <div class="col-sm-6 col-lg-4">
                                            <div class="h5 m-0">
                                                <span class="vertical-middle">Sort By:</span>
                                                <div class="btn-group vertical-middle" data-toggle="buttons">
                                                     <label class="btn btn-white btn-md waves-effect active">
                                                        <input type="radio" autocomplete="off" checked=""> Status
                                                     </label>
                                                     <label class="btn btn-white btn-md waves-effect">
                                                        <input type="radio" autocomplete="off"> Type
                                                     </label>
                                                     <label class="btn btn-white btn-md waves-effect">
                                                        <input type="radio" autocomplete="off"> Name
                                                     </label>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-actions-bar" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Order Date</th>
                                                    <!-- <th>Order Number</th> -->
                                                    <!-- <th>Customer</th> -->
                                                    <th>Amount</th>
                                                    <th style="min-width: 80px;">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @if(count($items_array)>0)
                                                @foreach($items_array as $items)
                                                <tr>
                                                    <td>
                                                        
                                                        <img  class="thumb-sm"  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}"
                                                            alt="{{$items->name}}"> <br>
                                                            <a href="{{url('product/'.$items->slug)}}" style="font-size: 12px;"> {{$items->name}}</a>
                                                    </td>
                                                    <td>{{$items->orderdate}}</td>
                                                    <!-- <td>{{$items->orderid}}</a></td> -->
                                                 <!--    <td>
                                                        <a href="" class="text-dark"><b>{{$items->customer_name}}</b></a>
                                                    </td> -->
                                                    <td>{{$homesettings->currencysymbol}} {{$items->price}}</td>
                                                    <td>
                                                        <a href="{{url('abandoned_orderdetails/'.$items->id)}}"   class="table-action-btn"><i class="md md md-edit"></i></a>
                                                       <!--  <a href="{{url('printinvoice/'.$items->orderid)}}"   class="table-action-btn"><i class="md md-local-print-shop"></i></a> -->

                                                        <!--  <a href="#" class="table-action-btn"><i class="md md-view-headline"></i></a> -->

                                                        <a href="{{url('deleteorder/'.$items->id)}}"   class="table-action-btn"><i class="md md-close"></i></a>

                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <p>No Orders yet !</p>
                                                @endif

                                            </tbody>
                                        </table>
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


<script>
    


$('#saveshipping').on('click',function(e) {

        
        var cityname=$("#city").val();
        var shippingcost=$("#cost").val();

        //alert(cityname+shippingcost);
        $.ajax({

            url:"{{ route('addshipping') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}","cityname": cityname,"shippingcost":shippingcost},
                success:function (data) 
                {
                console.log(data);

                swal("success",'Shipping Added Successfully !');
                $('#con-close-modal').modal('hide');
                location.reload();

                }

            })


});

$(document).on('click', '.editbutton', function() {
//$('.').on('click',function(e) {

       //var cityname=
        var id=$(this).val();
       //alert(id);

        $.ajax({

            url:"{{ route('findshipping') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}","id": id},
                success:function (data) 
                {
                console.log(data);

                //swal("success",'Shipping Added Successfully !');
                $("#updatecity").val(data['cityname']);
                $("#updatecost").val(data['shippingcost']);
                $('#editid').val(data['id']);
               
                }

            })



});



$(document).on('click', '.updateshipping', function() {
//$('.').on('click',function(e) {

    var cityname =$("#updatecity").val();
    var shippingcost=$("#updatecost").val();
    var id=$('#editid').val();
                
       //var id=$(this).val();
       //alert(id+cityname+shippingcost);

        $.ajax({

            url:"{{ route('updateshipping') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}","id": id,"cityname": cityname,"shippingcost":shippingcost},
                success:function (data) 
                {
                console.log(data);

                swal("success",'Shipping Added Successfully !');
               
                $('#edit-close-modal').modal('hide');
                location.reload();
                }

            })



});



$(document).on('click', '.deleteshipping', function() {
//$('.').on('click',function(e) {

/*    var cityname =$("#updatecity").val();
    var shippingcost=$("#updatecost").val();
    var id=$('#editid').val();
                */
       var id=$(this).val();
     //  alert(id);

        $.ajax({

            url:"{{ route('deleteshipping') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}","id": id},
                success:function (data) 
                {
                console.log(data);

                swal("success",'Deleted Successfully !');
               location.reload();
                
                }

            })



});

</script>
@endpush
@endsection