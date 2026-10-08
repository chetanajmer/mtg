@extends('layouts.frontapp')

@section('content')



<!-- breadcrumb-section start -->

<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>Thank You</li>
     </ul>
  </div>
</section>

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
                <img src="{{asset('frontend/assets/images/checkmark.png')}}" alt="">
                <h3 class="mb-20">Thank You ! </h3>
                <p>Your Enquiry Number is <strong>{{$orderid}}</strong></p>
                @php
                session()->forget('sessionid');
                @endphp

            </div>

        </div>

    </div>

</div>



@endsection