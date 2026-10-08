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
                    <!-- <li class="breadcrumb-item active" aria-current="page">Return Policy</li> -->
                </ol>
            </div>
        </div>
    </div>
</nav>
<!-- breadcrumb-section end -->


<section class="about-section pt-20" style="padding-left: 100px;padding-right: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="about-content">
                   <!--  <h2 class="title mb-20">Return Policy</h2> -->
                    <p class="mb-20">
                       <?php echo $items->returnpolicy; ?>
                    </p>
                </div>
            </div>
            
        </div>
       
    </div>
</section>

@endsection