@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif
@php
$headersettings=\App\Models\Header_setting::where('id','1')->first();
$settings=\App\Models\General_setting::where('id',1)->first();
$orders=\App\Models\Cartorder::where('orderid',$items->orderid)->get();
$homesettings=\App\Models\Homepage_setting::where('id','1')->first();
$total=0;
@endphp
@extends('layouts.app')

@section('content')

	 <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                               <!--  <div class="btn-group pull-right m-t-15">
                                    <button type="button" class="hidden-print btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu drop-menu-right" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
 -->
                                <h4 class="page-title">Invoice</h4>
                                <!-- <ol class="breadcrumb">
                                    <li><a href="#">Ubold</a></li>
                                    <li><a href="#">Extras</a></li>
                                    <li class="active">Invoice</li>
                                </ol> -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <!-- <div class="panel-heading">
                                        <h4>Invoice</h4>
                                    </div> -->
                                    <div class="panel-body">
                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <h4 class="text-right"><img src="{{ URL::asset('upload/logo/'.$headersettings->headerlogo) }}" alt="{{$headersettings->headerlogo}}" ></h4>
                                                
                                            </div>
                                            <div class="pull-right">
                                                <h4>Invoice # <br>
                                                    <strong>{{$items->orderid}}</strong>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                
                                                <div class="pull-left m-t-30">
                                                    <address>
                                                      <strong>{{$settings->site_name}}</strong><br>
                                                      {{$settings->address}}<br>
                                                      <abbr title="Phone">P:</abbr> {{$settings->phone}}
                                                      </address>
                                                </div>
                                                <div class="pull-right m-t-30">
                                                    <p><strong>Order Date: </strong> {{$items->orderdate}} </p>
                                                    <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-pink">{{$items->orderstatus}}</span></p>
                                                    <p class="m-t-10"><strong>Order ID: </strong>{{$items->orderid}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-h-50"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table m-t-30">
                                                        <thead>
                                                            
                                                            <th>Item</th>
                                                            <th>Description</th>
                                                            <th>Quantity</th>
                                                            <th>Unit Cost</th>
                                                            <th>Total</th>
                                                        </tr></thead>
                                                        <tbody>

                                                            @foreach($orders as $order)
                                                            @php
                                                            $total+=$order->price*$order->quantity;
                                                            @endphp
                                                            <tr>
                                                               
                                                                <td><b>{{$order->modelno}}</b>-{{$order->name}}  </td>
                                                                <td>
                                                                    @if(!empty($order->specification))
                                                                    @php echo $order->specification; @endphp
                                                                    @else
                                                                    No Description Available 
                                                                    @endif
                                                                </td>
                                                                <td>{{$order->quantity}}</td>
                                                                <td>{{$order->price}}</td>
                                                                <td>{{$order->price*$order->quantity}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="border-radius: 0px;">
                                            <div class="col-md-3 col-md-offset-9">
                                                <p class="text-right"><b>Sub-total:</b>{{$total}}</p>
                                             <!--    <p class="text-right">Discout: 12.9%</p> -->
                                                <p class="text-right">Shipping: {{$items->shippingprice}}</p>
                                                <hr>
                                                <h3 class="text-right">{{$homesettings->currencysymbol}} {{(int)$total+(int)$items->shippingprice}}</h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="hidden-print">
                                            <div class="pull-right">
                                                <a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                <a href="#" class="btn btn-primary waves-effect waves-light">Submit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- container -->
                               
                </div> <!-- content -->

               

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