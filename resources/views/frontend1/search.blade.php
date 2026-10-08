@php

$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

@endphp

@extends('layouts.frontapp')

@section('content')



@if(Session::has('error'))

    <div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>

@endif

@if(Session::has('success'))

    <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>

@endif





<!-- product tab start -->

<section id="content">
        <div class="wrap-bread-crumb">
            <div class="container">
                <div class="bread-crumb">
                    <a href="{{url('/')}}">Home</a>
                    <strong>{{$searchdata}}</strong>
                </div>
            </div>
        </div>
        <!-- End Bread Crumb -->
        <div class="content-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="sidebar sidebar-left">
                            <div class="widget widget-category">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">Recent Search</h2>
                                <div class="widget-content">
                                    <ul class="list-category-toggle toggle-tab list-none">
                                         @foreach($categories as $category)
                                        <li class="item-toggle-tab active">
                                            <a href="{{url('category/'.$category->slug)}}" class="toggle-tab-title">{{$category->catname}}</a>
                                        </li>
                                         @endforeach
                                        <!-- <li class="item-toggle-tab">
                                            <a href="#" class="toggle-tab-title">Baggage</a>
                                            <ul class="toggle-tab-content list-none">
                                                <li><a href="#">For Men’s <span>25</span></a></li>
                                                <li><a href="#">For Men’s <span>09</span></a></li>
                                                <li><a href="#">Accessories <span>37</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="item-toggle-tab">
                                            <a href="#" class="toggle-tab-title">Shoes</a>
                                            <ul class="toggle-tab-content list-none">
                                                <li><a href="#">For Men’s <span>25</span></a></li>
                                                <li><a href="#">For Men’s <span>09</span></a></li>
                                                <li><a href="#">Accessories <span>37</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="item-toggle-tab">
                                            <a href="#" class="toggle-tab-title">Jewelry</a>
                                            <ul class="toggle-tab-content list-none">
                                                <li><a href="#">For Men’s <span>25</span></a></li>
                                                <li><a href="#">For Men’s <span>09</span></a></li>
                                                <li><a href="#">Accessories <span>37</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="item-toggle-tab">
                                            <a href="#" class="toggle-tab-title">Rain Coat</a>
                                            <ul class="toggle-tab-content list-none">
                                                <li><a href="#">For Men’s <span>25</span></a></li>
                                                <li><a href="#">For Men’s <span>09</span></a></li>
                                                <li><a href="#">Accessories <span>37</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="item-toggle-tab">
                                            <a href="#" class="toggle-tab-title">Suitcase</a>
                                            <ul class="toggle-tab-content list-none">
                                                <li><a href="#">For Men’s <span>25</span></a></li>
                                                <li><a href="#">For Men’s <span>09</span></a></li>
                                                <li><a href="#">Accessories <span>37</span></a></li>
                                            </ul>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                            <!-- End Widget -->
                            <!-- <div class="widget widget-price">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">Price</h2>
                                <div class="widget-content">
                                    <div class="range-filter">
                                        <div class="slider-range">
                                            <span class="min-price"></span>
                                            <span class="max-price"></span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> -->
                            <!-- End Widget -->
                           <!--  <div class="widget widget-color">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">colors</h2>
                                <div class="widget-content">
                                    <ul class="list-none list-attr">
                                        <li><a href="#"><i style="background-color:#000000"></i> Black<span>03</span></a></li>
                                        <li><a href="#" class="active" data-color="#1476c6"><i style="background-color:#1476c6"></i>Blue<span>05</span></a></li>
                                        <li><a href="#"><i style="background-color:#5be5ee"></i>Cyan<span>13</span></a></li>
                                        <li><a href="#"><i style="background-color:#e36d55"></i>Beige<span>05</span></a></li>
                                        <li><a href="#"><i style="background-color:#ff1b1b"></i>Red<span>07</span></a></li>
                                        <li><a href="#"><i style="background-color:#f1ca2d"></i>Yellow<span>05</span></a></li>
                                        <li><a href="#"><i style="background-color:#ffffff"></i>White<span>05</span></a></li>
                                    </ul>
                                </div>
                            </div> -->
                            <!-- End Widget -->
                            <!-- <div class="widget widget-attr">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">Size</h2>
                                <div class="widget-content">
                                    <ul class="list-none list-attr">
                                        <li><a href="#">29"<span>05</span></a></li>
                                        <li><a href="#" class="active">30"<span>03</span></a></li>
                                        <li><a href="#">31"<span>13</span></a></li>
                                        <li><a href="#">32"<span>05</span></a></li>
                                        <li><a href="#">XS<span>07</span></a></li>
                                        <li><a href="#">S<span>05</span></a></li>
                                        <li><a href="#">M<span>05</span></a></li>
                                        <li><a href="#">L<span>04</span></a></li>
                                        <li><a href="#">XL<span>08</span></a></li>
                                    </ul>
                                </div>
                            </div> -->
                            <!-- End Widget -->
                            <!-- <div class="widget widget-attr">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">Material</h2>
                                <div class="widget-content">
                                    <ul class="list-none list-attr">
                                        <li><a href="#">Cotton<span>05</span></a></li>
                                        <li><a href="#" class="active">Wool<span>03</span></a></li>
                                        <li><a href="#">Lycra<span>13</span></a></li>
                                        <li><a href="#">Polyester<span>05</span></a></li>
                                        <li><a href="#">Leather<span>07</span></a></li>
                                    </ul>
                                </div>
                            </div> -->
                            <!-- End Widget -->
                           <!--  <div class="widget widget-attr">
                                <h2 class="widget-title title14 font-bold play-font text-uppercase dark">Manufacturer</h2>
                                <div class="widget-content">
                                    <ul class="list-none list-attr">
                                        <li><a href="#">Gucci<span>05</span></a></li>
                                        <li><a href="#" class="active">Panda<span>03</span></a></li>
                                        <li><a href="#">Mango<span>13</span></a></li>
                                        <li><a href="#">Fendi<span>05</span></a></li>
                                        <li><a href="#">Lacoste<span>07</span></a></li>
                                    </ul>
                                </div>
                            </div> -->
                            <!-- End Widget -->
                            <!-- <div class="widget widget-tags">
                                <h2 class="widget-title title14 font-bold text-uppercase play-font">Tags</h2>
                                <ul class="list-none wg-list-tags">
                                    <li><a href="#">$25</a></li>
                                    <li><a href="#">$400</a></li>
                                    <li><a href="#">Fashion</a></li>
                                    <li><a href="#">Electronic</a></li>
                                    <li><a href="#">Beauty</a></li>
                                    <li><a href="#">Sale</a></li>
                                </ul>
                            </div> -->
                            <!-- End Widget -->
                            <!-- <div class="widget widget-search">
                                <h2 class="widget-title title14 font-bold text-uppercase play-font">Search</h2>
                                <form class="wg-search-form" method="get">
                                    <input type="text" name="search" placeholder="Search.." />
                                    <input type="submit" value=""/>
                                </form>
                            </div> -->
                            <!-- End Widget -->
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="content-blog-page">
                            <div class="title-page">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- <h2 class="title30 font-bold text-uppercase pull-left play-font dark">List View</h2> -->
                                        <ul class="sort-pagi-bar list-inline-block pull-right">
                                            <li>
                                                <div class="sort-by">
                                                    <span class="gray">Sort:</span>
                                                    <div class="select-box inline-block">
                                                        <select>
                                                            <option value="">Default Sorting</option>
                                                            <option value="">Price Higher</option>
                                                            <option value="">Price Lower</option>
                                                            <option value="">Name Asc</option>
                                                            <option value="">Name Desc</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- <li>
                                                <div class="dropdown-box show-by">
                                                    <a href="#" class="dropdown-link"><span class="gray">Per Page:</span><span class="silver">12</span></a>
                                                    <ul class="dropdown-list list-none">
                                                        <li><a href="#">12</a></li>
                                                        <li><a href="#">16</a></li>
                                                        <li><a href="#">20</a></li>
                                                        <li><a href="#">24</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="view-type">
                                                    <span class="gray">View As:</span>
                                                    <a href="#" class="grid-view"><i class="fa fa-th-large"></i></a>
                                                    <a href="#" class="list-view active"><i class="fa fa-reorder"></i></a>
                                                </div>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Title Page -->
                            @if(count($products)>0)
                            <div class="product-list-view">
                                <div class="row">
                                      @foreach($products as $items)  
                                    <div class="col-md-12">
                                        <div class="item-product item-product1 item-product-list">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <div class="product-thumb">
                                                        <a href="{{url('product/'.$items->slug)}}" class="product-thumb-link zoom-thumb">
                                                            <img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt=""></a>
                                                        <a href="quick-view/{{$items->slug}}" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div class="product-info">
                                                        <div class="table-custom border-bottom title12">
                                                            <!-- <div class="text-left">
                                                                <a href="#" class="cat-parent opacity dark text-uppercase"></a>
                                                            </div> -->
                                                            <div class="text-right product-extra-link">
                                                                <a href="compare-product.html" class="compare-link fancybox fancybox.iframe dark"><i class="fa fa-copy opacity"></i><span>Compare</span></a>
                                                                <a href="#" class="wishlist-link dark"><i class="fa fa-heart-o opacity"></i><span>Wishlist</span></a>
                                                            </div>
                                                        </div>
                                                        <h3 class="title14 product-title play-font">
                                                            <a href="{{url('product/'.$items->slug)}}" class="dark">{{$items->name}}</a></h3>
                                                        <div class="product-price title14 play-font">
                                                             @if(!empty($items->sprice))
                                                            <del class="silver">{{$homesettings->currencysymbol}} {{$items->price}}</del>
                                                            <ins class="dark">{{$homesettings->currencysymbol}} {{$items->sprice}}</ins>
                                                            @else
                                                            <ins class="dark">{{$homesettings->currencysymbol}} {{$items->price}}</ins>
                                                            @endif
                                                        </div>
                                                        <ul class="wrap-rating list-inline-block">
                                                            <li>
                                                                <div class="product-rate">
                                                                    <div class="product-rating" style="width:100%"></div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <span class="rate-number silver">(5.0)</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="wrap-qty-cart list-inline-block pull-right">
                                                            <!-- <li><label class="title-attr">Qty:</label></li>
                                                            <li>
                                                                <div class="detail-qty border">
                                                                    <a href="#" class="qty-up"><i class="fa fa-angle-up"></i></a>
                                                                    <span class="qty-val">1</span>
                                                                    <a href="#" class="qty-down"><i class="fa fa-angle-down"></i></a>
                                                                </div>
                                                            </li> -->
                                                            <li><a href="{{url('product/'.$items->slug)}}" class="addcart-link inline-block round title12"><i class="fa fa-shopping-basket opacity"></i></a></li>
                                                        </ul>
                                                        @if(!empty($items->shortdescription))
                                                        <p class="product-desc desc dark opaci">
                                                            {!! $items->shortdescription !!}</p>
                                                        @else
                                                         <p class="product-desc desc dark opaci">
                                                           No Short Description  available !</p>
                                                        @endif
                                                        <a href="{{url('product/'.$items->slug)}}" class="shop-button dark">Read more</a>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>  
                                        @endforeach
                                </div>
                                
                                   @if(count($products)>0)  
                                <div class="pagi-nav text-right">
                                   <span>{{$products->links('pagination::bootstrap-4')}}</span>
                                    <!-- <a href="#">2</a>
                                    <a href="#">3</a>
                                    <a href="#" class="next"><i class="fa fa-angle-right"></i></a> -->
                                </div>
                                @endif
                                <!-- End Paginav -->
                            </div>
                        </div>
                        @else
                        <p>No Products Found</p>
                        @endif
                    </div>  
                </div>
            </div>
        </div>
    </section>
    <!-- End Content -->

<!-- product tab end -->





@endsection