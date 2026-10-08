@extends('layouts.frontapp')
@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>About Us</li>
     </ul>
  </div>
</section>
<section class="default_section abouts_section">
   <div class="container">
       <!--  <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">About us</h2>
        </div>
        <div class="clearfix"></div> -->
    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
     <div class="default_div category_banner mb-4 mt-4">
          <img src="/frontend/assets/images/about-banner-img.jpg" alt="">
        </div>
        <div class="clearfix"></div>
     <div class="row abouttext-row">
       <div class="col-md-6 dowe-text">
        <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">What we do</h2>
        </div>
        <div class="default_div category_banner mb-4">
          <img src="/frontend/assets/images/what-we-do-img.jpg" alt="">
        </div>
        <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
       </div>
       <div class="col-md-6 mission-text">
         <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">Our Mission</h2>
        </div>
        <div class="default_div category_banner mb-4">
          <img src="/frontend/assets/images/ourmission-img.jpg" alt="">
        </div>
        <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
       </div>
     </div>
   </div>
</section>
    <!-- End Content -->

@endsection