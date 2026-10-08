@php


$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

$complete_url=Request::fullUrl(); 

$role=session()->get('role');


if(!empty($items->category))
{
    $relatedproducts=\App\Models\Product::where('category',$items->category)->where('is_active',"online")->get()->take(9);
    $categorydata=\App\Models\Category::findorFail($items->category);
}
$baseurl=URL::to('/');

$userid=session()->get('userid');





$qty = 0;

if(!empty($items->variations))
{
        if(is_array(json_decode($items->variations, true)) && !empty(json_decode($items->variations, true)))
        {
            foreach (json_decode($items->variations) as $key => $variation) 
            {
                $qty+= $variation->qty;
            }
        }
        else
        {
            $qty = $items->current_stock;
                                                    
        }
}        
@endphp

@extends('layouts.frontapp')
@section('content')

<section id="content">
		<div class="wrap-shop-banner">
			<div class="banner-slider banner-slider2 banner-slider-shop bg-slider parallax-slider">
				<div class="wrap-item" data-transition="fade" data-navigation="true" data-itemscustom="[[0,1]]">
				@if(!empty($categorydata->bannerimage))	
				<div class="item-slider item-slider2">
						<div class="banner-thumb">
							<a href="#">
								<img src="{{ URL::asset('upload/category/'.$categorydata->bannerimage) }}" alt="{{$categorydata->catname}}" /></a>
						</div>
						<div class="banner-info animated text-center" data-animated="zoomIn">
							<h2 class="title48 play-font font-normal text-uppercase white">up to <strong>45%</strong> off</h2>
							<h3 class="title18 play-font font-italic white">This unique jewelry is handcrafted on the beautiful island of Nantucket using fine silver and semi precious stones.</h3>
							<a href="#" class="border-button white title18">Shop now</a>
						</div>
					</div>
				@endif	
				</div>
			</div>
		</div>
		<!-- End Shop Banner -->
		<div class="wrap-bread-crumb">
			<div class="container">
				<div class="bread-crumb">
					<a href="{{url('/')}}">Home</a>
					<a>Product</a>
					<span>{{$items->name}}</span>
				</div>
			</div>
		</div>
		<!-- End Bread Crumb -->
        <form  id="option-choice-form" method="post" class="form-horizontal" enctype="multipart/form-data"> @csrf

        <input type="hidden" name="id" id="id" value="{{$items->id}}"> 

        <input type="hidden" name="modelno" id="modelno" value="{{$items->modelno}}">

        <input type="hidden" name="product_name" id="product_name" value="{{$items->name}}">

        <input type="hidden" name="specification" id="specification" value="{{$items->shortdescription}}">

        <input type="hidden" name="slug" id="slug" value="{{$items->slug}}">

        <input type="hidden" name="thumbnail" id="thumbnail" value="{{$items->thumbnail}}">

        <input type="hidden" name="weight" id="weight" value="{{$items->weight}}">


        @if($items->choice_options!="[]" || $items->colors!="[]")   
            <input type="hidden" name="price" id="variationprice" >      
            <input type="hidden" name="variationstatus" id="variationstatus" value="on">
            
        @else
            
            @if(!empty($items->sprice))

                <input type="hidden" name="price" id="price" value="{{$items->sprice}}">

            @else

                <input type="hidden" name="price" id="price" value="{{$items->price}}">

            @endif

            <input type="hidden" name="variationstatus" id="variationstatus" value="off">
        @endif      

		<div class="content-page">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="content-page-detail">
							<div class="product-detail detail-full-width">
								<div class="row">
									<div class="col-md-5 col-sm-12 col-xs-12">
										<div class="detail-gallery vertical">
											<div class="mid">
												<img id="img123" src="{{ URL::asset('upload/product/'.$items->image1) }}" alt=""/>
											</div>
											<div class="gallery-control">
												<a href="#" class="prev"><i class="fa fa-angle-left"></i></a>
												<div class="carousel" data-visible="4" data-vertical="true">
													<ul class="list-none">
													  
													    @if(!empty($items->image1))
															<li><a href="{{$items->name}}" class="active" >
															<img src="{{ URL::asset('upload/product/'.$items->image1) }}" alt=""/></a></li>
														@endif

														 @if(!empty($items->image2))
														<li><a href="{{$items->name}}"><img src="{{ URL::asset('upload/product/'.$items->image2) }}" alt=""/></a></li>
														@endif

														 @if(!empty($items->image3))
														<li><a href="{{$items->name}}"><img src="{{ URL::asset('upload/product/'.$items->image3) }}" alt=""/></a></li>
														@endif

														 @if(!empty($items->image4))
														<li><a href="{{$items->name}}"><img src="{{ URL::asset('upload/product/'.$items->image4) }}" alt=""/></a></li>
														@endif

														 @if(!empty($items->image5))
														<li><a href="{{$items->name}}"><img src="{{ URL::asset('upload/product/'.$items->image5) }}" alt=""/></a></li>
														@endif

														 @if(!empty($items->image6))
														<li><a href="{{$items->name}}"><img src="{{ URL::asset('upload/product/'.$items->image6) }}" alt=""/></a></li>
														@endif

														
													</ul>
												</div>
												<a href="#" class="next"><i class="fa fa-angle-right"></i></a>
											</div>
										</div>
										<!-- End Gallery -->
										<div class="detail-share-social text-center">
											<span>Share</span>
											<a href="https://www.facebook.com/sharer.php?u={{$complete_url}}" class="float-shadow"><i class="fa fa-facebook"></i></a>
											<a href="https://twitter.com/share?url={{$complete_url}}&text={{$items->name}}" class="float-shadow"><i class="fa fa-twitter"></i></a>
											<a href="https://www.linkedin.com/shareArticle?url={{$complete_url}}&title={{$items->name}}" class="float-shadow">
												<i class="fa fa-linkedin"></i>
											</a>
										</div>
									</div>
									<div class="col-md-7 col-sm-12 col-xs-12">
										<div class="detail-info">
											<h2 class="product-title title24 text-uppercase dark font-bold play-font">{{$items->name}}</h2>
											<div class="product-price play-font">
                                            @if($items->choice_options!="[]" || $items->colors!="[]")   
                                                {{$homesettings->currencysymbol}} 
                                                <ins class="black title18" id="priceshow">{{$items->price}}</ins>
                                            @else
                                            @if(!empty($items->sprice))
                                            <del class="silver">{{$homesettings->currencysymbol}} {{$items->price}}</del>
                                            <ins class="black title18">{{$homesettings->currencysymbol}} {{$items->sprice}}</ins>
                                                @else
                                            <ins class="black title18">{{$homesettings->currencysymbol}} {{$items->price}}</ins>
                                                @endif
                                                @endif
											</div>
											<!-- <ul class="wrap-rating list-inline-block">
												<li>
													<div class="product-rate">
														<div class="product-rating" style="width:60%"></div>
													</div>
												</li>
												<li>
													<span class="rate-number">(3.0)</span>
												</li>
											</ul> -->
                                            @if(!empty($items->shortdescription))
											<p class="desc product-desc">{!! $items->shortdescription !!}</p>
                                            @else
                                            <p class="desc product-desc">No Description Available</p>
                                            @endif
                                            @if ($items->colors!="[]") 
                                            <div class="fabric_row">
							                    <span class="title_span">Color:</span>
								                    <div class="colorbtn_div">
                                                    @foreach (json_decode($items->colors) as $key => $color)
								                        <label class="radio_row">
                                                        <input type="radio" id="{{ $items->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0)  @endif required="">
                                                         <span class="checkmark" style="background: {{ $color }};"></span>
								                        </label>
                                                    @endforeach
								                    </div>
							                    </div>
                                            @endif
                                            @if($items->choice_options!="[]")
                                            <div class="default_row fabric_row mt-20">
                                            
                                                @foreach (json_decode($items->choice_options) as $key => $choice)
                                                <div class="default_row fabric_row mb-20">
                                                <span class="title_span">{{ $choice->title }}:</span>

                                                <div class="radiobtn_div">
                                                    @foreach ($choice->options as $key => $option)
                                                    <label class="radio_row">
                                                    <input type="radio" class="choice" id="{{ $choice->name }}-{{ $option }}" name="{{ $choice->name }}" value="{{ $option }}" @if($key == 0)  @endif required="">
                                                    <span class="checkmark">{{ $option }}</span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                                @endforeach
                                            </div>
                                            @endif
						                        
											<div class="detail-attr qty-cart">
												<label class="title-attr">Qty:</label>
												<div class="detail-qty border product_qty">
										          <div class="number">
										            <span class="qty-up minus"><i class="fa fa-angle-down"></i></span>
										            <input type="text" value="{{ !empty($items->min_order_quantity) ? (int)$items->min_order_quantity : 1 }}" name="quantity" id="quantity" onchange="getVariantPrice()">
										            <span class="qty-down plus" ><i class="fa fa-angle-up"></i></span>
										          </div>
										        </div>
                                                
                                                
											<input type="submit" class="shop-button bg-dark addcart-link font-bold text-uppercase" id="addtocart" value="Add to Cart">										</div>
											<div class="detail-extra-link">
												<a value="{{$items->id}}" class="wishlist-link" onclick="addtowishlist(event,{{$items->id}})" style="cursor:pointer">
													<i class="fa fa-heart-o"></i><span>Add to Wishlist</span></a>
												<!-- <a href="compare-product.html" class="compare-link fancybox fancybox.iframe"><i class="fa fa-copy"></i><span>Add to Compare</span></a> -->
											</div>
											<ul class="list-none product-meta-info">
												<li>
													<div class="item-product-meta-info product-code-info">
														<label>Product Model:</label>
														<span>{{$items->modelno}}</span>
													</div>
												</li>
												<!-- <li>
													<div class="item-product-meta-info product-available-info">
														<label>Availability:</label>
														<span>In stock</span>
													</div>
												</li> -->
												@php
                    								$catname=\App\Models\Category::where('id',$items->category)->get();

                								@endphp
												<li>
													<div class="item-product-meta-info product-category-info">
														<label>Categories:</label>
														<a href="{{url('category/'.$items->slug)}}">{{$catname[0]['catname']}}</a>
														<!-- <a href="#">Beauty</a>
														<a href="#">Sale</a> -->
													</div>
												</li>
											</ul>
											<!-- <div class="product-control">
												<a href="#" class="prev"><i class="fa fa-long-arrow-left"></i></a>
												<a href="#" class="next"><i class="fa fa-long-arrow-right"></i></a>
											</div> -->
											<!-- <div class="detail-info-contact">
												<ul class="list-none">
													<li>
														<div class="item-service4 table-custom">
															<div class="service-icon">
																<a href="#" class="dark"><i class="fa fa-suitcase"></i></a>
															</div>
															<div class="service-info">
																<h3 class="title14 text-uppercase dark play-font font-bold">Free Shipping</h3>
																<p class="desc opaci dark">With Order over $99</p>
															</div>
														</div>
													</li>
													<li>
														<div class="item-service4 table-custom">
															<div class="service-icon">
																<a href="#" class="dark"><i class="fa fa-undo"></i></a>
															</div>
															<div class="service-info">
																<h3 class="title14 text-uppercase dark play-font font-bold">30 DAYS RETURNS</h3>
																<p class="desc opaci dark">Money Back Guarantee</p>
															</div>
														</div>
													</li>
													<li>
														<div class="item-service4 table-custom">
															<div class="service-icon">
																<a href="#" class="dark"><i class="fa fa-volume-control-phone"></i></a>
															</div>
															<div class="service-info">
																<h3 class="title14 text-uppercase dark play-font font-bold">call us now</h3>
																<p class="desc opaci dark">+84 1678.311.160</p>
															</div>
														</div>
													</li>
												</ul>
											</div> -->
										</div>
									</div>
								</div>
							</div>
							<!-- End Product Detail -->
							<div class="detail-tabs">
								<div class="detail-tab-title">
									
									<ul class="list-tag-detail list-none text-uppercase font-bold" role="tablist">
										<li class="active"><a href="#tab1" data-toggle="tab">Description</a></li>
										<!--<li><a href="#tab2" data-toggle="tab">reviews</a></li>-->
										<li><a href="#tab3" data-toggle="tab">Specification</a></li>
										<!--<li><a href="#tab4" data-toggle="tab">video</a></li>-->
									</ul>
								
								</div>
								<div class="detail-tab-content">
									<div class="tab-content">
										<div id="tab1" class="tab-pane active">
											<div class="detail-tab-desc clearfix">
												<div class="img-detail pull-right">
													<!-- <img src="/frontend/assets/images/shop/detail.jpg" alt="" /> -->
												</div>
												@if(!empty($items->description))
												<p class="desc">{!! $items->description !!} </p>
												@else
												<p class="desc">No Description Available </p>
												@endif
												<!-- <ul class="silver">
													<li>The globe and the map are the small model of our world. Nowadays maps are very useful thing especially when you want to explore some wild spots. </li>
													<li>Of course you can rely on your GPS system but we must never forget our past because new technologies are more vulnerable than good-old stuff. Our company provides goods of premium quality and at fair prices. </li>
												</ul> -->
											</div>
										</div>
										<div id="tab2" class="tab-pane">
											<div class="detail-tab-review">
												<h3 class="title14">2 Review for "Travel Product"</h3>
												<ul class="list-none list-tags-review">
													<li>
														<div class="review-author">
															<a href="#"><img src="images/shop/author1.jpg" alt=""></a>
														</div>
														<div class="review-info">
															<p class="review-header"><a href="#"><strong>7up-theme</strong></a> – March 30, 2017:</p>
															<div class="product-rate">
																<div class="product-rating" style="width:100%"></div>
															</div>
															<p class="desc">Really a nice stool. It was better than I expected in quality. The color is a rich, honey brown and looks a little lighter than pictured but still a great stool for the money.</p>
														</div>
													</li>
													<li>
														<div class="review-author">
															<a href="#"><img src="images/shop/author2.jpg" alt=""></a>
														</div>
														<div class="review-info">
															<p class="review-header"><a href="#"><strong>7up-theme</strong></a> – March 30, 2017:</p>
															<div class="product-rate">
																<div class="product-rating" style="width:100%"></div>
															</div>
															<p class="desc">Really a nice stool. It was better than I expected in quality. The color is a rich, honey brown and looks a little lighter than pictured but still a great stool for the money.</p>
														</div>
													</li>
												</ul>
												<div class="add-review-form">
													<h3 class="title14">Add a Review</h3>
													<p>Your email address will not be published. Required fields are marked *</p>
													<form class="review-form">
														<div>
															<label>Name *</label>
															<input name="name" id="name" type="text">
														</div>
														<div>
															<label>Email *</label>
															<input name="email" id="email" type="text">
														</div>
														<div>
															<label>Your Rating</label>
															<div class="product-rate">
																<div class="product-rating" style="width:100%"></div>
															</div>
														</div>
														<div>
															<label>Your Review *</label>
															<textarea name="messasge" id="message" cols="30" rows="10"></textarea>
														</div>
														<div>
															<input class="shop-button bg-dark" value="Submit" type="submit">
														</div>
													</form>
												</div>
											</div>
										</div>
										<div id="tab3" class="tab-pane">
											<div class="detail-addition">
												@php $product_specs = !empty($items->specifications) ? json_decode($items->specifications, true) : array(); @endphp
												@if(!empty($product_specs))
												<table class="table table-bordered table-striped">
													@foreach($product_specs as $spec)
													<tr>
														<td><p class="desc"><strong>{{ $spec['name'] }}</strong></p></td>
														<td><p class="desc">{{ $spec['value'] }}</p></td>
													</tr>
													@endforeach
												</table>
												@elseif(!empty($items->specification))
												<p class="desc">{!! $items->specification !!} </p>
												@else
												<p class="desc">No Specification Available </p>
												@endif
												<!-- <table class="table table-bordered table-striped">
													<tr>
														<td><p class="desc">Frame Material: Wood</p></td>
														<td><p class="desc">Seat Material: Wood</p></td>
													</tr>
													<tr>
														<td><p class="desc">Adjustable Height: No</p></td>
														<td><p class="desc">Seat Style: Saddle</p></td>
													</tr>
													<tr>
														<td><p class="desc">Distressed: No</p></td>
														<td><p class="desc">Custom Made: No</p></td>
													</tr>
													<tr>
														<td><p class="desc">Number of Items Included: 1</p></td>
														<td><p class="desc">Folding: No</p></td>
													</tr>
													<tr>
														<td><p class="desc">Stackable: No</p></td>
														<td><p class="desc">Cushions Included: No</p></td>
													</tr>
													<tr>
														<td><p class="desc">Arms Included: No</p></td>
														<td>
															<div class="product-more-info">
																<p class="desc">Legs Included: Yes</p>
																<ul class="list-none">
																	<li><a href="#">Leg Material: Wood</a></li>
																	<li><a href="#">Number of Legs: 4</a></li>
																</ul>
															</div>
														</td>
													</tr>
													<tr>
														<td><p class="desc">Footrest Included: Yes</p>	</td>
														<td><p class="desc">Casters Included: No</p></td>
													</tr>
													<tr>
														<td><p class="desc">Nailhead Trim: No</p></td>
														<td><p class="desc">Weight Capacity: 225 Kilogramm</td>
													</tr>
													<tr>
														<td><p class="desc">Commercial Use: No</p></td>
														<td><p class="desc">Country of Manufacture: Vietnam</p></td>
													</tr>
												</table> -->
											</div>
										</div>
										<div id="tab4" class="tab-pane">
											<div class="detail-tab-video">
												<iframe width="560" height="315" src="https://www.youtube.com/embed/FkmIpGikNN0" allowfullscreen></iframe>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End Tabs -->
							<div class="related-tabs">
								<ul class="list-inline-block related-tab-title font-bold text-uppercase play-font">
									<li class="active"><a href="#ral1" data-toggle="tab">Related products</a></li>
									<!-- <li><a href="#ral2" data-toggle="tab">Uppsell Products</a></li> -->
								</ul>
								<div class="tab-content">
									<div id="ral1" class="tab-pane active">
										<div class="product-slider">
											<div class="wrap-item group-navi" data-pagination="false" data-navigation="true" data-itemscustom="[[0,1],[560,2],[990,3]]">
												@if(!empty($relatedproducts))
												@foreach($relatedproducts as $items)
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="{{url('product/'.$items->slug)}}" class="product-thumb-link zoom-thumb">
															<img src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="{{$items->name}}">
														</a>
														<a href="quick-view/{{$items->slug}}" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title">
															<a href="{{url('product/'.$items->slug)}}" class="black">
															{{$items->name}}
														</a></h3>
														<div class="product-price title14 play-font">
															@if(!empty($items->sprice))
															<del class="silver">{{$homesettings->currencysymbol}} {{$items->price}}</del>
															<ins class="black title18">{{$homesettings->currencysymbol}} {{$items->sprice}}</ins>
															@else
															<ins class="black title18">{{$homesettings->currencysymbol}} {{$items->price}}</ins>
															@endif
														</div>
														<!-- <ul class="wrap-rating list-inline-block">
															<li>
																<div class="product-rate">
																	<div class="product-rating" style="width:100%"></div>
																</div>
															</li>
															<li>
																<span class="rate-number silver">(5.0)</span>
															</li>
														</ul> -->
														<div class="product-extra-link4 title18">
															<!-- <a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a> -->
															<a href="{{url('product/'.$items->slug)}}" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a value="{{$items->id}}" class="wishlist-link black inline-block"
																onclick="addtowishlist(event,{{$items->id}})"  style="cursor:pointer"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												@endforeach
												@endif
												<!-- <div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-02.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div> -->
												<!-- <div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-03.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div> -->
												<!-- <div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-04.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div> -->
												<!-- <div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-05.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div> -->
												<!-- <div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-06.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
											</div> -->
										</div>
									</div>
									<!-- <div id="ral2" class="tab-pane">
										<div class="product-slider">
											<div class="wrap-item group-navi" data-pagination="false" data-navigation="true" data-itemscustom="[[0,1],[560,2],[990,3]]">
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-07.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-08.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-09.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-10.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-11.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
												<div class="item-product item-product4 text-center border">
													<div class="product-thumb">
														<a href="#" class="product-thumb-link zoom-thumb"><img src="images/photos/jewelry/dark-light-jewelry-12.jpg" alt=""></a>
														<a href="quick-view.html" class="quickview-link fancybox.iframe title12 round white"><i class="fa fa-search"></i></a>
													</div>
													<div class="product-info">
														<h3 class="title14 product-title"><a href="#" class="black">Blue ring in ged palladium</a></h3>
														<div class="product-price title14 play-font">
															<del class="silver">$601.00</del>
															<ins class="black title18">$400.67</ins>
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
														<div class="product-extra-link4 title18">
															<a href="compare-product.html" class="compare-link inline-block black fancybox fancybox.iframe"><i class="icon ion-ios-loop-strong"></i><span class="title10 white text-uppercase">Compare</span></a>
															<a href="#" class="addcart-link black inline-block"><i class="icon ion-bag"></i><span class="title10 white text-uppercase">Add to cart</span></a>
															<a href="#" class="wishlist-link black inline-block"><i class="icon ion-android-favorite-outline"></i><span class="title10 white text-uppercase">Wishlist</span></a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div> -->
							</div>
							<!-- End Related Tabs -->
						</div>
					</div>	
				</div>
			</div>
		</div>
     </form>
	</section>
	<!-- End Content -->
	<script>
	document.addEventListener('DOMContentLoaded', function(){
		var moq = {{ !empty($items->min_order_quantity) ? (int)$items->min_order_quantity : 0 }};
		if(moq > 1){
			var qty = document.getElementById('quantity');
			var clamp = function(){
				var v = parseInt(qty.value) || 0;
				if(v < moq){ qty.value = moq; }
			};
			qty.value = moq;
			qty.addEventListener('change', clamp);
			qty.addEventListener('blur', clamp);
			var minus = document.querySelector('.product_qty .minus');
			if(minus){
				minus.addEventListener('click', function(){ setTimeout(clamp, 0); });
			}
		}
	});
	</script>
    @endsection