@php

$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

@endphp

@extends('layouts.frontapp')

@section('content')



<!-- breadcrumb-section start -->


<form action="{{ route('attfilter') }}" id="filterform" method="post" >{{ csrf_field() }}  
            <input type="hidden" name=url value="subcategory/{{$catslug}}">
<nav class="breadcrumb-section  pt-20 pb-10">

    <div class="container">

        <div class="row">

            <!-- <div class="col-12">

                <div class="section-title  mb-15">

                    <h2 class="title text-dark text-capitalize">{{$catname}}</h2>

                </div>

            </div> -->

            <div class="col-12">

                <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">

                    <li class="breadcrumb-item"><a href="{{url('/')}}" >Home</a></li>

                    <li class="breadcrumb-item active" aria-current="page">{{$catname}}</li>

                </ol>

            </div>

        </div>

    </div>

</nav>

@if(Session::has('error'))

    <div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>

@endif

@if(Session::has('success'))

    <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>

@endif





<!-- product tab start -->

<div class="product-tab bg-white pt-30 pb-50">

    <div class="container">

        <div class="row">

            <div class="col-lg-9 mb-30" id="productstab">

                <div class="grid-nav-wraper bg-lighten2 mb-30">

                    <div class="row align-items-center">

                        <div class="col-12 col-md-6 mb-3 mb-md-0">

                            <nav class="shop-grid-nav">

                                <ul class="nav nav-pills align-items-center" id="pills-tab" role="tablist">

                                    <li class="nav-item">

                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill"

                                            href="#pills-home" role="tab" aria-controls="pills-home"

                                            aria-selected="true">

                                            <i class="fa fa-th"></i>



                                        </a>

                                    </li>

                                    <li class="nav-item mr-0">

                                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill"

                                            href="#pills-profile" role="tab" aria-controls="pills-profile"

                                            aria-selected="false"><i class="fa fa-list"></i></a>

                                    </li>

                                    <li> <span class="total-products text-capitalize">There are {{count($products)}} products.</span></li>

                                </ul>

                            </nav>

                        </div>

                        <div class="col-12 col-md-6 position-relative">

                            <div class="shop-grid-button d-flex align-items-center justify-content-end">
                                @if(!empty($_GET['pricesort']))   

                                    @php $pricesort=$_GET['pricesort']; @endphp

                                @endif
                                <span class="sort-by">Sort by:</span>

                                <select name="sortbyprice" onchange="this.form.submit();" >
                                    <option value=""> Select Options</option>
                                    <option  id="latest"  value="3" @if(!empty($pricesort) && $pricesort=='3') 
                                       selected
                                    @endif >Latest</option>

                                     <option  id="lowtohigh"  value="1" @if(!empty($pricesort) && $pricesort=='1')
                                       selected
                                    @endif >Low to High</option>

                                     <option  id="hightolow"  value="2" @if(!empty($pricesort) && $pricesort=='2')
                                       selected
                                    @endif >High to Low</option>

                                  </select>
                                <!-- <button class="btn-dropdown rounded d-flex justify-content-between" type="button"

                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"

                                    aria-expanded="false">

                                    Relevance <span class="ion-android-arrow-dropdown"></span>

                                </button>

                                <div class="dropdown-menu shop-grid-menu" aria-labelledby="dropdownMenuButton">

                                    <a class="dropdown-item" href="#">Relevance</a>

                                    <a class="dropdown-item" href="#"> Name, A to Z</a>

                                    <a class="dropdown-item" href="#"> Name, Z to A</a>

                                    <a class="dropdown-item" href="#"> Price, low to high</a>

                                    <a class="dropdown-item" href="#"> Price, high to low</a>

                                </div> -->

                            </div>

                        </div>

                    </div>

                </div>

                <!-- product-tab-nav end -->



                <div class="tab-content" id="pills-tabContent">

                    <!-- first tab-pane -->

                    @if(count($products)>0)

                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"

                        aria-labelledby="pills-home-tab">

                        <div class="row grid-view theme1">

                            @foreach($products as $items)   

                            <div class="col-sm-6 col-lg-4 col-xl-3 mb-30">

                                <div class="card product-card">

                                    <div class="card-body">

                                        <div class="product-thumbnail position-relative">

                                           <!--  <span class="badge badge-danger top-right">New</span> -->

                                            <a href="{{url('product/'.$items->slug)}}">

                                                <img class="first-img" src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="{{$items->name}}">

                                            </a>

                                            <!-- product links -->

                                            <ul class="product-links d-flex justify-content-center">

                                                <!-- <li>

                                                    <a href="javascript:void(0)">

                                                        <span data-toggle="tooltip" data-placement="bottom"

                                                            title="add to wishlist" class="icon-heart"> </span>

                                                    </a>

                                                </li> -->

                                                <!-- <li>

                                                    <a href="#" data-toggle="modal" data-target="#compare">

                                                        <span data-toggle="tooltip" data-placement="bottom"

                                                            title="Add to compare" class="icon-shuffle"></span>

                                                    </a>

                                                </li> -->
<!-- 
                                                <li>

                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#quick-view">

                                                        <span data-toggle="tooltip" data-placement="bottom"

                                                            title="Quick view" class="icon-magnifier"></span>

                                                    </a>

                                                </li>
                                                <li>
                                                    <a href="#"><button class="pro-btn" data-toggle="modal" data-target="#add-to-cart"><i class="icon-basket"></i></button></a>
                                                </li> -->

                                            </ul>

                                            <!-- product links end-->

                                        </div>

                                        <div class="product-desc">

                                            <h3 class="title"><a href="{{url('product/'.$items->slug)}}">{{$items->name}}</a></h3>

                                            

                                            <div class="d-flex align-items-center justify-content-between">

                                                <h6 class="product-price">

                                                    @if(!empty($items->sprice))

                                                                

                                                                <del class="del">{{$homesettings->currencysymbol}} {{$items->price}}</del>

                                                                <span class="onsale">{{$homesettings->currencysymbol}} {{$items->sprice}}</span>

                                                            @else

                                                             {{$homesettings->currencysymbol}} {{$items->price}}

                                                            @endif

                                                </h6>

                                                <!-- <button class="pro-btn" data-toggle="modal" data-target="#add-to-cart"><i class="icon-basket"></i></button> -->

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!-- product-list End -->

                            </div>

                            @endforeach

                           

                        </div>

                    </div>

                    @else

                    <p>There is no Products in this category !</p>

                    @endif

                    <!-- second tab-pane -->

                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <div class="row grid-view-list theme1">

                            @foreach($products as $items)   

                            <div class="col-12 mb-30">

                                <div class="card product-card">

                                    <div class="card-body">

                                        <div class="media flex-column flex-md-row">

                                            <div class="product-thumbnail position-relative">

                                               <!--  <span class="badge badge-danger top-right">New</span> -->
                                                <a href="{{url('product/'.$items->slug)}}">

                                                <img class="first-img" src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="{{$items->name}}">

                                                </a>

                                            </div>

                                            <div class="media-body pl-30">

                                                <div class="product-desc py-0">
                                                     <h3 class="title"><a href="{{url('product/'.$items->slug)}}">{{$items->name}}</a></h3>

                                                        <h6 class="product-price">

                                                            @if(!empty($items->sprice))
                                                                <del class="del">{{$homesettings->currencysymbol}} {{$items->price}}</del>
                                                                <span class="onsale">{{$homesettings->currencysymbol}} {{$items->sprice}}</span>
                                                            @else
                                                                {{$homesettings->currencysymbol}} {{$items->price}}

                                                            @endif
                                                        </h6>

                                                </div>

                                                <ul class="product-list-des">

                                                                            <li>

                                                                                @if(!empty($items->shortdescription))

                                                                                

                                                                                <?php echo $items->shortdescription; ?>

                                                                                @else

                                                                                <p>No Specification available !</p>

                                                                                @endif

                                                                            </li>

                                                </ul>

                                                <a href="{{url('product/'.$items->slug)}}"><button class="btn theme-btn--dark1 btn--xl rounded-5" >
                                                Add to cart </button>
                                                </a>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!-- product-list End -->

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                @if(count($products)>0)    
                <div class="row">
                  

                    <div class="col-12">

                        <nav class="pagination-section mt-30">

                            <div class="row align-items-center">

                                <div class="col-12">
                                    {{$products->links('pagination::bootstrap-4')}}

                                </div>

                            </div>

                        </nav>

                    </div>

                </div>

                 @endif

            </div>

            <div class="col-lg-3 mb-30 order-lg-first">
            
                <aside class="left-sidebar theme1">

                    <!-- search-filter start -->

                <div class="search-filter">

                        <form action="#">

                            <div class="check-box-inner mt-10">
<!-- 
                                <h4 class="title">Filter By</h4>

                                
                                </div> -->

                            </div>

                            <!-- check-box-inner -->

                            <!-- <div class="check-box-inner mt-10">

                                <h4 class="sub-title">Price</h4>

                                <div class="price-filter mt-10">

                                    <div class="price-slider-amount">

                                        <input type="text" id="amount" name="price" readonly

                                            placeholder="Add Your Price" />

                                    </div>

                                    <div id="slider-range"></div>

                                </div>
                            </div>
 -->

                            <!-- <div class="check-box-inner mt-10">

                                <h4 class="sub-title">Size</h4>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="test9">

                                    <label for="test9">s <span>(2)</span></label>

                                </div>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="test10">

                                    <label for="test10">m <span>(2)</span></label>

                                </div>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="test11">

                                    <label for="test11">l <span>(2)</span></label>

                                </div>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="test12">

                                    <label for="test12">xl <span>(2)</span></label>

                                </div>

                            </div>
 -->
                            <!-- check-box-inner -->

                            <!-- <div class="check-box-inner mt-10">

                                <h4 class="sub-title">color</h4>

                                <div class="filter-check-box color-grey">

                                    <input type="checkbox" id="20826">

                                    <label for="20826">grey <span>(4)</span></label>

                                </div>

                                <div class="filter-check-box color-white">

                                    <input type="checkbox" id="20827">

                                    <label for="20827">white <span>(3)</span></label>

                                </div>

                                <div class="filter-check-box color-black">

                                    <input type="checkbox" id="20828">

                                    <label for="20828">black <span>(6)</span></label>

                                </div>

                                <div class="filter-check-box color-camel">

                                    <input type="checkbox" id="20829">

                                    <label for="20829">camel <span>(2)</span></label>

                                </div>

                            </div>
 -->
                            <!-- check-box-inner -->

                            <!-- <div class="check-box-inner mt-10">

                                <h4 class="sub-title">Brand</h4>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="20824">

                                    <label for="20824">Graphic Corner<span>(5)</span></label>

                                </div>

                                <div class="filter-check-box">

                                    <input type="checkbox" id="20825">

                                    <label for="20825">Studio Design<span>(8)</span></label>

                                </div>

                            </div> -->

                        </form>

                    </div>

                    <!-- search-filter end -->

                    <div class="product-widget mb-60 mt-30">

                        <h3 class="title">Recent Search</h3>

                        <ul class="product-tag d-flex flex-wrap">
                            @foreach($categories as $category)
                            <li><a href="{{url('category/'.$category->slug)}}">{{$category->catname}}</a></li>

                            @endforeach
                        </ul>

                    </div>

                    <!--second banner start-->

                    <!-- <div class="banner hover-animation position-relative overflow-hidden">

                        <a href="shop-grid-4-column.html" class="d-block">

                            <img src="assets/img/banner/2.jpg" alt="img">

                        </a>

                    </div> -->

                    <!--second banner end-->

                </aside>
            </form>    
            </div>

        </div>

    </div>

</div>

<!-- product tab end -->




@push('custom-scripts')

<script type="text/javascript">
    
    /*$('#filterform input').on('change', function(){
       

        alert('hello');
       
    });*/

   

</script>


@endpush
@endsection