
@php
$categorydata=\App\Models\Category::findorFail($catid);
@endphp
@extends('layouts.frontapp')

@section('content')

<form action="{{ route('attfilter') }}" id="filterform" method="post" >{{ csrf_field() }}  
            <input type="hidden" name=url value="category/{{$categorydata->slug}}">
<section class="default_section breadcrumb_section">
  <div class="container">
     <ul class="default_row breadcrumb_menu">
       <li><a href="{{url('/')}}">Home</a></li>
       @php
          $breadcrumb_brand=\App\Models\Brand::where('id',$categorydata->brand_id)->first();
       @endphp
        <li><a href="{{url('brand/'.$breadcrumb_brand->slug)}}">{{$breadcrumb_brand->brandname}}</a></li>
       <li>{{$categorydata->catname}}</li>
     </ul>
  </div>
</section>
<section class="default_section productdetail_section">
  <div class="container">
    <div class="row category_page_row">
      <div class="col-md-12 col-lg-3">
        <div class="category_filterdiv">
         
          <div class="c-accordion">
              <span class="category_heading">Subcategories</span>
              <a href="javascript:void(0)" class="js-btn js-is-active"></a>
              <div class="accordion__body">
                  @if(count($subcategories)>0)
                    @foreach($subcategories as  $subcategories)
                    <div class="checkbox_list">{{$subcategories->catname}}
                      <input type="checkbox" id="subcategory_checkbox" name="subcategory_checkbox[]" value="{{$subcategories->id}}">
                      <input type="hidden" name="category" id="category" value="{{$categorydata->slug}}">
                      <span class="checkmark"></span>
                    </div>
                @endforeach
                @else
                  <div class="error-text">There is no Subcategory!</div>
                @endif
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
          <!-- <div class="default_div">
            <img src="/frontend/assets/images/leftcategory_img.jpg" alt="" class="w-100">
          </div> -->
        </div>
      </div>
      <div class="col-md-12 col-lg-9">
        <!-- <div class="default_div category_banner"> 
               @if(!empty($categorydata->bannerimage))
                <img src="{{ URL::asset('upload/category/'.$categorydata->bannerimage) }}" alt="">
                @else
                <img src="/frontend/assets/images/categorypage_banner.jpg" alt="">
                @endif
        </div>
        <div class="clearfix"></div>
        <div class="default_div small-desc">
          <p class="mt-3 mb-3 boldfont">{{$categorydata->catdescription}}</p>
        </div> -->
        <div class="default_row toolbar_row">
          <div class="col-4 col-md-3">
             <div class="toolbar_icon">
              <a href="{{url('category/.',$catslug)}}" class="active"><i class="fa fa-th" aria-hidden="true"></i></a>
              <a href="{{url('listview/category/.',$catslug)}}"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
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
             <!--  <div class="sort_item">
                <span>Show</span>
                <select>
                  <option>10</option>
                  <option>20</option>
                </select>
              </div> -->
            </div>
          </div>
        </div>
        <div class="row product-grid" id="filteredproducts">
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
              <!-- <a href="{{url('product/'.$product->slug)}}" class="cart_btn"><i class="fa fa-eye" aria-hidden="true"></i> View Now</a> -->
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