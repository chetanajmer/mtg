@php

//$category=\App\Models\Category::where('id',$items->category)->first();

$homesettings=\App\Models\Homepage_setting::where('id','1')->first();

//$complete_url=Request::fullUrl(); 



//$relatedproducts=\App\Models\Product::where('category',$category->id)->where('is_active',"online")->get()->take(9);





$baseurl= url('/'); 

$total=0;

@endphp

@extends('layouts.frontapp')



@section('content')

<section id="content">
		<div class="content-page">
			<div class="container">
				<div class="content-about content-cart-page woocommerce">
					<h2 class="title30 play-font text-uppercase font-bold dark">Cart</h2>
					<form method="post" id="cart_form">
						<div class="default_div cart-table-responsive" >
							<table class="shop_table cart table">
								<thead>
									<tr>
										<th class="product-thumbnail">Product</th>
										<th class="product-price">Price</th>
										<th class="product-quantity">Quantity</th>
										<th class="product-subtotal">Total</th>
                                        <th class="product-remove">Action</th>
									</tr>
								</thead>
								<tbody class="woocommerce_cart_item">
                                @if(count($items_array)>0)

                                    @foreach($items_array as $items)

                                    @php

                                    $total+=$items->quantity*$items->price;

                                    @endphp
									<tr class="cart_item">
										<td class="tableproduct-thumbnail" data-title="Product">
											<img  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" alt="{{$items->name}}"/>
											<div class="product-name">
											<a href="#">{{$items->modelno}} - {{$items->name}}</a><br>
                                            <a href="#">@if(!empty($items->variation)) {{$items->variation}} @endif</a>	
                                            </div>				
										</td>
										<td class="product-price" data-title="Price">
											<span class="amount">{{$homesettings->currencysymbol}} {{$items->price}}</span>					
										</td>
										<td class="product-quantity" data-title="Quantity">
                                         <form id="cart_qty" method="post" class="form-horizontal" enctype="multipart/form-data">
                                         @csrf
                                         <input type="hidden" name="modelno"  id="modelno" value="{{$items->modelno}}">
											<div class="detail-qty border">
                                            <div class="number">
                                                <span class="qty-up minus" onclick="decrementqty('{{$items->modelno}}')"><i class="fa fa-angle-down"></i></span>
                                                    <input type="text" value="{{$items->quantity}}" name="quantity" id="quantity">
                                                <span class="qty-down plus" onclick="incrementqty('{{$items->modelno}}')" ><i class="fa fa-angle-up"></i></span>
									        </div>
											</div>
                                         </form>
										</td>
										<td class="product-subtotal" data-title="Total" >
											<a class="amount">{{$homesettings->currencysymbol}} {{$items->price*$items->quantity}}</a>					
										</td>
                                        <td class="product-remove">
											<a class="remove" onclick="delete_product('{{$items->id}}')"><i class="fa fa-trash"></i></a>
										</td>
									</tr>
									@endforeach
                                    @else
                                        <tr class="action_row"><td text-align="center" colspan="6"><p>There is no Items in your Cart !</p></td></tr>        
                                    @endif  
									<tr class="action_row">
										<td class="actions" colspan="6">
											<div class="coupon">
												<label for="coupon_code">Coupon:</label> 
												<input type="text" placeholder="Coupon code" value="" id="coupon_code" class="input-text" name="coupon_code"> 
												<input type="submit" value="Apply Coupon" name="apply_coupon" class="button apply_button bg-color">
											</div>
											<input type="submit" value="Update Cart" name="update_cart" class="button bg-color">			
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
					<div class="cart-collaterals">
						<div class="cart_totals ">
							<h2>Cart Totals</h2>
							<div class="table-responsive">
								<table class="table">
									<tbody>
										<!-- <tr class="cart-subtotal">
											<th>Subtotal</th>
											<td><strong class="amount">{{$homesettings->currencysymbol}} {{$total}} </strong></td>
										</tr> -->
										<!-- <tr class="shipping">
											<th>Shipping</th>
											<td>
												<ul class="list-none" id="shipping_method">
													<li>
														<input type="radio" class="shipping_method" checked="checked" value="free_shipping" id="shipping_method_0_free_shipping" data-index="0" name="shipping_method[0]">
														<label for="shipping_method_0_free_shipping">Free Shipping</label>
													</li>
													<li>
														<input type="radio" class="shipping_method" value="local_delivery" id="shipping_method_0_local_delivery" data-index="0" name="shipping_method[0]">
														<label for="shipping_method_0_local_delivery">Local Delivery (Free)</label>
													</li>
													<li>
														<input type="radio" class="shipping_method" value="local_pickup" id="shipping_method_0_local_pickup" data-index="0" name="shipping_method[0]">
														<label for="shipping_method_0_local_pickup">Local Pickup (Free)</label>
													</li>
												</ul>
											</td>
										</tr> -->
										<tr class="order-total">
											<th>Total</th>
											<td><strong><span class="amount">{{$homesettings->currencysymbol}} {{$total}} </span></strong> </td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="wc-proceed-to-checkout">
								<a class="checkout-button button alt wc-forward bg-color" href="{{url('checkout')}}">Proceed to Checkout</a>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
		<!-- End Content Pages -->
	</section>

<!-- product tab end -->

@push('custom-scripts')



<script>









/*function RefreshTable() {

            $( "#torefresh" ).load( "{{$baseurl}}/cart #torefresh" );

          }  */    





/*    $(document).one('click', '.increment', function() {



         

    	var modelno=$('#modelno').val();

    	var quantity=$('#quantity').val();



        //alert(slug);

        setTimeout(function(){$('#msg').fadeOut();}, 3000);



         $.ajax({



                url:"{{ route('incrementquantity') }}",

                    method:"POST",

                    data: {"_token": "{{ csrf_token() }}",



                    "modelno": modelno,

                    "quantity": quantity,

                	},

                    success:function (data) 

                    {

 							console.log(data);

 							location.reload();

                       



                    } 

                });

                    

        });*/

    	



</script>



@endpush

@endsection