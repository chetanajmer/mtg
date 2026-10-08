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
                                <h4 class="page-title">Enquiry Details</h4><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                 <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                 <h5 class="text-muted text-uppercase m-t-0 m-b-20">
                                                    <b>Enquiry Detail</b>
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table  table-bordered">
                                                        <thead >
                                                            <tr >
                                                            <th style="text-align: center">Image</th>
                                                            <th style="text-align: center">Model No</th>
                                                            <th style="text-align: center">Item</th>
                                                            <th style="text-align: center">Colors / Quantity</th>
                                                            <th style="text-align: center">Total Quantity</th>
                                                            <!-- <th style="text-align: center">Price</th> -->
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                          @foreach($orders as $order)
                                                            @php

                                                              $subtotal+=$order->quantity*$order->price;
                                                              $main_colors=App\Models\Color::get();
                                                          @endphp

                                                            <tr style="text-align: center">
                                                              <td>
                                                                 @php
                                                                      $split_modelno=str_split($order->modelno,3);
                                                                    @endphp
                                                                    
                                                                    @if($split_modelno[0]=='SGM')
                                                                       <img  class=""  src="{{$order->thumbnail}}"
                                                                          alt="{{$order->name}}" height="80" width="80">
                                                                    @else
                                                                       <img  class=""  src="{{ URL::asset('upload/product/thumbnail/'.$order->thumbnail) }}"
                                                                          alt="{{$order->name}}" height="80" width="80">

                                                                    @endif
                                                              </td>
                                                              <td>{{$order->modelno}}</td>
                                                              <td>{{$order->name}} </td>
                                                              <td style="text-align: center;"><?php 
                                                                if($order->color=='[]')
                                                                {
                                                                  echo "NA";
                                                                }
                                                                else
                                                                {
                                                                  $colors=json_decode($order->color);
                                                                    $color=array();
                                                                    foreach($colors as $val)
                                                                    {
                                                                      $get_color=explode('_',$val);
                                                                      $color=$get_color[0];
                                                                      $qty=$get_color[1];
                                                                      $main_colors=App\Models\Color::get();
                                                                      foreach($main_colors as $main_colors)
                                                                      {
                                                                        $colorcode=$main_colors->code;
                                                                        $colorname=$main_colors->name;
                                                                        if($color==$colorcode)
                                                                        { ?>
                                                                          <ul style="list-style-type: none;padding-inline-start: inherit;">
                                                                            <li style="background-color: {{$colorcode}};width: 12px;height: 12px;border: 1px #ddd solid;border-radius: 12px;"></li>
                                                                            <li style="margin-top:-15px;padding-left:15px"> {{$colorname}} / {{$qty}}</li>
                                                                           
                                                                          </ul> 
                                                                         

                                                              <?php     } 


                                                                      
                                                                      }
                                                                    }
                                                               
                                                                }  ?>
                                                                    </td>
                                                              <td>{{$order->quantity}}</td>
                                                               <!-- <td>{{$order->price}}</td> -->
                                                            </tr>
                                                            @endforeach
                                                          
                                                            <!-- <tr>
                                                                <td colspan="3" style="text-align: right;font-weight: 600;">Subtotal</td>
                                                                <td style="text-align: center">{{$subtotal}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" style="text-align: right;font-weight: 600;">Vat</td>
                                                                <td style="text-align: center">5%</td>
                                                            </tr>
                                                            @php $vat=0.05; 
                                                            $total=$subtotal+$vat;
                                                            @endphp
                                                            <tr>
                                                                <td colspan="3" style="text-align: right;font-weight: 600;">Total</td>
                                                                <td style="text-align: center">{{$subtotal}}</td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                    <!-- <a href="@if(!empty($orders[0]->orderid)){{url('printinvoice/'.$orders[0]->orderid)}}@endif" id="update" class="btn w-sm btn-default waves-effect waves-light" style="margin-top: 20px;float: right">Print Invoice</a> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div>

                               <!-- <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="text-muted text-uppercase m-t-0 m-b-20">
                                                    <b>Customer Information</b>
                                                </h5>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Name:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_name)){{$orders[0]->customer_name}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Email:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_email)){{$orders[0]->customer_email}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Mobile:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_phone)){{$orders[0]->customer_phone}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Address:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_address)){{$orders[0]->customer_address}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Landmark:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_landmark)){{$orders[0]->customer_landmark}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer City:</label>
                                                       <input type="text" class="form-control"  value="@if(!empty($orders[0]->customer_city)){{$orders[0]->customer_city}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer State:</label>
                                                       <input type="text" class="form-control"  value="@if(!empty($orders[0]->customer_state)){{$orders[0]->customer_state}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Pincode:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_pincode)){{$orders[0]->customer_pincode}}@endif">
                                                     </div>
                                                </div>
                                               
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> -->
                                <!-- <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="text-muted text-uppercase m-t-0 m-b-20">
                                                    <b>Order Information</b>
                                                </h5>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Order ID:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->orderid)){{$orders[0]->orderid}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Payment Mode:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->payment_mode)){{$orders[0]->payment_mode}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Order Status:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->orderstatus)){{$orders[0]->orderstatus}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Order Date:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->orderdate)){{$orders[0]->orderdate}}@endif">
                                                     </div>
                                                </div>

                                                
                                               
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> -->
                            </div>

                            
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="text-muted text-uppercase m-t-0 m-b-20">
                                                    <b>Customer Information</b>
                                                </h5>

                                                <div class="col-md-12">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Name:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_name)){{$orders[0]->customer_name}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Email:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_email)){{$orders[0]->customer_email}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Mobile:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_phone)){{$orders[0]->customer_phone}}@endif">
                                                     </div>
                                                </div>

                                                 <div class="col-md-12">
                                                    <div class="form-group m-b-20">
                                                      <label>Enquiry Date:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->created_at)){{$orders[0]->created_at}}@endif">
                                                     </div>
                                                </div>

                                                <!-- <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Address:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_address)){{$orders[0]->customer_address}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Landmark:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_landmark)){{$orders[0]->customer_landmark}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer City:</label>
                                                       <input type="text" class="form-control"  value="@if(!empty($orders[0]->customer_city)){{$orders[0]->customer_city}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer State:</label>
                                                       <input type="text" class="form-control"  value="@if(!empty($orders[0]->customer_state)){{$orders[0]->customer_state}}@endif">
                                                     </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                      <label>Customer Pincode:</label>
                                                       <input type="text" class="form-control" value="@if(!empty($orders[0]->customer_pincode)){{$orders[0]->customer_pincode}}@endif">
                                                     </div>
                                                </div> -->
                                               
                                            </div> <!-- col -->
                                        </div> <!-- row -->  
                                    </div> <!-- panel body -->
                                </div><!-- panel -->
                            </div>
                             







                        </div> 

                    </div> <!-- container -->
                               
                </div> <!-- content -->

               


@endsection