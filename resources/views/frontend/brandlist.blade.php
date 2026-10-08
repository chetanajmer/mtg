@php
$branddata=\App\Models\Brand::findorFail($brandid);
@endphp

@extends('layouts.frontapp')
@section('content')

<form action="{{ route('attfilter') }}" id="filterform" method="post" >{{ csrf_field() }}  
            <input type="hidden" name=url value="listview/brand/{{$branddata->slug}}">
<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       <li>{{$branddata->brandname}}</li>
     </ul>
  </div>
</section>


<section class="default_section productdetail_section">
  <div class="container">
    <div class="row category_page_row">
      <div class="col-md-12 col-lg-3">
        <div class="category_filterdiv">
          <div class="c-accordion">
              <span class="category_heading">Categories</span>
              <a href="javascript:void(0)" class="js-btn js-is-active"></a>
              <div class="accordion__body">
                 @foreach($checkbox_categories as  $checkbox_categories)
                    <div class="checkbox_list">{{$checkbox_categories->catname}}
                      <input type="checkbox" id="category_checkbox" name="category_checkbox[]" value="{{$checkbox_categories->id}}" >
                      <input type="hidden" name="brand" id="brand" value="{{$branddata->slug}}">
                      <span class="checkmark"></span>
                    </div>
                @endforeach
                   <!--  <div class="checkbox_list">Drilling & Fastening
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div>
                    <div class="checkbox_list">Rotary & Demolition
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div>
                    <div class="checkbox_list">Accessories
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div> -->
              </div>
          </div>

           
         <!--  <div class="c-accordion">
              <span class="category_heading">Shop by size</span>
              <a href="javascript:void(0)" class="js-btn js-is-active"></a>
              <div class="accordion__body">
                    <div class="checkbox_list">Small
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div>
                    <div class="checkbox_list">Medium
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div>
                    <div class="checkbox_list">Large
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </div>
              </div>
          </div> -->
          <div class="default_div">
            <img src="images/leftcategory_img.jpg" alt="" class="w-100">
          </div>
        </div>
      </div>
      <div class="col-md-12 col-lg-9">
        <!-- <div class="default_div category_banner"> 
              @if(!empty($branddata->bannerimage))
                <img src="{{ URL::asset('upload/brand/'.$branddata->bannerimage) }}" alt="">
                @else
                <img src="/frontend/assets/images/categorypage_banner.jpg" alt="">
                @endif
        </div>
        <div class="clearfix"></div>
        <div class="default_div small-desc">
          <p class="mt-3 mb-3 boldfont">{{$branddata->brand_description}}</p>
        </div> -->
        <div class="default_row toolbar_row">
          <div class="col-4 col-md-3">
             <div class="toolbar_icon">
              <a href="{{url('brand/'.$branddata->slug)}}" ><i class="fa fa-th" aria-hidden="true"></i></a>
              <a href="{{url('listview/brand/'.$branddata->slug)}}" class="active"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
            </div>
          </div>
          <div class="col-8 col-md-9">
            <div class="toolbar_sort">
              <div class="sort_item">
                 @if(!empty($_GET['sort']))   
                    @php $sort=$_GET['sort']; @endphp
                 @endif
                <span>Sort By</span>
                <select name="sort" onchange="this.form.submit();">
                  <option value="">Select Options</option>
                  <option id="featured" value="1" @if(!empty($sort) && $sort=='1') selected @endif >Featured</option>
                  <option id="latest" value="2" @if(!empty($sort) && $sort=='2') selected @endif >Latest</option>
                </select> 
              </div>
              <!-- <div class="sort_item">
                <span>Show</span>
                <select>
                  <option>10</option>
                  <option>20</option>
                </select>
              </div> -->
            </div>
          </div>
        </div>
        <div class="row product-list mt-3" id="filteredproducts">
         @if(count($products)>0)
            @foreach($products as $product)
              <div class="col-md-12 mb-3">
                <div class="default_row product_list">
                  <div class="col-md-3 border_line">
                    @if(!empty($product->thumbnail))
                    <img src="{{ URL::asset('upload/product/thumbnail/'.$product->thumbnail) }}" alt="">
                    @else
                    <img src="/frontend/assets/images/no-image-available.png" alt="">
                    @endif
                  </div>
                  <div class="col-md-9">
                    <div class="product-info">
                      <a href="{{url('product/'.$product->slug)}}" class="product_name">{{$product->name}}</a>
                      <!-- <span class="category_name">Firecat</span> -->
                      <a href="{{url('product/'.$product->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a>
                    </div>
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
          <div class="pagination_div default_row" id="page_hide">
             {!! $products->appends(Request::except('page'))->render('pagination::bootstrap-4') !!}
          </div>
      </div>
      
    </div>
  </div>
</section>
</form>
@endsection