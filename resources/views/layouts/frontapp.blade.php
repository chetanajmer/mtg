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
<title>More Than Giftz</title>
<link rel="icon" type="image" href="{{asset('frontend/assets/images/favicon.png')}}">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/reset.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="/frontend/assets/css/lightslider.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@include('web_frontend.partials.site-chrome-head')
</head>
<body>
@include('web_frontend.partials.site-loader')

<div id="loading" style="display: none">
  <img src="/assets/images/preloader.gif" id="loading-image">
</div>

@include('web_frontend.partials.home-header')

    @yield('content')



@include('web_frontend.partials.home-footer')
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
<script src="{{ asset('web_frontend/assets/js/home.js') }}?v={{ filemtime(public_path('web_frontend/assets/js/home.js')) }}"></script>
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
