@extends('layouts.frontapp')
@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>Contact Us</li>
     </ul>
  </div>
</section>



<section class="default_section contact_section">
   <div class="container">
    <!--  <div class="default_div">
      <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">Contact us</h2>
        </div>
       
     </div>
     <div class="clearfix"></div> -->
     <div class="default_row contactform-row">
      <div class="w49">
         <div class="default_div address-row">
          <div class="addrow">
           <i class="fa fa-map-marker" aria-hidden="true"></i>
           <strong>Address</strong>
           </div>
           <strong class="company_name">MEEM 53 GENERAL TRADING LLC</strong>
           <p>Office No. 118, 1st Floor, <br>Sheikh Mohammed Bin Rashid Charity Establishment Building, <br>Al-MAMZAR Area, Dubai, UAE.</p>
         </div>
         <div class="default_div address-row">
          <div class="addrow">
           <i class="fa fa-phone" aria-hidden="true"></i>
           <strong>Phone numbers</strong>
         </div>
           <a href="tel:+971585351786">+971 58 535 1786</a>
         </div>
         <div class="default_div address-row">
          <div class="addrow">
           <i class="fa fa-envelope-o" aria-hidden="true"></i>
           <strong>Email</strong>
         </div>
           <a href="mailto:support@meem.com">order@meemindustrial.com</a>
         </div>
       </div>
       <div class="w49">

        @if(Session::has('success'))
         <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('success') }}</div>
        @endif
         <form action="{{route('contactform')}}" method="post">
           {{ csrf_field() }}
           <input type="text" name="contact-name" placeholder="Name" class="mb-2" required="">
           <input type="email" name="contact-email" placeholder="Email" class="mb-2" required="">
           <input type="text" name="contact-phone" placeholder="Phone number" class="mb-2" required="">
           <textarea rows="3" cols="3" placeholder="Message" class="mb-2" required="" name="message"></textarea>
           <button type="submit" class="cart_btn">Send </button>
         </form>
       </div>
       <div class="default_div contact-map">
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.2700909710634!2d55.398031715448866!3d25.19411253800725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f672c81924d0f%3A0xd9792dc36f3130d0!2sSheikh%20Mohamed%20Bin%20Rashid%20Charity%20Bldg!5e0!3m2!1sen!2sin!4v1640419718619!5m2!1sen!2sin" allowfullscreen="" loading="lazy"></iframe>
       </div>
     </div>
   </div>
</section>
    <!-- End Content -->

@endsection