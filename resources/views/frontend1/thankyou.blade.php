@extends('layouts.frontapp')

@section('content')



<!-- breadcrumb-section start -->

<div class="wrap-bread-crumb">
            <div class="container">
                <div class="bread-crumb">
                    <a href="{{url('/')}}">Home</a>
                    <strong>Thank You</strong>
                </div>
            </div>
        </div>

<!-- breadcrumb-section end -->
<div class="container">
@if(Session::has('error'))

    <div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>

@endif

@if(Session::has('success'))

    <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>

@endif
</div>


<div class="my-account pt-20 pb-20">

    <div class="container">

        <div class="row">

            <div class="col-12 thank_page">
                <h3 class="mb-20">Thank You ! </h3>
               <!--  <i class="fa fa-check" aria-hidden="true"></i> -->
                <p>Your order is Placed ! Your order id is <strong>{{$orderid}}</strong></p>

                @php

                session()->forget('sessionid');

                @endphp

            </div>

        </div>

    </div>

</div>



@endsection