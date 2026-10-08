@extends('layouts.frontapp')
@section('content')


<!-- breadcrumb-section start -->
<nav class="breadcrumb-section pt-20 pb-10">
    <div class="container">
        <div class="row">
            <!-- <div class="col-12">
                <div class="section-title text-center mb-15">
                    <h2 class="title text-dark text-capitalize">About us</h2>
                </div>
            </div> -->
            <div class="col-12">
                <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" >Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">404</li>
                </ol>
            </div>
        </div>
    </div>
</nav>
<!-- breadcrumb-section end -->

<section class="error_page theme1 pt-80 pb-80">
    <div class="container">
      <div class="error_page_row">
        <h2 class="text-dark text-center">404 </h2>  
        <p>Oops! Page Not Found</p>
        <a href="{{url('/')}}" class="addtocart btn theme--btn-default btn--xl mt-5 mt-sm-0 rounded-5">Go To Home</a>
      </div>
    </div>
</section>

@endsection