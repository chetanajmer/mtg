@extends('layouts.frontapp')

@section('content')



<!-- breadcrumb-section start -->

<nav class="breadcrumb-section theme1 bg-lighten2 pt-20 pb-10">

    <div class="container">

        <div class="row">

           <!--  <div class="col-12">

                <div class="section-title text-center mb-15">

                    <h2 class="title text-dark text-capitalize">Reset Password</h2>

                </div>

            </div> -->

            <div class="col-12">

                <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">

                    <li class="breadcrumb-item"><a href="{{url('/')}}" >Home</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Reset Password</li>

                </ol>

            </div>

        </div>

    </div>

</nav>

<!-- breadcrumb-section end -->
<div class="container">
@if(Session::has('error'))

    <div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>

@endif

@if(Session::has('success'))

    <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>

@endif
</div>


<div class="my-account pt-30 pb-30">

    <div class="container">

        <div class="row">

            <div class="col-12">

                <h3 class="title text-capitalize mb-30 pb-25"> Reset your account Password !</h3>

                

                <form class="log-in-form"  action="{{ route('doresetpassword') }}" method="post" class="form-horizontal" enctype="multipart/form-data">{{ csrf_field() }}

                    <div class="form-group row">

                        <label for="staticEmail" class="col-md-3 col-form-label">Email</label>

                        <div class="col-md-6">

                            <input type="email" name="email" class="form-control" id="staticEmail">

                        </div>

                    </div>

                   

                    <div class="form-group row pb-3 text-center">

                        <div class="col-md-6 offset-md-3">

                            <div class="login-form-links">

                                <a href="#" class="for-get">Sign In/ Register</a>  

                                <div class="sign-btn">

                                    <button type="submit"  class="btn theme-btn--dark1 btn--md">Reset </button>

                                </div>



                            </div>

                        </div>

                    </div>

                    <!-- <div class="form-group row text-center mb-0">

                        <div class="col-12">

                            <div class="border-top">

                                <a href="register.html" class="no-account">Continue As Guest </a>

                            </div>

                        </div>

                    </div> -->

                </form>

            </div>

        </div>

    </div>

</div>



@endsection