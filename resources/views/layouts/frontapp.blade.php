@php
$headersettings=\App\Models\Header_setting::where('id','1')->first();
$footersettings=\App\Models\Footer_setting::where('id','1')->first();
$tmporders=\App\Models\Tmporder::where('sessionid',session()->get('sessionid'))->get();
$brands=\App\Models\Brand::where('is_active','online')->where('featured','online')->orderby('ranking')->get()->take(3);
$footer_categories=\App\Models\Category::where('is_active','online')->where('featured','online')->get();
@endphp
<!DOCTYPE html>
<html>
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>::Welcome to Meem Industrial::</title>
<link rel="icon" type="image" href="{{asset('frontend/assets/images/favicon.png')}}">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/reset.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/lightslider.css">
</head>
<body>

<div id="loading" style="display: none">
  <img src="/assets/images/preloader.gif" id="loading-image">
</div>

<section class="default_section topbar_section">
  <div class="container">
    <div class="default_row topbar_row">
       <div class="mobilemenu">
        <a href="JavaScript:void(0)" class="quote menuicon"><i class="fa fa-bars" aria-hidden="true"></i></a>
      </div>
      <span class="welcome_text text-uppercase">Welcome to meem store!</span>
      <div class="topbar_right">
          <!-- <div class="dropdown_item">
            <div class="dropdown">
              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                Aed
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">Aed</a>
                <a class="dropdown-item" href="#">Aed</a>
                <a class="dropdown-item" href="#">Aed</a>
              </div>
            </div>
            <div class="dropdown">
              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                Eng
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">Eng</a>
                <a class="dropdown-item" href="#">Eng</a>
                <a class="dropdown-item" href="#">Eng</a>
              </div>
            </div>
          </div> -->
          <div class="account_info">
           <ul>
              <li><a href="tel:+971585351786"><i class="fa fa-whatsapp" aria-hidden="true"></i> +971 58 535 1786</a></li>
              <li><a href="mailto:order@meem.com"><i class="fa fa-envelope-o" aria-hidden="true"></i> order@meemindustrial.com</a></li>
            </ul>
          </div>
      </div>
    </div>
  </div>
</section>
<section class="default_section banner_section">
  <img src="/frontend/assets/images/banner.jpg" alt="" class="desktop_banner">
  <img src="/frontend/assets/images/mobile-banner.jpg" alt="" class="mobile_banner">
  <div class="container">
    <div class="default_row logoinnerdiv">
    <a href="{{url('/')}}" class="logo"><img src="/frontend/assets/images/logo.png" alt="meem"></a>
    <form class="header_search" action="{{ route('search_bar') }}" method="post">
         {{ csrf_field() }}
        <input type="text" name="search_val" placeholder="Search Item" id="search" required="" value="">
        <button id="search_items" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
      <div class="default_div searchbar" style="display: none" id="searchbar">
          <div class="searchbar_inner default_row" id="searchlist">
            
             

               
              
             
          </div>
         <!--  <div class="searchbar_inner default_row">
            <span class="product_name">Search Suggestions</span>
              <ul class="default_div suggestion_name mt-1">
                <li><a href="#">Suggestion Name </a></li>
              </ul>
          </div> -->
        </div> 
      </form>
  </div>
  </div>
</section>
<section class="default_section nav_section">
  <div class="container">
    <div class="default_row">
        <div class="nav_row">
          <div class="category_menu">
             <div class="category_menu_inner">
             <span class="menuquote menubar_icon" id="menudropdown"> Shop By Brands <i class="fa fa-bars" aria-hidden="true"></i></span>
             </div>
             <ul class="dropdown_submenu">
              @php
                 $main_brand=\App\Models\Brand::where('is_active','online')->get();
              @endphp

               @foreach($main_brand as $brand)

               @php $categories=\App\Models\Category::where('brand_id',$brand->id)->get();  @endphp

              @if(count($categories)>0)
              <li class="has_arrow">
              @else
               <li>
              @endif
                <a href="{{url('brand/'.$brand->slug)}}">{{$brand->brandname}}</a>
                @if(count($categories)>0)
                <div class="subcategory_menu">
                  <div class="row">
                  @foreach($categories as $cat)
                  <div class="col-md-4">
                    <a href="{{url('category/'.$cat->slug)}}" class="product_name">{{$cat->catname}}</a>
                    @php $subcategories=\App\Models\Subcategory::where('catid',$cat->id)->get();  @endphp
                    
                    @foreach($subcategories as $subcat)
                    <ul class="">
                      <li><a href="{{url('subcategory/'.$subcat->slug)}}">{{$subcat->catname}}</a></li>
                    </ul>
                    @endforeach
                  </div>
                  @endforeach
                  </div>
                </div>
                @endif
              </li>
              @endforeach
             </ul>
          </div>
          <div class="navication">
            <nav class="navbar navbar-expand-lg navbar-light">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                  <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('aboutus')}}">About Us</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('brands')}}">Brands</a>
                  </li>
                  <!-- <li class="nav-item">
                     <a class="nav-link" href="#">B2B Enquires</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">Vendors</a>
                  </li> -->
                  <li class="nav-item">
                     <a class="nav-link" href="{{url('contactus')}}">Contact us</a>
                  </li>
                </ul>
              </div>
            </nav>
          </div>
        </div>
        <div class="cart_icon position-relative" >
          <a href="{{url('cart')}}"  id="cart_count">
           <i class="minicart-icon"></i>
           @if(count($tmporders)>0)
           <span class="cart-count">{{count($tmporders)}}</span>
           @else
            <span class="cart-count">0</span>
           @endif
           </a>
        </div>
    </div>
  </div>
</section>
      

    @yield('content')



<section class="default_section newsletter_section">
  <div class="container">
    <div class="default_row newsletter_row">
      <div class="col-md-12 col-lg-5 subscribe_div">
        <h4>Subscribe to our newsletter</h4>
        <p>Get all the latest information on products and offers</p>
      </div>
      <div class="col-md-12 col-lg-7">
         <form class="default_row newsletter_form">
           <input type="text" name="" placeholder="Enter Email"  id="subscribername">
           <!-- <input type="button" name="" value="Subscribe" class="btn btn-secondary subscribe_btn "> -->
           <button type="button" class="btn btn-secondary subscribe_btn " >Subscribe</button>
         </form>
      </div>
    </div>
  </div>
</section>

<section class="default_section footer_section">
  <div class="container">
    <div class="default_row footer_row">
      <div class="col-12 col-md-4 col-lg-3">
        <a href="{{url('/')}}" class="footer_logo"><img src="/frontend/assets/images/footer_logo.png" alt=""></a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 mt-3">
        <ul class="default_div footer_menu">
          <li><a href="{{url('aboutus')}}">About Us</a></li>
           <li><a href="{{url('brands')}}">Brands</a></li>
          <!-- <li><a href="#">B2B Enquires</a></li>
          <li><a href="#">Vendors</a></li> -->
          <li><a href="{{url('contactus')}}">Contact Us</a></li>
        </ul>
      </div>
     <!-- <div class="col-6 col-md-4 col-lg-2 mt-3">
        <ul class="default_div footer_menu">
          <li><a href="#">Track Order</a></li>
          <li><a href="#">Cart</a></li>
          <li><a href="#">Sign in</a></li>
          <li><a href="#">Help</a></li>
          <li><a href="#">Wishlist</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div> -->
      <div class="col-6 col-md-4  col-lg-2 mt-3">
        <ul class="default_div footer_menu">
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Return Policy</a></li>
          <!-- <li><a href="#">Money-back</a></li>
          <li><a href="#">Support</a></li>
          <li><a href="#">Shipping</a></li> -->
        </ul>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-3">
        <div class="default_row footercall_info">
          <span class="call_icon"><img src="/frontend/assets/images/call_icon.png" alt=""></span>
          <span class="call_detail">
            <strong>Call Us</strong>
            <a href="tel:{{$headersettings->headerphone}}">{{$headersettings->headerphone}}</a>
          </span>
        </div>
        <div class="default_row footercall_info">
          <span class="call_icon"><img src="/frontend/assets/images/mail_icon.png" alt=""></span>
          <span class="call_detail">
            <strong>Mail Us</strong>
            <a href="mailto:{{$headersettings->headeremail}}">{{$headersettings->headeremail}}</a>
          </span>
        </div>
       <!--  <ul class="default_div footer_menu">
          <li><a href="mailto:{{$headersettings->headeremail}}">{{$headersettings->headeremail}}</a></li>
        </ul> -->
        <ul class="default_row social_icon">
          @if(!empty($footersettings->facebook))
          <li><a href="{{$footersettings->facebook}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
          @endif

          @if(!empty($footersettings->linkedin))
          <li><a href="{{$footersettings->linkedin}}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
          @endif

           @if(!empty($footersettings->instagram))
          <li><a href="{{$footersettings->instagram}}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
          @endif

          @if(!empty($footersettings->twitter))
          <li><a href="{{$footersettings->twitter}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
          @endif

          @if(!empty($footersettings->youtube))
          <li><a href="{{$footersettings->youtube}}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
          @endif
        </ul>
      </div>
    </div>

    <div class="default_row footer_catmenu">
      <strong>Trending Categories</strong>
      <ul class="default_row footer_catlink">
        @foreach($footer_categories as $cat)
         <li><a href="{{url('category/'.$cat->slug)}}">{{$cat->catname}}</a></li>
        @endforeach
       <!--  <li><a href="#">Tools & Equipment</a></li>
        <li><a href="#">Hardware Tools</a></li>
        <li><a href="#">Lawn & Garden</a></li>
        <li><a href="#">Painting</a></li>
        <li><a href="#">Plumbing & Sanitary</a></li> -->      
      </ul>
    </div>
 
    <!-- <div class="default_row footer_catmenu">
      <strong>IT and Electronics</strong>
      <ul class="default_row footer_catlink">
        <li><a href="#">Network Camera</a></li>
        <li><a href="#">Routers</a></li>
        <li><a href="#">Media Converters</a></li>
        <li><a href="#">Unified Storage</a></li>
        <li><a href="#">Fiber Products</a></li>
        <li><a href="#">Copper Products</a></li>
        <li><a href="#">Accessories</a></li>
      </ul>
    </div> -->
  </div>
</section>
<section class="default_section copyright_section">
  <div class="container">
    <p>&copy; Copyright Meem Industrial <script>document.write(new Date().getFullYear())</script>. All Rights Reserved. Design & Develop by <a href="https://silverpixelz.com" target="_blank">SilverPixelz</a></p>
  </div>
</section>
<div class="callsidecard" id="quotebox">
  <div class="close shadowpt"></div>
  <a href="javascript:void(0);" class="close btnsclose">x</a>
   <div class="innercallwrap">
     <a href="#" class="default_div logo"><img src="/frontend/assets/images/logo.png" alt="meem"></a>
     
      <ul class="default_div mobile-menu-item">
        <li> <a class="nav-link" href="{{url('/')}}">Home</a></li>
        <li> <a class="nav-link" href="{{url('aboutus')}}">About Us</a></li>
        <li><a href="{{url('brands')}}">Brands</a></li>
        <!-- <li><a href="#">B2b Enquires</a></li>
        <li><a href="#">Vendors</a></li> -->
        <li> <a class="nav-link" href="{{url('contactus')}}">Contact us</a></li>
      </ul>
   </div>
</div>
<div class="menuside" id="menubox">
  <div class="close shadowpt"></div>
  <a href="javascript:void(0);" class="close btnsclose">x</a>
    <div class="default_div mobilecategory">

     @php
        $main_brand=\App\Models\Brand::where('is_active','online')->get();
      @endphp

     @foreach($main_brand as $brand)
      @php $categories=\App\Models\Category::where('brand_id',$brand->id)->get();  @endphp
    <div class="c-accordion">
      <ul class="default_div menucategry">
        <li> <a href="{{url('brand/'.$brand->slug)}}">{{$brand->brandname}}</a>
         @if(count($categories)>0) 
          <a href="javascript:void(0)" class="js-btn"></a>
          <div class="accordion__body">
          
             @foreach($categories as $cat)
            <div class="c-accordion">
              <ul class="menucategry_sub">
                <li><a href="{{url('category/'.$cat->slug)}}">{{$cat->catname}}</a>
                  @php $subcategories=\App\Models\Subcategory::where('catid',$cat->id)->get();  @endphp
                  <a href="javascript:void(0)" class="js-btn"></a>
                  
                  <div class="accordion__body">
                    <ul class="sub-menucat">
                       @foreach($subcategories as $subcat)
                      <li><a href="{{url('subcategory/'.$subcat->slug)}}">{{$subcat->catname}}</a></li>
                        @endforeach
                    </ul>
                  </div>
                
                </li>
              </ul>
            </div>
            @endforeach
         
          </div>
             @endif
        </li>
      </ul>
    </div>
    @endforeach
    
    
    </div>
</div>


<script src="/frontend/assets/js/jquery.min.js"></script>
<script src="/frontend/assets/js/bootstrap.bundle.min.js"></script>
<script src="/frontend/assets/js/custom.js"></script>
<script src="/frontend/assets/js/owl.carousel.js"></script>
<script src="/frontend/assets/js/lightslider.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>
<script type="text/javascript">      
 var successClick = function(message){
  $.notify({
    // options
    title: '<strong>Alert</strong><br>',
    message:message,
  icon: 'fa fa-check',
   
    target: '_blank'
},{
    // settings
    element: 'body',
    //position: null,
    type: "success",
    //allow_dismiss: true,
    //newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 99999,
    delay: 3300,
    timer: 500,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animated bounceIn',
        exit: 'animated bounceOut'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
  template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
    '<span data-notify="icon"></span> ' +
    '<span data-notify="title">{1}</span> ' +
    '<span data-notify="message">{2}</span>' +
    '<div class="progress" data-notify="progressbar">' +
      '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
    '</div>' +
    '<a href="{3}" target="{4}" data-notify="url"></a>' +
  '</div>' 
});
}

var infoClick = function(){
  $.notify({
    // options
    title: '<strong>Info</strong>',
    message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
  icon: 'glyphicon glyphicon-info-sign',
},{
    // settings
    element: 'body',
    position: null,
    type: "info",
    allow_dismiss: true,
    newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 1031,
    delay: 3300,
    timer: 500,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animated bounceInDown',
        exit: 'animated bounceOutUp'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
});
}

var warningClick = function(){
  $.notify({
    // options
    title: '<strong>Warning</strong>',
    message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
  icon: 'fa fa-exclamation-triangle',
},{
    // settings
    element: 'body',
    position: null,
    type: "warning",
    allow_dismiss: true,
    newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 1031,
    delay: 3300,
    timer: 1000,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animated bounceIn',
        exit: 'animated bounceOut'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
});
}

var dangerClick = function(message){
  $.notify({
    // options
    title: '<strong>Error</strong><br>',
    message: message,
  icon: 'fa fa-exclamation-triangle',
},{
    // settings
    element: 'body',
    position: null,
    type: "danger",
    allow_dismiss: true,
    newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 9999,
    delay: 3300,
    timer: 1000,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animated flipInY',
        exit: 'animated flipOutX'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
    '<span data-notify="icon"></span> ' +
    '<span data-notify="title">{1}</span> ' +
    '<span data-notify="message">{2}</span>' +
    '<div class="progress" data-notify="progressbar">' +
      '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
    '</div>' +
    '<a href="{3}" target="{4}" data-notify="url"></a>' +
  '</div>' 
});
}

</script>

<script>
$(document).ready(function(){
$("#option-choice-form").submit(function (e) {
  e.preventDefault();
         $.ajax({
                url:"{{ route('addtocart') }}",
                method:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: $('#option-choice-form').serializeArray(),
                success:function (data) 
                {
                  console.log(data);
                  successClick(data);
                  $( "#cart_count" ).load(window.location.href + " #cart_count" );
                } 
              });
         });
});


$(document).ready(function(){
$("#variant-form").submit(function (e) {
  e.preventDefault();
         $.ajax({
                url:"{{ route('addtocart') }}",
                method:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: $('#variant-form').serializeArray(),
                success:function (data) 
                {
                  console.log(data);
                  successClick(data);
                  $( "#cart_count" ).load(window.location.href + " #cart_count" );
                   $('#exampleModal').modal('hide');
                } 
              });
         });
});
</script>
<script>
function get_qty()
{
   var quantity=$('#quantity').val();
   $('#qty').val(quantity);
}

function delete_product($id) {
//  alert($id);
 $.ajax({
    type:"get",
    url:"{{ route('deleteproduct') }}",
    method:"POST",
    data: {"_token": "{{ csrf_token() }}","product_id":$id
    },
    success:function (data)
    {
      successClick(data);
      $( "#cart_count" ).load(window.location.href + " #cart_count" );
      location.reload();
    } 
  });
}

function clear_cart() {
//  alert($id);
 $.ajax({
    type:"get",
    url:"{{ route('clearcart') }}",
    method:"POST",
    data: {"_token": "{{ csrf_token() }}"
    },
    success:function (data)
    {
      successClick(data);
      $( "#cart_count" ).load(window.location.href + " #cart_count" );
      location.reload();
    } 
  });
}

function incrementqty($data) 
{
 
 var value=$data.split(',');
 var id=value[0];
 var quantity=value[1];
 $.ajax({
    url:"{{ route('incrementquantity') }}",
    method:"POST",
    data: {"_token": "{{ csrf_token() }}","id":id,"quantity":quantity},
    success:function (data)
    {
      console.log(data);
      successClick(data);
      $( "#mini_cart" ).load(window.location.href + " #mini_cart" );
      $( "#cart_count" ).load(window.location.href + " #cart_count" );
      $( "#cart_total" ).load(window.location.href + " #cart_total" );
      location.reload();
    } 
  });
}

function decrementqty($data) {

var value=$data.split(',');
var id=value[0];
var quantity=value[1];

$.ajax({
     url:"{{ route('decrementquantity') }}",
     method:"POST",
     data: {"_token": "{{ csrf_token() }}","id":id,"quantity":quantity},
     success:function (data)
     {
       console.log(data);
       successClick(data);
       $( "#mini_cart" ).load(window.location.href + " #mini_cart" );
      $( "#cart_count" ).load(window.location.href + " #cart_count" );
      $( "#cart_total" ).load(window.location.href + " #cart_total" );   
       location.reload();
     } 
   });
}

</script>
<script>
   $(document).ready(function () {

    var categories = [];
   
    var brand_slug=$('#brand').val();
    
    $('input[name="category_checkbox[]"]').on('change', function (e) {

        e.preventDefault();
        categories = []; 
        $('input[name="category_checkbox[]"]:checked').each(function()
        {
            categories.push($(this).val());
        });

        $.ajax
        ({
            url:"{{url('category_filter')}}",
            method:"POST",
            data:{"_token": "{{ csrf_token() }}","categories":categories,"brand_slug":brand_slug},
            beforeSend: function()
            {
              $("#loading").show();
            },
            success:function(status)
            {
              console.log(status);
              $("#loading").hide();
              $('#filteredproducts').html(status);   
              $('#page_hide').hide();     
            }
        });         

    });

   

});
    
</script>
<script>
   $(document).ready(function () {

    var subcategories=[];
    var cat_slug=$('#category').val();
    

    $('input[name="subcategory_checkbox[]"]').on('change', function (e) {

        e.preventDefault();
        subcategories = []; 
        $('input[name="subcategory_checkbox[]"]:checked').each(function()
        {
            subcategories.push($(this).val());
        });

        $.ajax
        ({
            url:"{{url('sub_category_filter')}}",
            method:"POST",
            data:{"_token": "{{ csrf_token() }}","subcategories":subcategories,"cat_slug":cat_slug},
            beforeSend: function()
            {
              $("#loading").show();
            },
            success:function(status)
            {
              console.log(status);
              $("#loading").hide();
              $('#filteredproducts').html(status);  
               $('#page_hide').hide();      
            }
        });         

    });

});
    
</script>
<script>

$(".subscribe_btn").on("click", function () {

    
    var subscribername= $('#subscribername').val();
    var checkemail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

      if(subscribername!="")
      {
          if(checkemail.test(subscribername))
              {    
                  $.ajax({

                  url:"{{ route('addsubscriber') }}",
                  method:"POST",
                  data: {"_token": "{{ csrf_token() }}","subscribername": subscribername,},
                  success:function (data) 
                    {       
                      console.log(data);
                      successClick(data);
                      $('#subscribername').val('');         
                    } 
                 });
              }
              else
              {   
                  dangerClick('Invalid Email Address !');
              }  
      }
      else
      {
          dangerClick('Please Enter Email Address ! ');
      }

                  
        });
        

</script>
<script>

$("#search").on("keyup", function () {

    var search_val=$('#search').val();
    if(search_val!='')
    {
      $('#searchbar').show();
      $.ajax({

                  url:"{{ route('searchbar') }}",
                  method:"POST",
                  datatype:'json',
                  data: {"_token": "{{ csrf_token() }}","search_val": search_val},
                  success:function (response) 
                  {       
                    console.log(response);
                    var totalcount=response.data.length;
                  
                    if(totalcount>0)
                    {
                        $('#searchlist').empty();
                        $('#searchlist').append('<span class="product_name">Recommended products</span><a href="https://meemindustrial.spdemoserver.com/search_bar/'+search_val+'" class="browse_btn">View All <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>');
                        $('#search').val(search_val);
                        $.each(response.data, function( index, value ) {
                        $('#searchlist').append(' <ul class="product_searchlist" ><li><a href="https://meemindustrial.spdemoserver.com/product/'+value.slug+'"><span class="product_img"><img src="https://meemindustrial.spdemoserver.com/upload/product/thumbnail/'+value.thumbnail+'" alt=""></span><span class="product_info"><strong>'+value.modelno+'</strong><span id="">'+value.name+'</span></span></a></li></ul>')

                        })
                    }
                    // else
                    // {
                    //       $('#searchlist').append(' <li><span class="product_info">No Products Found</span></a></li>')
                    // }
                    
                                
                             
                  } 
                 });
    }
    else
    {
      $('#searchbar').hide();
    }
                 
  });
</script>

<script>
  function getvariant($slug)
  {
   
     var master_price=$('#price').val();

      $.ajax({

              url:"{{ route('variant_price') }}",
              method:"POST",
              data: {"_token": "{{ csrf_token() }}","slug": $slug},
              success:function (data) 
              {       
                console.log(data);

                $('#exampleModal').modal('show');

                $('#product_title').html(data.product.name);
                $('#product_modalno').html("Part No : "+data.product.modelno);
                $('#master_price').html("Master Price :"+master_price);
                $('#product_price').html("Price :"+data.product.price);
                $('#customized_price').html("Customized Price :"+data.product.cprice);
                var totalprice=parseInt(data.product.price)+parseInt(master_price);
          
                $('#total_price').html("Total Price :"+totalprice);

                if(data.product.shortdescription!='')
                {
                   $('#shortdescription').html(data.product.shortdescription);
                }
                else
                {
                   $('#shortdescription').html("No Description Available");
                }
                

                 // form data
                $('#variant_id').val(data.product.id);
                $('#variant_modelno').val(data.product.modelno);
                $('#variant_product_name').val(data.product.name);
                $('#variant_specification').val(data.product.specification);
                $('#variant_slug').val(data.product.slug);
                $('#variant_thumbnail').val(data.product.thumbnail);
                $('#variant_price').val(totalprice);
                 $('#variant_cprice').val(data.product.cprice);
                // successClick(data);
                // $('#subscribername').val('');         
              } 
            });
  }


  
function getcprice($data)
{
  
  var value=$data.split(",");
  var cprice=value[0];
  var id=value[1];
  // alert(modelno);
  if ($('#c_price'+id).is(':checked')) 
    {
       $('#customized_price'+id).show();
       $('#totalprice1'+id).show();
       $('#totalprice'+id).hide();
    }
    else
    {
       $('#customized_price'+id).hide();
       $('#totalprice1'+id).hide();
       $('#totalprice'+id).show();
    }
}
</script>

<style>
    #loading {
  position: fixed;
  display: block;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  text-align: center;
  opacity: 0.7;
  background-color: transparent;
  z-index: 99;
}

#loading-image {
  position: absolute;
  top: 50%;
  left: 50%;
  right: 50%;
  z-index: 100;
}

</style>
</body>
</html>
