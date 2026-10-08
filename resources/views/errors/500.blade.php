@extends('layouts.frontapp')
@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>500</li>
     </ul>
  </div>
</section>

<section class="default_div error_page">
    <div class="container">
       <div class="col-12 thank_page">
        <h3 class="mb-20">500 Server Error</h3>  
        <p>Oops! Something went wrong</p>
        <a href="{{url('/')}}" class="cart_btn">Go To Home</a>
      </div>
    </div>
</section>

@endsection