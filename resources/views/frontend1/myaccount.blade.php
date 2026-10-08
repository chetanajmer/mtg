@php
$homesettings=\App\Models\Homepage_setting::where('id','1')->first();
@endphp
@if(Session::has('userid'))

@else

<script>

window.location.href = "{{url('/store/login')}}";</script>

</script>   

@endif

@extends('layouts.frontapp')
@section('content')

<div class="container">
@if(Session::has('error'))
    <div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>
        @endif
        @if(Session::has('success'))
        <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>
        @endif
    </div>

<div class="my-account pt-30">

    <div class="container">

        <div class="row">

            <div class="col-12">

                <h3 class="title text-capitalize mb-30 pb-25">my account</h3>

            </div>

            <!-- My Account Tab Menu Start -->

            <div class="col-lg-3 col-12 mb-20">

                <div class="myaccount-tab-menu nav" role="tablist">
                    <ul class="list-none text-uppercase font-bold" role="tablist">
                        <li><a href="#dashboad" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li><a href="#orders" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i>  Orders</a></li>
                        <li class="active"><a href="#account-info" data-toggle="tab" class=""><i class="fa fa-user"></i> Account Details</a></li>
                        <li><a href="#rewards" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i>  Reward Points</a></li>
                        <li><a href="{{url('/store/dologout')}}"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>

                </div>

            </div>

            <!-- My Account Tab Menu End -->



            <!-- My Account Tab Content Start -->

            <div class="col-lg-9 col-12 mb-20">

                <div class="tab-content" id="myaccountContent">

                    <!-- Single Tab Content Start -->

                    <div class="tab-pane fade" id="dashboad" role="tabpanel">

                        <div class="myaccount-content">

                            <h3>Dashboard</h3>

                            <div class="welcome mb-20">

                                <p>Hello, <strong>{{$userdetails->fname}} {{$userdetails->lname}}</strong> (If Not <strong>{{$userdetails->fname}} !</strong><a

                                        href="{{url('/store/dologout')}}"  class="logout"> Logout</a>)</p>

                            </div>



                            <p class="mb-0">From your account dashboard. you can easily check &amp; view your

                                recent orders, manage your shipping and billing addresses and edit your

                                password and account details.</p>

                        </div>

                    </div>

                    <!-- Single Tab Content End -->



                    <!-- Single Tab Content Start -->

                    <div class="tab-pane fade" id="orders" role="tabpanel">

                        <div class="default_div myaccount-content">

                            <h3>Orders</h3>

                            
                            <div class="default_div accordion_div">
                                 @if(count($items_array)>0)
                                  @foreach($items_array as $items)
                               <div class="set">
                                    <a href="javascript:void(0);"> Order ID: {{$items->orderid}} / Order Total: {{$homesettings->currencysymbol}} {{$items->price}}  <i class="fa fa-plus"></i></a>
                                    <div class="content">
                                        <div class="order_product">
                                          <span class="produt_image">
                                            <img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="">
                                          </span>
                                          <span class="orderproduct_info">
                                            <a href="{{url('product/'.$items->slug)}}" class="produt_part">{{$items->name}}</a>
                                            <span class="specifi_info">Options: {{$items->variation}}</span>
                                            <!-- <span class="specifi_info">Size: 2XL</span> -->
                                            <span class="specifi_info">Price: {{$homesettings->currencysymbol}} {{$items->price}}</span>
                                            <span class="specifi_info">Order Status: {{$items->orderstatus}}</span>
                                            <!-- <span class="specifi_info item_cancell">Order Status: Cancelled</span>
                                            <span class="specifi_info item_deliver">Order Status: Deliver</span> -->
                                          </span>
                                        </div>
                                    </div>
                                </div>
                                 @endforeach
                                  @endif
                            </div>

                        </div>

                    </div>

                    <!-- Single Tab Content End -->


                     <div class="tab-pane fade" id="rewards" role="tabpanel">

                        <div class="default_div myaccount-content">
                            @php
                            $sum=0;
                            $reward=0;
                            $rewardpoints=json_decode(json_encode($items_array, true));
                            foreach($rewardpoints as $key=>$value)
                            {
                                $sum=$value->reward_points;
                                $reward+= $sum;
                            }@endphp
                            <h3>Total Reward Points : @php echo $reward @endphp </h3>
                            
                          
                        </div>

                    </div>


                    <!-- Single Tab Content Start -->

                    <div class="tab-pane active show" id="account-info" role="tabpanel">

                        <div class="myaccount-content">

                            <h3>Account Details</h3>



                            <div class="account-details-form">

                                <form action="#" id="addressform">
                                    <input type="hidden" name="id" value="">
                                    <div class="row">

                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="first-name" placeholder="First Name" value="{{$userdetails->fname}}"  name="fname" type="text">

                                        </div>



                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="last-name" value="{{$userdetails->lname}}"  name="lname" placeholder="Last Name" type="text">

                                        </div>


                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="email" name="email" placeholder="Email Address" value="{{$userdetails->email}}" type="email">

                                        </div>

                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="number" name="mobile" value="{{$userdetails->mobile}}" placeholder="Phone Number" type="number">

                                        </div>

                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="pass" name="password" value="{{$userdetails->password}}" placeholder="Password" type="text">

                                        </div>


                                        <div class="col-12 mb-30">

                                            <h4>Address Details</h4>

                                        </div>



                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="current-pwd" value="{{$userdetails->flatno}}"  name="flatno" placeholder="Flat no." type="text">

                                        </div>



                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="new-pwd" name="city" value="{{$userdetails->city}}"  placeholder="City" type="text">

                                        </div>


                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="new-pwd" value="{{$userdetails->region}}"  name="region" placeholder="State/Region" type="text">

                                        </div>


                                        <div class="col-lg-6 col-12 mb-20">

                                            <input id="new-pwd" value="{{$userdetails->country}}"  name="country" placeholder="Country" type="text">

                                        </div>


                                        <div class="col-lg-12 col-12 mb-20">

                                            <input id="confirm-pwd" value="{{$userdetails->address}}"  name="address" placeholder="Address" type="text">

                                        </div>



                                        <div class="col-12">

                                            <button type="button" class="btn theme-btn">Save

                                                Changes</button>

                                        </div>



                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    <!-- Single Tab Content End -->

                   

                </div>

            </div>

            <!-- My Account Tab Content End -->

        </div>

    </div>

</div>




@endsection