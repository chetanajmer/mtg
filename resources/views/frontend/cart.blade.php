

@extends('layouts.frontapp')
@section('content')

<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>Enquiry</li>
     </ul>
  </div>
</section>
<section class="default_section contact_section">
   <div class="container">
        <!-- <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">Cart</h2>
        </div> -->

       @if(count($items_array)>0)
      <table class="table table-bordered cart_table">
        <thead>
          <tr>
            <th>Product Name</th>
            <th class="text-center">Part No</th>
            <th class="text-center">Price</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
         
           @foreach($items_array as $items)

           @php 

              $totalprice=$items->price*$items->quantity; 
              $totalprice1=($items->price+$items->cprice)*$items->quantity;

           @endphp
          <tr>
            <td class="cartproduct_img">
              <a href="{{url('product/'.$items->slug)}}"><img src="{{ URL::asset('upload/product/thumbnail/'.$items['thumbnail']) }}" alt=""></a>
              <a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->name}}</a>

               @if(!empty($items->cprice))
               <input type="checkbox" name="custprice"  id="c_price{{$items->id}}"  onclick="getcprice('{{$items->cprice}},{{$items->id}}')">Customizable Price
               @endif
            </td>
            <td class="text-center"><a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->modelno}}</a></td>
             <td class="text-center">
              @if(!empty($items->cprice))
              <a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->price}} 
                <span style="display: none" id="customized_price{{$items->id}}">
                <br>+{{$items->cprice}}
              </span>
              </a>
              @else
              <a href="{{url('product/'.$items->slug)}}" class="product_name">{{$items->price}}
              </a>
              @endif
            </td>
            <td class="mob-border text-center">
              <form id="cart_qty" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
               <input type="hidden" name="modelno"  id="modelno" value="{{$items->modelno}}">
              <div class="number">
                <input type="text" value="{{$items->quantity}}" name="quantity" id="quantity" >
                <span class="plus" onclick="incrementqty('{{$items->id}},{{$items->quantity}}')"><i class="fa fa-angle-up" aria-hidden="true" ></i></span>
                <span class="minus" onclick="decrementqty('{{$items->id}},{{$items->quantity}}')"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
              </div>
            </form>
            </td>
            <td class="text-center">
              <a href="{{url('product/'.$items->slug)}}" class="product_name" id="totalprice{{$items->id}}">{{$totalprice}}</a>
            
              <a href="{{url('product/'.$items->slug)}}" class="product_name" id="totalprice1{{$items->id}}" style="display: none">{{$totalprice1}}</a>
           
            </td>
            <td class="delete_btn text-center"><a href="javascript(void(0))" onclick="delete_product('{{$items->id}}')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
          </tr>
          @endforeach
         
        </tbody>
      </table>
       
      <div class="default_row cartbtn_row">
        <button type="button" class="cart-btn1 cart_btn" onclick="clear_cart()">Clear Cart</button>
        <div class="btn_cartdiv">
          <a href="{{url('/')}}" class="cart_btn continue_btn">Continue Browsing</a>
          <a href="#cartform" class="cart_btn" id="send_btn">Send Enquiry</a>
        </div>
        
      </div>

      @else
       <div class="error-text">There is no products in enquiry list !</div>
      @endif

      @if(count($items_array)>0)
      <div class="clearfix"></div>
      <div id="cartform">
        <div class="default_div product-heading cartpage_heading">
           <h2 class="product_name">Please fill Below Details</h2>
        </div>
        <form class="row" action="{{route('docheckout')}}" method="post">
            {{ csrf_field() }}
          <div class="col-md-6 mb-3">
            <label>Name:</label>
            <input type="text" name="name" placeholder="Enter Name" required="">
          </div>
          <div class="col-md-6 mb-3">
            <label>Company Name:</label>
            <input type="text" name="company_name" placeholder="Enter Company Name" required="">
          </div>
          <div class="col-md-6 mb-3">
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter Email" required="">
          </div>
          <div class="col-md-6 mb-3">
            <label>Phone/Mobile Number:</label>
            <input type="text" name="phone"  placeholder="Enter Phone Number" required="">
          </div>
          <div class="col-md-12 mb-2">
            <label>Additional Information:</label>
            <textarea rows="3" cols="3" placeholder="Additional Information" name="information" required=""></textarea>
          </div>
          <div class="col-md-12 cart_submit">
            <input type="submit" name="" value="Submit" class="cart_btn">
          </div>
        </form>
        </div>
      @endif
   </div>
</section>

@endsection