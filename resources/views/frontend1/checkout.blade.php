@php



//$category=\App\Models\Category::where('id',$items->category)->first();



$homesettings=\App\Models\Homepage_setting::where('id','1')->first();



//$complete_url=Request::fullUrl(); 



$role=session()->get('role');



//$relatedproducts=\App\Models\Product::where('category',$category->id)->where('is_active',"online")->get()->take(9);











$baseurl= url('/'); 



$total=0;



@endphp



@extends('layouts.frontapp')







@section('content')



@if(count($items_array)>0)



<section id="content">

		<div class="content-page">

			<div class="container">

				<div class="content-about content-checkout-page woocommerce">

					<h2 class="title30 play-font font-bold text-uppercase text-center dark">Checkout</h2>

					<div class="row">

						<div class="col-md-12 col-sm-12 col-xs-12">

							<div class="row">

								<div class="col-md-6 col-sm-6 col-ms-12">

									<div class="check-billing">

										<form class="form-my-account" method="post" action="{{ route('docheckout') }}" >
											{{ csrf_field() }}

											<h2 class="title title18 font-bold text-uppercase">Billing Details</h2>

											<p class="clearfix box-col2">
												
                                               <input  id="firstName2" name="fname"  type="text" required="" placeholder="first name *">

                                                <input id="firstName2" name="lname"  type="text" required="" placeholder="last name *">

                                            </p>
											

											<p> <input  id="company" name="company" type="text" placeholder="company"></p>

											<p class="clearfix box-col2">

												<input  type="text" required="" name="email" placeholder="Email *" />

												<input  type="text" required="" name="phone" placeholder="Phone *" />

											</p>

											<p class="clearfix box-col2">
												<input type="text" required=""  name="address" placeholder="Address *" />
												<input type="text" required=""  name="pincode" placeholder="Zip / Postcode *" />
												
												<!-- <input type="text" value="Town / City *" required=""  /> -->

											</p>

											<p>
												@if($homesettings->shippingtype=="on")
												<select id="shippingweightcity"  name="shippingcityid"  required="">
													<option value="" selected="selected">City *</option>
													@foreach($weightshipping as $ship)
													<option value="{{$ship->area}}">{{$ship->area}}</option>
													@endforeach
												</select>
												@else
												 <select id="shippingcity"  name="shippingcityid"  required="">
													<option value="" selected="selected">City *</option>
													 @foreach($shipping as $ship)
													<option value="{{$ship->id}}">{{$ship->cityname}}</option>
													@endforeach
												</select>
												@endif
											</p>

											

										<!-- 	<p>

												<input type="checkbox"  id="remember" /> 
												<label for="remember">Create an account?</label>

											</p> -->

										

									</div>

								</div>

								<div class="col-md-6 col-sm-6 col-ms-12">

									<!-- <div class="check-address">

										<form class="form-my-account">

											<p class="ship-address">

												<input type="checkbox"  id="address" /> <label for="address">Ship to a different address?</label>

											</p>

											<p>

												<textarea cols="30" rows="10" onblur="if (this.value=='') this.value = this.defaultValue" onfocus="if (this.value==this.defaultValue) this.value = ''">Order Notes</textarea>

											</p>

										</form>

									</div>	 -->

									<h3 class="order_review_heading bg-dark">Your order</h3>

							<div class="woocommerce-checkout-review-order" id="order_review">

								<div class="table-responsive">

                               

									<table class="shop_table woocommerce-checkout-review-order-table">

										<thead>

											<tr>

												<th class="product-name">Product</th>

												<th class="product-total">Total</th>

											</tr>

										</thead>

										<tbody>


                                        @foreach($items_array as $items)

                                            @php

                                                $total+=$items->price*$items->quantity;

                                                $totalprice=$items->price*$items->quantity;

                                            @endphp

											<tr class="cart_item">

												<td class="product-name">

                                                <?php

                                                    $str=$items->name;

                                                    if (strlen($str) > 40)

                                                    $str = substr($str, 0, 25) . '...';

                                                    echo $str;?> 

                                                    <span class="product-quantity">× {{$items->quantity}}</span>

												</td>

												<td class="product-total">
													<span class="amount">{{$homesettings->currencysymbol}} {{$totalprice}}</span>
												</td>

											</tr>

                                    @endforeach

										</tbody>

										<tfoot>

											<tr class="cart-subtotal">

												<th>Subtotal</th>

												<td><strong class="amount">
												@php
										
										  			$resulttotal = json_decode(json_encode($items_array, true));
										  			$subtotal = 0;
													foreach($resulttotal as $key=>$value)
													{
													   $subtotal+= $value->price * $value->quantity;
													}
															
													echo $homesettings->currencysymbol.$subtotal;
												@endphp	
												</strong></td>
												
											</tr>

											<!-- <tr class="order-total">

												<th>VAT {{$homesettings->vat}}% (Inclusive)</th>

												<td><strong>
													<span class="amount">{{$homesettings->currencysymbol}}  {{$total*$homesettings->vat/100}}</span>
												</strong> </td>

											</tr> -->
											@php 

											/* record from product table */
											$product=json_decode(json_encode($items_array, true));
											
										    /* status from reward setting */
											$product_status=\App\Models\Reward_setting::where('product_status',"on")
												->get();
											$totalsale_status=\App\Models\Reward_setting::where('totalsale_status',"on")
												->get();
											
											/* record from reward */
											$totalsale=\App\Models\Reward::where('status','on')
											->where('totalsale_from','>=','$subtotal')
											->orwhere('totalsale_to','<=','$subtotal')
											->get();
											$totalsalerewardstatus=json_decode(json_encode($totalsale, true));
											@endphp

											
											@if((count($product_status)>0)||(count($totalsale_status)>0)&&(!empty(session()->get('userid'))))
											@if(count($product_status)>0)

												<tr class="order-total">
													 <th>
													 	Reward Points
													 </th>
													 <td>
														<strong>
															<span class="amount">
																@php
																$sum = 0;
																$reward = 0;
																	foreach($product as $key=>$value)
																	{
																	  if(count($totalsale_status)>0)	
																	  {
																	  	foreach($totalsalerewardstatus as $key=>$items)
																		{
																		  $sum=($value->reward_points*$value->quantity)+$items->reward_points;
																		  $reward+= $sum;		
																		}
																	  }
																	  else
																	  {
																	  	$sum=$value->reward_points*$value->quantity;
																		$reward+=$sum;	
																	  }
																				
																	}
																	echo $reward;
																@endphp
	                                                          <input type="hidden" name="rewardpoints" id="rewardpoints" 
	                                                          value="@php echo $reward @endphp">
															</span>
														</strong> 
													</td>
												</tr>
											@else
											<tr class="order-total">
													 <th>
													 	Reward Points
													 </th>
													 <td>
														<strong>
															<span class="amount">
																@php
																$sum = 0;
																$reward = 0;
																   foreach($totalsalerewardstatus as $key=>$items)
																	{
																	   $sum=$items->reward_points;
																	   $reward+= $sum;	
																	}
																echo $reward;
																@endphp
	                                                          <input type="hidden" name="rewardpoints" id="rewardpoints" 
	                                                          value="@php echo $reward @endphp">
															</span>
														</strong> 
													</td>
												</tr>
											@endif
										
											@endif
											

											
										

											<tr class="shipping">

												<th>Shipping</th>

												<td>
													<strong>
														<span class="amount">{{$homesettings->currencysymbol}} 0</span></strong> 
													<ul class="list-none" id="shipping_method">

														<!-- <li>

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

														</li> -->

													</ul>

												</td>

											</tr>

											

											@php 
											$carttotal=0;
											$carttotal=$subtotal;
											@endphp
											<tr class="order-total">

												<th>Total</th>

												<td>
													<strong>
														<span class="amount">
															{{$homesettings->currencysymbol}} {{$carttotal}}
														</span>
													</strong> 
												</td>
												<input type="hidden" name="grandtotal" id="grandtotal" value="{{$carttotal}}" >
											</tr>

										</tfoot>

									</table>

								</div>

								<div class="woocommerce-checkout-payment" id="payment">

									<ul class="payment_methods methods list-none">

										<!-- <li class="payment_method_bacs">

											<input type="radio" data-order_button_text="" value="bacs" name="payment_method" class="input-radio" id="payment_method_bacs" checked="checked">

											<label for="payment_method_bacs">Direct Bank Transfer 	</label>

											<div style="" class="payment_box payment_method_bacs">

												<p>Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>

											</div>

										</li>

										<li class="payment_method_cheque">

											<input type="radio" data-order_button_text="" value="cheque" name="payment_method" class="input-radio" id="payment_method_cheque">

											<label for="payment_method_cheque">Cheque Payment 	</label>

												<div style="display:none;" class="payment_box payment_method_cheque">

												<p>Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>

											</div>

										</li> -->

										<li class="payment_method_cod">

											<input type="radio" data-order_button_text="" value="cod" name="payment_method" class="input-radio" id="paymentmode" checked="checked">

											<label for="payment_method_cod">Cash on Delivery 	</label>

											<div style="display:none;" class="payment_box payment_method_cod">

												<p>Pay with cash upon delivery.</p>

											</div>

										</li>

										<!-- <li class="payment_method_paypal">

											<input type="radio" data-order_button_text="Proceed to PayPal" value="paypal" name="payment_method" class="input-radio" id="payment_method_paypal">

											<label for="payment_method_paypal">

												PayPal <img alt="PayPal Acceptance Mark" src="images/page/payment.png"><a title="What is PayPal?" onclick="javascript:window.open('https://www.paypal.com/gb/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;" class="about_paypal" href="https://www.paypal.com/gb/webapps/mpp/paypal-popup">What is PayPal?</a>	

											</label>

											<div style="display:none;" class="payment_box payment_method_paypal">

												<p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.</p>

											</div>

										</li> -->

									</ul>

									<div class="form-row place-order">

										<input type="submit" data-value="Place order" value="Place order" id="" name="" class="button alt bg-color">

									</div>

								</div>

							</div>	

								</div>

							</div>

							</form>

						</div>

					</div>

				</div>	

			</div>

		</div>

		<!-- End Content Pages -->

	</section>



@else



<section class="check-out-section pt-80 pb-50">



    <div class="container">



        <div class="row">



            <div class="col-lg-12 mb-30" style="text-align: center;">There is No items in Your Cart !</div>



        </div>



    </div>



</section>



@endif            



<!-- product tab end -->



@push('custom-scripts')







<script>



















/*function RefreshTable() {



            $( "#torefresh" ).load( "{{$baseurl}}/cart #torefresh" );



          }  */    



$("#shippingcity").val("");







$("#shippingcity").on("change", function () {







         



    	var id=$(this).val();



        //alert(id);



       







         $.ajax({







                url:"{{ route('storeshipping') }}",



                    method:"POST",



                    data: {"_token": "{{ csrf_token() }}",







                    "id": id,



                    



                	},



                    success:function (data) 



                    {



 							//console.log(data);



 							var cost=parseInt(data['shippingcost']);  



                            var total='{!!$total!!}';



                            var grandtotal=cost+parseInt(total); 



                            $('#shippingprice').empty();



                            $('#shippingprice').append(cost);    



                            $('#totalprice').empty();



                            $('#totalprice').append(grandtotal);



                            $('#grandtotal').val(grandtotal);



                            $('#shippingcityname').val(data['cityname']);







                    } 



                });   



                    



        });



    	







</script>

<script>





$("#shippingweightcity").val("");

$("#shippingweightcity").on("change", function () {



        var id=$(this).val();

         //alert(id);

            $.ajax(

            {

                url:"{{ route('storeweightshipping') }}",



                    method:"POST",



                    data: {"_token": "{{ csrf_token() }}",

                    "area1": id,             



                    },



                    success:function (data) 



                    {



                            console.log(data);



                            var cost=parseInt(data['price']);  



                            var total='{!!$total!!}';



                            var grandtotal=cost+parseInt(total); 



                            $('#shippingprice').empty();



                            $('#shippingprice').append(cost);    



                            $('#totalprice').empty();



                            $('#totalprice').append(grandtotal);



                            $('#grandtotal').val(grandtotal);



                            $('#shippingcityname').val(data['area']);







                    } 



            });   



                    



        });



</script>





@endpush



@endsection