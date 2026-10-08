@php
$subtotal=0;
$headersettings=\App\Models\Header_setting::where('id','1')->first();
$settings=\App\Models\General_setting::where('id',1)->first();
@endphp

@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif

@extends('layouts.app')

@section('content')

	 <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Order Details</h4><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                 <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                 <h5 class="text-muted text-uppercase m-t-0 m-b-20">
                                                    <b>Order Detail</b>
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table  table-bordered">
                                                        <thead >
                                                            <tr >
                                                            <!-- <th style="text-align: center">Order ID</th> -->
                                                            <th style="text-align: center">Item</th>
                                                            <th style="text-align: center">Quantity</th>
                                                            <th style="text-align: center">Price</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                          @foreach($orders as $order)
                                                            @php

                                                              $subtotal+=$order->quantity*$order->price;

                                                          @endphp

                                                            <tr style="text-align: center">
                                                               <!-- <td>{{$order->orderid}}</td> -->
                                                               <td>{{$order->name}}</td>
                                                               <td>{{$order->quantity}}</td>
                                                               <td>{{$order->price}}</td>
                                                            </tr>
                                                            @endforeach
                                                          
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;font-weight: 600;">Subtotal</td>
                                                                <td style="text-align: center">{{$subtotal}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;font-weight: 600;">Vat</td>
                                                                <td style="text-align: center">5%</td>
                                                            </tr>
                                                            @php $vat=0.05; 
                                                            $total=$subtotal+$vat;
                                                            @endphp
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;font-weight: 600;">Total</td>
                                                                <td style="text-align: center">{{$subtotal}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <a  class="btn w-sm btn-default waves-effect waves-light" style="margin-top: 20px;float: right" onclick="send_abandoned_mail('{{$order->userid}}')">Send Email</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>

                        </div> 

                    </div> <!-- container -->
                               
                </div> <!-- content -->

               

<script>
function send_abandoned_mail($id)
{
  $.ajax({
          type:"POST",
          url:"{{ route('send_abandoned_mail') }}",
          method:"POST",
          data: {"_token": "{{ csrf_token() }}","id":$id
          },

          success:function (data)
          {
            console.log(data);
            // $.Notification.notify('success','top right', 'Success ', "Email Sent Successfully !");
            // location.reload();
          } 

        });

    }
</script>
@endsection