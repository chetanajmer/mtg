
@extends('layouts.frontapp')

@section('content')


<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>{{$searchdata}}</li>
     </ul>
  </div>
</section>

<section class="default_section productdetail_section">
  <div class="container">
    <div class="row category_page_row">
      
      <div class="col-md-12">
        <div class="default_div category_banner"> 
              
                <img src="/frontend/assets/images/categorypage_banner.jpg" alt="">
               
        </div>
        <div class="clearfix"></div>
        <div class="default_div small-desc">
          <p class="mt-3 mb-3 boldfont"></p>
        </div>
        <!-- <div class="default_row toolbar_row">
          <div class="col-md-3">
             <div class="toolbar_icon">
              <a href="" class="active"><i class="fa fa-th" aria-hidden="true"></i></a>
              <a href=""><i class="fa fa-list-ul" aria-hidden="true"></i></a>
            </div>
          </div>
          <div class="col-md-9">
            <div class="toolbar_sort">
              
            </div>
          </div>
        </div> -->
        <div class="row product-grid">
        @if(count($products)>0)
            @foreach($products as $product)
          <div class="col-6 col-md-4 col-xl-3 product-grid-list" >
            <div class="default_div product_list">
              <a href="{{url('product/'.$product->slug)}}" class="default_div product-img">
                @if(!empty($product->thumbnail))
                <img src="{{ URL::asset('upload/product/thumbnail/'.$product->thumbnail) }}" alt="">
                @else
                <img src="/frontend/assets/images/no-image-available.png" alt="">
                @endif
                 <span class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</span>
              </a>
              <div class="default_div product-info">
              <a href="{{url('product/'.$product->slug)}}" class="product_name">{{$product->name}}</a>
              <!-- <span class="category_name">Firecat</span> -->
             <!--  <a href="{{url('product/'.$product->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a> -->
              </div>
            </div>
          </div>
          @endforeach
          
          @else
              <div class="error-text">There is no Products!</div>
          @endif
          

        </div>
        <div class="clearfix"></div>
          <!-- <ul class="pagination">
            <li class="page-item">
              <a  href="#" aria-label="Previous"> <i class="fa fa-chevron-circle-left" aria-hidden="true"></i></a>
            </li>
            <li class="page-item active"><a href="#">1</a></li>
            <li class="page-item"><a href="#">2</a></li>
            <li class="page-item"><a href="#">3</a></li>
            <li class="page-item">
              <a href="#" aria-label="Next">  <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> </a>
            </li>
          </ul> -->
          <div class="pagination_div default_row">
           {!! $products->appends(Request::except('page'))->render('pagination::bootstrap-4') !!}
         </div>
        
      </div>
      
    </div>
  </div>
</section>
</form>
@endsection