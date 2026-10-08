@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";</script>
</script>   
@endif


@php
$settings=\App\Models\General_setting::where('id',1)->first();
$headersettings=\App\Models\Header_setting::where('id','1')->first();
$productsettings=\App\Models\Product_setting::where('id','1')->first();
$headercategories=\App\Models\Category::where('is_active','online')->get()->take(6);
$footersettings=\App\Models\Footer_setting::where('id','1')->first();
$tmporders=\App\Models\Tmporder::all();
$cartorders=\App\Models\Cartorder::orderbyDesc('id')->get()->unique('orderid')->take(2);
$homesettings=\App\Models\Homepage_setting::where('id','1')->first();
$storeusers=\App\Models\Storeuser::all();

use Carbon\Carbon;



/*Online Users*/
$total_online_visitor_count=DB::table(config('session.table'))
      ->where('sessions.last_activity', '>', Carbon::now()->subMinutes(2)->getTimestamp())
      ->get()->count();
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
          <!-- <div class="btn-group pull-right m-t-15">
              <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                <ul class="dropdown-menu drop-menu-right" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div> -->
            <h4 class="page-title">Dashboard</h4>
            <p class="text-muted page-title-alt" style="color: #fff !important ;">Welcome to {{$settings->site_name}} admin panel !</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-info pull-left">
              <i class="md md-equalizer text-info"></i>
            </div>
            <div class="text-right">
              <h3 class="text-dark"><b class="counter">@if(!empty($total_online_visitor_count)){{$total_online_visitor_count}} @else 0 @endif</b></h3>
              <p class="text-muted">Total Online Visitor</p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-custom pull-left">
              <i class="md md-add-shopping-cart text-custom"></i>
            </div>
            <div class="text-right">
              <h3 class="text-dark"><b class="counter">{{count($tmporders)}}</b></h3>
              <p class="text-muted">Today's Sales</p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-info pull-left">
              <i class="md md-equalizer text-info"></i>
            </div>
            <div class="text-right">
              <h3 class="text-dark"><b class="counter">{{count($storeusers)}}</b></h3>
              <p class="text-muted">Customers</p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-custom pull-left">
              <i class="md md-remove-red-eye text-custom"></i>
            </div>
            <div class="text-right">
              <h3 class="text-dark"><b class="counter">{{$cartorders->sum('quantity')}}</b></h3>
              <p class="text-muted">Unit's Sold</p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
                    
      <h4 class="page-title">Google Analytics Data</h4>
      <p class="text-muted page-title-alt" style="color: #fff !important ;">To better understand your customers. Actual data from your Google Analytics Account</p>
      <div class="row">
         <div class="col-lg-12">
        <div class="card-box">
           <a href="{{url('usersbydate')}}" style="float: right;font-size: 15px">View All</a>
          <canvas id="line_chart" height="100"></canvas>

        </div>
      </div>
      
    </div>  
     <div class="row">
         <div class="col-lg-12">
        <div class="card-box">
          <canvas id="country_chart" height="135"></canvas>
        </div>
      </div>
    
    </div>  
     <div class="row">
         <div class="col-lg-6">
        <div class="card-box">
          <h4 class="text-dark header-title m-t-0">Most views by page</h4>
          <div class="table-responsive" style="overflow-y: scroll;height: 320px" >
              <table class="table table-actions-bar">
                <thead>
                  <tr>
                    <th>Page Url</th>
                    <th>Views</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($most_views_page)>0)
                    @foreach($most_views_page as $val)
                      <tr>
                        <td>{{$val['fullPageUrl']}}</td>
                        <td>{{$val['screenPageViews']}}</td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
                </table>
              </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card-box">
          <h4 class="text-dark  header-title m-t-0 m-b-30">Customers Statics (Last 7 Days)</h4>
            <div class="widget-chart text-center">
              <div id="sparkline5" ></div>
                <ul class="list-inline m-t-15">
                  <li>
                    <h5 class="text-muted m-t-20">New User</h5>
                    <h4 class="m-b-0">{{$newuser}}</h4>
                  </li>
                  <li>
                    <h5 class="text-muted m-t-20">Returning User</h5>
                    <h4 class="m-b-0">{{$returninguser}}</h4>
                  </li> 
                </ul>
              </div>
          </div>
      </div>
    
    </div>  
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <h4 class="text-dark header-title m-t-0">Latest orders</h4>
            <div class="table-responsive">
              <table class="table table-actions-bar">
                <thead>
                  <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Model No</th>
                    <th>Order Date</th>
                    <!-- <th>Order Number</th> -->
                    <th>Customer</th>
                    <!-- <th>Status</th>
                    <th>Amount</th> -->
                    <th style="min-width: 80px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($cartorders)>0)
                    @foreach($cartorders as $items)
                      <tr>
                        <td>
                          @php
                            $split_modelno=str_split($items->modelno,3);
                          @endphp
                          @if($split_modelno[0]=='SGM')
                            <img  class=""  src="{{$items->thumbnail}}" alt="{{$items->name}}" height="80" width="80">
                          @else
                            <img  class=""  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}"
                                                              alt="{{$items->name}}" height="80" width="80">
                          @endif      
                        </td>
                        <td> 
                          <a href="https://silvergiftz.com/product/{{$items->slug}}" style="font-size: 12px;"> {{$items->name}}</a></td>
                        <td>{{$items->modelno}}</td>
                        @php
                          $updated_at=date('d-M-Y h:i:s',strtotime($items->updated_at));
                        @endphp
                        <td>{{$updated_at}}</td>
                        <!-- <td>{{$items->orderid}}</a></td> -->
                        <td>
                          <a href="" class="text-dark"><b>{{$items->customer_name}}</b></a>
                        </td>
                        <!-- <td>
                          <span class="label label-success">{{$items->orderstatus}}</span>
                          </td>
                          <td>{{$homesettings->currencysymbol}} {{$items->price}}</td> -->
                        <td>
                          <!-- <a href="{{url('printinvoice/'.$items->orderid)}}"   class="table-action-btn"><i class="md md-local-print-shop"></i></a> -->
                          <!--  <a href="#" class="table-action-btn"><i class="md md-view-headline"></i></a> -->
                          <!--   <a href="{{url('deleteorder/'.$items->orderid)}}"   class="table-action-btn"><i class="md md-close"></i></a> -->
                          <a href="{{url('orderdetails/'.$items->orderid)}}"   class="table-action-btn"><i class="md md md-edit"></i></a>
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
          </div>
        </div>
      </div> <!-- container -->
    </div> <!-- content -->


@push('custom-scripts')

<!-- Chart js -->
<script type="text/javascript">

$( document ).ready(function() {

var DrawSparkline = function() {
    
    $('#sparkline3').sparkline(['200','300','400'], {
        type: 'pie',
        width: '165',
        height: '165',
        sliceColors: ['#dcdcdc', '#34d3eb', '#7e57c2']
    });


    $('#sparkline4').sparkline(['2000','3000'], {
        type: 'pie',
        width: '165',
        height: '165',
        sliceColors: ['#7E57C2', '#34D3EB']
    });

    
    $('#sparkline5').sparkline(['{{$newuser}}','{{$returninguser}}'], {
      type: 'pie',
      width: '200',
      height: '200',
      sliceColors: ['#7E57C2', '#34D3EB']
  });  
};


DrawSparkline();

var resizeChart;

$(window).resize(function(e) {
    clearTimeout(resizeChart);
    resizeChart = setTimeout(function() {
        DrawSparkline();
    }, 300);
});
});

</script>

<script>
  var chrt = document.getElementById("line_chart").getContext("2d");
  <?php
    $start_date=substr($user[0]['date'], 0, 4)."-".substr($user[0]['date'],4,2)."-".substr($user[0]['date'],6,2);
    $startdate=date('d-m-Y',strtotime($start_date));
    $end_date=substr($user[6]['date'], 0, 4)."-".substr($user[6]['date'],4,2)."-".substr($user[6]['date'],6,2);
    $enddate=date('d-m-Y',strtotime($end_date));
  ?>
  var chartId = new Chart(chrt, {
    type: 'line',
    data: {
            labels: [<?php 
                    foreach($user as $val)
                    {
                      $date=substr($val['date'], 0, 4)."-".substr($val['date'],4,2)."-".substr($val['date'],6,2);
                      $all_date=date('Y-m-d',strtotime($date));
                      echo "'".$all_date."'".',';
                    }

              ?>],
            datasets: [{
            label: "Users According to Date(<?php echo $startdate ?> - <?php echo $enddate ?>) " ,
            data: [<?php 
                    foreach($user as $val)
                    {
                      echo $val['totalUsers'].',';
                    }

              ?>],
            fill: false,
            backgroundColor: "rgba(126,87,195,1.0)",
            borderColor: "rgba(126,87,195,0.1)",
            lineTension: 0,
          }],
         },
        options: {
        scales: {
          yAxes: [{ticks: {min: 0, max:500}}],
         
        }
      }
      });
</script>
<script>
  var chrt = document.getElementById("country_chart").getContext("2d");
  var chartId = new Chart(chrt, {
    type: 'line',
    data: {
            labels: [<?php 
                    foreach($country as $val)
                    {
                      echo "'".$val['country']."'".',';
                    }

              ?>],
            datasets: [{
            label: "Users According to Country (Last 7 Days)" ,
            data: [<?php 
                    foreach($country as $val)
                    {
                      echo $val['totalUsers'].',';
                    }

              ?>],
            fill: false,
            backgroundColor: "rgba(0, 191, 255,1.0)",
            borderColor: "rgba(0,191,255,0.1)",
            lineTension: 0,
          }],
         },
        options: {
        scales: {
          yAxes: [{ticks: {min: 0, max:500}}],
        }
      }
      });
</script>
<!-- chart js / -->

@endpush
@endsection