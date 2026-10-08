    @if(count($products)>0)
		@foreach($products as $product)
           <div class="col-6 col-md-4 col-xl-3 product-grid-list" >
            <div class="default_div product_list">
              <a href="#" class="default_div product-img">
                @if(!empty($product->thumbnail))
                <img src="{{ URL::asset('upload/product/thumbnail/'.$product->thumbnail) }}" alt="">
                @else
                <img src="images/camera_category.jpg" alt="">
                @endif
                <span class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</span>
              </a>
              <div class="default_div product-info">
              <a href="{{url('product/'.$product->slug)}}" class="product_name">{{$product->name}}</a>
              <!-- <span class="category_name">Firecat</span> -->
              <!-- <a href="{{url('product/'.$product->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a> -->
              </div>
            </div>
          </div>
          @endforeach
   @else
              <div class="error-text">There is no Products!</div>
          @endif
          
            <div class="pagination_div default_row">
          {!! $products->appends(Request::except('page'))->render('pagination::bootstrap-4') !!}
        </div>
				