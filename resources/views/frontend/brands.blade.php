@extends('layouts.frontapp')

@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
      
       <li>Brands</li>
     </ul>
  </div>
</section>




<section class="default_section contact_section">
   <div class="container">
       <!--  <div class="default_div product-heading innerpage_heading">
           <h2 class="product_name">Brands</h2>
        </div> -->
      <div class="row brandspage-row">
        @foreach($all_brands as $all_brand)
        <div class="col-4 col-md-3 col-lg-2 brands_div">
          <div class="product_list">
           <a href="{{url('brand/'.$all_brand->slug)}}" class="product-img">
            @if(!empty($all_brand->image1))
            <img src="{{ URL::asset('upload/brand/'.$all_brand->image1) }}" alt="">
            @else
            <img src="/frontend/assets/images/no-image-available.png" alt="">
            @endif
          </a>
          </div>
        </div>
        @endforeach
        
        <div class="clearfix"></div>
          
         <div class="pagination_div default_row" id="page_hide">
          {!! $all_brands->appends(Request::except('page'))->render('pagination::bootstrap-4') !!}
        </div>



      </div>
   </div>
</section>
@endsection