@php 

$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

@endphp

<!DOCTYPE HTML>

<html lang="en-US">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1"/>

	<meta name="description" content="BW Store is new Html theme that we have designed to help you transform your store into a beautiful online showroom. This is a fully responsive Html theme, with multiple versions for homepage and multiple templates for sub pages as well" />

	<meta name="keywords" content="BW Store,7uptheme" />

	<meta name="robots" content="noodp,index,follow" />

	<meta name='revisit-after' content='1 days' />

	<title>BW Store | Quick View</title>

	<link href="https://fonts.googleapis.com/css?family=Dosis:300,400,700%7cOpen+Sans:300,400,700%7cGreat+Vibes" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/font-awesome.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/bootstrap.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/bootstrap-theme.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/jquery.fancybox.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/jquery-ui.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/owl.carousel.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/owl.transitions.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/jquery.mCustomScrollbar.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/owl.theme.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/animate.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/libs/hover.min.css"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/theme.css" media="all"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/responsive.css" media="all"/>

	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/browser.css" media="all"/>

	<!-- <link rel="stylesheet" type="text/css" href="css/rtl.css" media="all"/> -->

</head>

<body>

<div class="wrap">

	<form id="option-choice-form" method="post" class="form-horizontal" enctype="multipart/form-data">

	@csrf

	<input type="hidden" name="id"  value="{{$item->id}}">                           

	<input type="hidden" name="modelno" id="modelno" value="{{$item->modelno}}">

	<input type="hidden" name="product_name" id="product_name" value="{{$item->name}}">

	<input type="hidden" name="specification" id="specification" value="{{$item->shortdescription}}">

	<input type="hidden" name="slug" id="slug" value="{{$item->slug}}">

	<input type="hidden" name="price" id="price" value="{{$item->price}}">

	<input type="hidden" name="thumbnail" id="thumbnail" value="{{$item->thumbnail}}">

	

	<section id="content">

		<div class="detail-light-box">

			<div class="product-detail">

				<div class="row">

					<div class="col-md-5 col-sm-5 col-xs-12">

						<div class="detail-gallery">

							<div class="mid">

								<img src="{{ URL::asset('upload/product/'.$item->image1) }}" alt=""/>

							</div>

							<div class="gallery-control">

								<a href="#" class="prev"><i class="fa fa-angle-left"></i></a>

								<div class="carousel" data-visible="5">

									<ul class="list-none">
                    					@if(!empty($item->image1))
										<li><a href="#" class="active"><img src="{{ URL::asset('upload/product/'.$item->image1) }}" alt=""/></a></li>
                    					@endif

                     					@if(!empty($item->image2))
										<li><a href="#"><img src="{{ URL::asset('upload/product/'.$item->image2) }}" alt=""/></a></li>
                    					@endif

                     					@if(!empty($item->image3))
										<li><a href="#"><img src="{{ URL::asset('upload/product/'.$item->image3) }}" alt=""/></a></li>
                    					@endif

                     					@if(!empty($item->image4))
										<li><a href="#"><img src="{{ URL::asset('upload/product/'.$item->image4) }}" alt=""/></a></li>
                    					@endif

                     					@if(!empty($item->image5))
										<li><a href="#"><img src="{{ URL::asset('upload/product/'.$item->image5) }}" alt=""/></a></li>
                    					@endif

                     					@if(!empty($item->image6))
										<li><a href="#"><img src="{{ URL::asset('upload/product/'.$item->image6) }}" alt=""/></a></li>
                    					@endif
									</ul>

								</div>

								<a href="#" class="next"><i class="fa fa-angle-right"></i></a>

							</div>

						</div>

						<!-- End Gallery -->

						<div class="detail-share-social text-center">

							<span>Share</span>

							<a href="#" class="float-shadow"><img src="/frontend/assets/images/icon/icon-email.png" alt="" /></a>

							<a href="#" class="float-shadow"><img src="/frontend/assets/images/icon/icon-facebook.png" alt="" /></a>

							<a href="#" class="float-shadow"><img src="/frontend/assets/images/icon/icon-twitter.png" alt="" /></a>

							<a href="#" class="float-shadow"><img src="/frontend/assets/images/icon/icon-pinterest.png" alt="" /></a>

						</div>

					</div>

					<div class="col-md-7 col-sm-7 col-xs-12">

						<div class="detail-info">

							<h2 class="product-title title24 text-uppercase dark font-bold play-font">{{$item->name}}</h2>

							<div class="product-price play-font">

							@if($item->choice_options!="[]" || $item->colors!="[]")   

			                {{$homesettings->currencysymbol}} 

			                  <ins class="black title18" id="priceshow">{{$item->price}}</ins>

			              @else

			              @if(!empty($item->sprice))

			                <del class="silver">{{$homesettings->currencysymbol}} {{$item->price}}</del>

			                  <ins class="black title18">{{$homesettings->currencysymbol}} {{$item->sprice}}</ins>

			              @else

			                  <ins class="black title18">{{$homesettings->currencysymbol}} {{$item->price}}</ins>

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

							<p class="desc product-desc">{!! $item->shortdescription !!}</p>

							@if ($item->colors!="[]")     

							  <div class="fabric_row">

							    <span class="title_span">Color:</span>

								    <div class="colorbtn_div">

										@foreach (json_decode($item->colors) as $key => $color)

								            <label class="radio_row">

												<input type="radio" id="{{ $item->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0)  @endif required="" onchange="getVariantPrice()">

                              					<span class="checkmark" style="background: {{ $color }};"></span>

								            </label>

										@endforeach

								    </div>

							  </div>

							@endif

							               

							@if($item->choice_options!="[]")

								@foreach (json_decode($item->choice_options) as $key => $choice)

						            <span class="title_span">{{ $choice->title }}:</span>

						                <div class="radiobtn_div">

											@foreach ($choice->options as $key => $option)

												<label class="radio_row">

												  <input type="radio" id="{{ $choice->name }}-{{ $option }}" name="{{ $choice->name }}" value="{{ $option }}" @if($key == 0)  @endif required="" onchange="getVariantPrice()">

													<span class="checkmark">{{ $option }}</span>

												</label>

											@endforeach

						                </div>

								@endforeach

							@endif

							<div class="detail-attr qty-cart">

								<label class="title-attr">Qty:</label>

								<div class="detail-qty border product_qty">

								  <div class="number">

									<span class="qty-up minus"><i class="fa fa-angle-down"></i></span>

										<input type="text" value="1" name="quantity" id="quantity" onchange="getVariantPrice()">

									<span class="qty-down plus"><i class="fa fa-angle-up"></i></span>

									</div>

								</div>

								<input type="submit" class="shop-button bg-dark addcart-link font-bold text-uppercase" id="addtocart" value="Add to Cart">

							</div>

							<div class="detail-extra-link">

								<a href="#" class="wishlist-link"><i class="fa fa-heart-o"></i><span>Add to Wishlist</span></a>

								<!-- <a href="compare-product.html" class="compare-link fancybox fancybox.iframe"><i class="fa fa-copy"></i><span>Add to Compare</span></a> -->

							</div>

							<ul class="list-none product-meta-info">

								<li>

									<div class="item-product-meta-info product-code-info">

										<label>Product Model:</label>

										<span>{{$item->modelno}}</span>

									</div>

								</li>

								<!-- <li>

									<div class="item-product-meta-info product-available-info">

										<label>Availability:</label>

										<span>In stock</span>

									</div>

								</li> -->
					                @php
					                    $catname=\App\Models\Category::where('id',$item->category)->get();
					                @endphp
								<li>
									<div class="item-product-meta-info product-category-info">

										<label>Categories:</label>

										<a href="{{url('category/'.$item->slug)}}">{{$catname[0]['catname']}}</a>

										<!-- <a href="#">Beauty</a>

										<a href="#">Sale</a> -->

									</div>

								</li>

							</ul>

						</div>

					</div>

				</div>

			</div>

			<!-- End Product Detail -->

		</div>

	</section>

</form>





	<!-- End Content -->

<script src="/frontend/assets/js/libs/jquery-3.2.1.min.js"></script>

<script src="/frontend/assets/js/libs/bootstrap.min.js"></script>

<script src="/frontend/assets/js/libs/jquery.fancybox.min.js"></script>

<script src="/frontend/assets/js/libs/jquery-ui.min.js"></script>

<script src="/frontend/assets/js/libs/owl.carousel.min.js"></script>

<script src="/frontend/assets/js/libs/jquery.jcarousellite.min.js"></script>

<script src="/frontend/assets/js/libs/jquery.mCustomScrollbar.min.js"></script>

<script src="/frontend/assets/js/libs/jquery.elevatezoom.min.js"></script>

<script src="/frontend/assets/js/libs/popup.min.js"></script>

<script src="/frontend/assets/js/libs/timecircles.min.js"></script>

<script src="/frontend/assets/js/libs/wow.min.js"></script>

<script src="/frontend/assets/js/theme.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>

<script type="text/javascript">      

 var successClick = function(message){

  $.notify({

    // options

    title: '<strong>Alert</strong><br>',

    message:message,

  icon: 'fa fa-check',

   

    target: '_blank'

},{

    // settings

    element: 'body',

    //position: null,

    type: "success",

    //allow_dismiss: true,

    //newest_on_top: false,

    showProgressbar: false,

    placement: {

        from: "top",

        align: "right"

    },

    offset: 20,

    spacing: 10,

    z_index: 99999,

    delay: 3300,

    timer: 1000,

    url_target: '_blank',

    mouse_over: null,

    animate: {

        enter: 'animated bounceIn',

        exit: 'animated bounceOut'

    },

    onShow: null,

    onShown: null,

    onClose: null,

    onClosed: null,

    icon_type: 'class',

	template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +

		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +

		'<span data-notify="icon"></span> ' +

		'<span data-notify="title">{1}</span> ' +

		'<span data-notify="message">{2}</span>' +

		'<div class="progress" data-notify="progressbar">' +

			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +

		'</div>' +

		'<a href="{3}" target="{4}" data-notify="url"></a>' +

	'</div>' 

});

}



var infoClick = function(){

  $.notify({

    // options

    title: '<strong>Info</strong>',

    message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",

  icon: 'glyphicon glyphicon-info-sign',

},{

    // settings

    element: 'body',

    position: null,

    type: "info",

    allow_dismiss: true,

    newest_on_top: false,

    showProgressbar: false,

    placement: {

        from: "top",

        align: "right"

    },

    offset: 20,

    spacing: 10,

    z_index: 1031,

    delay: 3300,

    timer: 1000,

    url_target: '_blank',

    mouse_over: null,

    animate: {

        enter: 'animated bounceInDown',

        exit: 'animated bounceOutUp'

    },

    onShow: null,

    onShown: null,

    onClose: null,

    onClosed: null,

    icon_type: 'class',

});

}



var warningClick = function(){

  $.notify({

    // options

    title: '<strong>Warning</strong>',

    message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",

  icon: 'glyphicon glyphicon-warning-sign',

},{

    // settings

    element: 'body',

    position: null,

    type: "warning",

    allow_dismiss: true,

    newest_on_top: false,

    showProgressbar: false,

    placement: {

        from: "top",

        align: "right"

    },

    offset: 20,

    spacing: 10,

    z_index: 1031,

    delay: 3300,

    timer: 1000,

    url_target: '_blank',

    mouse_over: null,

    animate: {

        enter: 'animated bounceIn',

        exit: 'animated bounceOut'

    },

    onShow: null,

    onShown: null,

    onClose: null,

    onClosed: null,

    icon_type: 'class',

});

}



var dangerClick = function(message){

  $.notify({

    // options

    title: '<strong>Error</strong><br>',

    message: message,

  icon: 'glyphicon glyphicon-remove-sign',

},{

    // settings

    element: 'body',

    position: null,

    type: "danger",

    allow_dismiss: true,

    newest_on_top: false,

    showProgressbar: false,

    placement: {

        from: "top",

        align: "right"

    },

    offset: 20,

    spacing: 10,

    z_index: 9999,

    delay: 3300,

    timer: 1000,

    url_target: '_blank',

    mouse_over: null,

    animate: {

        enter: 'animated flipInY',

        exit: 'animated flipOutX'

    },

    onShow: null,

    onShown: null,

    onClose: null,

    onClosed: null,

    icon_type: 'class',

});

}



  </script>



<script>

    

      $('#option-choice-form input').on('change', function(){

        // var abc=$(this).val();



        // alert(abc);

        getVariantPrice();

    });



    function getVariantPrice(){

        if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){

            $.ajax({

               type:"POST",

               url: '{{ route('variant_price') }}',

                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

               data: $('#option-choice-form').serializeArray(),

               success: function(data){



                    console.log(data);

                   $('#priceshow').empty();

                   $('#priceshow').append(data.price);

                    $('#variationprice').val(data.price);



                    if(data.price==0)

                    {

                        $('.addtocart').hide();

                        $('.badge').empty();

                        $('.badge ').append("Out of Stock ");

                    }

                    else

                    {

                        $('.addtocart').show();

                        $('.badge').hide();

                    }    

                  /* $('#available-quantity').html(data.quantity);

                   $('.input-number').prop('max', data.quantity);

                   //console.log(data.quantity);

                   if(parseInt(data.quantity) < 1){

                       $('.buy-now').hide();

                       $('.add-to-cart').hide();

                   }

                   else{

                       $('.buy-now').show();

                       $('.add-to-cart').show();

                   }*/

               }

           });

        }

    }





    function checkAddToCartValidity(){

        var names = {};

        $('#option-choice-form input:radio').each(function() { // find unique names

              names[$(this).attr('name')] = true;

        });

        var count = 0;

        $.each(names, function() { // then count them

              count++;

        });





        if($('#option-choice-form input:radio:checked').length == count){

        //    alert(names);

            return true;

        }



      //  alert(names);

        return false;





    }

</script>

<script>

$(document).ready(function(){

$("#option-choice-form").submit(function (e) {

	e.preventDefault();

         $.ajax({

                url:"{{ route('addtocart') }}",

                method:"POST",

				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                data: $('#option-choice-form').serializeArray(),

                success:function (data) 

                {

             		console.log(data);

      				successClick(data);

      				$( "#mini_cart" ).load(window.location.href + " #mini_cart" );

      				$( "#cart_count" ).load(window.location.href + " #cart_count" );

      				$( "#cart_total" ).load(window.location.href + " #cart_total" );
                } 
              });
         });
});

</script>

<script>

function delete_product($id) {



//  alert($id);

 $.ajax({

		type:"get",

		url:"{{ route('deleteproduct') }}",

		method:"POST",

		data: {"_token": "{{ csrf_token() }}","product_id":$id

		},

		success:function (data)

		{

			successClick(data);

			$( "#mini_cart" ).load(window.location.href + " #mini_cart" );

			$( "#cart_count" ).load(window.location.href + " #cart_count" );

			$( "#cart_total" ).load(window.location.href + " #cart_total" );

		} 



	});

}

</script>









</body>

</html>