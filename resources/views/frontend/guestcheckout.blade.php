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

<!-- breadcrumb-section start -->

<nav class="breadcrumb-section pt-20 pb-10">

    <div class="container">

        <div class="row">

           <!--  <div class="col-12">

                <div class="section-title text-center mb-15">

                    <h2 class="title text-dark text-capitalize">Checkout</h2>

                </div>

            </div> -->

            <div class="col-12">

                <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">

                    <li class="breadcrumb-item"><a href="{{url('/')}}" >Home</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>

                </ol>

            </div>

        </div>

    </div>

</nav>

<!-- breadcrumb-section end -->

<!-- product tab start -->



@if(count($items_array)>0)

<section class="check-out-section pt-30">

    <div class="container">

        <div class="row">

            <div class="col-lg-8 mb-10">

                <div id="accordion">

                     <form action="{{ route('docheckout') }}" method="post" class="form-horizontal" enctype="multipart/form-data">{{ csrf_field() }}

                   

                        <div class="card">

                            <div class="card-header" id="headingTwo">

                                <h5 class="mb-0">

                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"

                                        aria-expanded="false" aria-controls="collapseTwo">

                                        Addresses

                                    </button>

                                </h5>

                            </div>



                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">

                                <div class="card-body">

                                    <div class="checkout-inner border-0">

                                        <div class="order-asguest mt-2 mb-2">

                                        <a href="#">Ordering as a guest</a> <span class="separator"></span>

                                        <a class="gray" href="{{url('store/login')}}">Sign in</a>

                                        </div>

                                        <div class="checkout-address p-0">

                                            <p>

                                                The selected address will be used both as your personal address (for

                                                invoice) and as your delivery address.

                                            </p>

                                        </div>

                                        <div class="check-out-content">

                                           





                                                <div class="form-group row">

                                                    <label class="col-md-3" for="firstName2">First name</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" id="firstName2" name="fname" type="text" required="" >

                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label class="col-md-3" for="lastName2">Last name</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" id="lastName2" name="lname" type="text" required="" >

                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label class="col-md-3" for="company">Company</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" id="company" name="company" type="text" >

                                                    </div>

                                                    <div class="col-md-3">

                                                        <span class="optional">

                                                            Optional

                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label class="col-md-3" for="address1">Address</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" id="address1" name="address"

                                                            type="text" required="" >

                                                    </div>

                                                </div>

                                              

                                                

                                            



                                                <div class="form-group row">

                                                    <label class="col-md-3" for="city">City</label>

                                                    <div class="col-md-6">

                                                        @if($homesettings->shippingtype=="on")   
                                                        <select class="form-control" id="shippingweightcity"  name="shippingcityid"  required="">
                                                            @foreach($weightshipping as $ship)
                                                            <option value="{{$ship->area}}">{{$ship->area}}</option>
                                                            @endforeach    
                                                        </select>
                                                        @else
                                                        <select class="form-control" id="shippingcity"  name="shippingcityid"  required="">
                                                            @foreach($shipping as $ship)
                                                            <option value="{{$ship->id}}">{{$ship->cityname}}</option>
                                                            @endforeach    
                                                        </select>
                                                        @endif
                                                        

                                                        <input type="hidden" name="shippingcityname" id="shippingcityname">

                                                </div>



                                                   

                                                </div>



                                                <div class="form-group row">

                                                    <label class="col-md-3" for="zip">Zip/Postal Code</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" id="zip" name="pincode" type="text"

                                                           >

                                                    </div>

                                                </div>

                                                

                                                <div class="form-group row">

                                                    <label class="col-md-3" for="phone">Phone</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" name="phone" id="phone" type="number" required="">

                                                    </div>

                                                    

                                                </div>



                                                 <div class="form-group row">

                                                    <label class="col-md-3" for="phone">Email</label>

                                                    <div class="col-md-6">

                                                        <input class="form-control" name="email" id="email" type="email" required="">

                                                    </div>

                                                    

                                                </div>

                                              <!--   <div class="form-group row">

                                                    <div class="col-md-9 col-md-offset-3">

                                                        <div class="filter-check-box mb-0">

                                                            <input type="checkbox" id="20824" required="">

                                                            <label class="checkout" for="20824">check out</label>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="form-group row mb-0">

                                                    <div class="col-12 text-right">

                                                        <button type="submit"

                                                            class="btn theme-btn--dark1 btn--md">Continue</button>

                                                    </div>

                                                </div> -->

                                        

                                        </div>

                                    </div>



                                    <div class="card-body pt-0">

                                            <!-- <div class="custom-radio mb-4">

                                                <input type="radio" id="test5" name="radio-group">

                                                <label for="test5">Pay by Check</label>

                                            </div>

                                            <div class="custom-radio mb-4">

                                                <input type="radio" id="test6" name="radio-group">

                                                <label for="test6">Pay by bank wire</label>

                                            </div> -->

                                            <div class="custom-radio mb-3">

                                                <input type="radio" id="test7" value="cod"  checked="" name="paymentmode">

                                                <label for="test7">Pay by Cash on Delivery</label>

                                            </div>

                                            <div class="filter-check-box">

                                                <input type="checkbox" checked="" id="terms" required="">

                                                <label class="checkout" for="terms">I agree to the terms and Conditions</label>

                                            </div>



                                    </div>

                                </div>

                            </div>

                        </div>

              

                </div>

            </div>

            <div class="col-lg-4 mb-30">

                <ul class="list-group cart-summary rounded-0">

                    @foreach($items_array as $items)

                    @php

                    $total+=$items->price*$items->quantity;

                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <ul class="items">

                            <li>{{$items->name}} X <b>{{$items->quantity}}</b></li>

                           

                        </ul>

                        <ul class="amount">

                            <li>{{$homesettings->currencysymbol}} {{$items->price}}</li>

                            

                        </ul>

                    </li>

                    @endforeach

                   <li class="list-group-item d-flex justify-content-between align-items-center">

                        <ul class="items">

                            <li>VAT 5%</li>

                           

                        </ul>

                        <ul class="amount">

                           <li>{{$homesettings->currencysymbol}} <span id="" >
                                
                                {{$total*5/100}}

                        </ul>

                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <ul class="items">

                            <!-- <li>Subtotal (tax incl.)</li> -->

                             <li>Shipping</li>

                        </ul>

                        <ul class="amount">

                           <!--  <li>{{$homesettings->currencysymbol}} {{$total}}</li> -->

                            <li>{{$homesettings->currencysymbol}} <span id="shippingprice">0</span></li>

                        </ul>

                    </li>



                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <ul class="items">

                            <li>Total</li>

                           

                        </ul>

                        <ul class="amount">

                            <li>{{$homesettings->currencysymbol}} <span id="totalprice" >0</span></li>

                            <input type="hidden" name="grandtotal" id="grandtotal" >

                        </ul>

                    </li>



                    <li class="list-group-item text-center">

                        <button type="submit" class="btn theme-btn--dark1 btn--md">Proceed to checkout</button>

                    </li>

                </ul>

                </form> 



                <!-- <div class="delivery-info mt-20">

                    <ul>

                        <li>

                            <img src="assets/img/icon/10.png" alt="icon"> Security policy (edit with Customer

                            reassurance module)

                        </li>

                        <li>

                            <img src="assets/img/icon/11.png" alt="icon"> Delivery policy (edit with Customer

                            reassurance module)

                        </li>

                        <li class="mb-0">

                            <img src="assets/img/icon/12.png" alt="icon"> Return policy (edit with Customer

                            reassurance module)

                        </li>

                    </ul>

                </div> -->

            </div>

        </div>

    </div>

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


$("#shippingcity").val("");
$("#shippingcity").on("change", function () {

    	var id=$(this).val();

            $.ajax(
            {
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
                        
                        //    alert(id);
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