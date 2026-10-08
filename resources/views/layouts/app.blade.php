@php
$settings=\App\Models\General_setting::where('id',1)->first();
$role=session()->get('role');
$email=session()->get('username');
$admin_permission=\App\Models\Admin::where('id',session()->get('userid'))->first();    
@endphp
<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="/assets/images/users/avatar-1.jpg">

        <title>{{$settings->site_name}}</title>

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="/assets/plugins/morris/morris.css">
        <!-- Plugins css-->
        <link href="/assets/plugins/custombox/css/custombox.css" rel="stylesheet">
        <link href="/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
        <link href="/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />

        <!-- Summernote -->
        <link href="/assets/plugins/summernote/summernote.css" rel="stylesheet" />
          <link href="/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css">

        <!-- Dropzone css -->
        <link href="/assets/plugins/dropzone/dropzone.css" rel="stylesheet" type="text/css" />

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/image-uploader.min.css" rel="stylesheet" type="text/css" />
        <!-- pickers -->

        <link href="/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
        <link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="/assets/plugins/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
        <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">  
        <!-- Datatables -->
        <link href="/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
        
<link href="/assets/plugins/croppie/croppie.css" rel="stylesheet">
<style type="text/css">
    label.cabinet{
    display: block;
    cursor: pointer;
}


label.cabinet input.file{
    position: relative;
    height: 100%;
    width: auto;
    opacity: 0;
    -moz-opacity: 0;
  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
  margin-top:-30px;
}

#upload-demo{
    width: 250px;
    height: 250px;
  padding-bottom:25px;
}
figure figcaption {
    position: absolute;
    bottom: 0;
    color: #fff;
    width: 100%;
    padding-left: 9px;
    padding-bottom: 5px;
    text-shadow: 0 0 10px #fff;
}
</style>
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="/assets/js/modernizr.min.js"></script>

<!-- 
        <style >
          .btn-default{

                background-color: <?php  $settings->admincolor; ?> !important;
                border: 1px solid <?php  $settings->admincolor; ?> !important
          }
          

          #sidebar-menu > ul > li > a.active 
          {
            color:<?php  $settings->admincolor; ?> !important;
          }

          #sidebar-menu ul ul a:hover {
            color:<?php  $settings->admincolor; ?> !important;
          }

          #sidebar-menu > ul > li > a:hover {
            color:<?php  $settings->admincolor; ?> !important;
          } 

          #sidebar-menu ul ul li.active a {
             color:<?php  $settings->admincolor; ?> !important;

          }
        </style> -->

        <style>
          /* ===== Admin theme — clean & decent ===== */
          body{background:#f1f3f7;}
          .content-page{background-color:#f1f3f7;}
          .content{background-image:none !important;background:#f1f3f7;}
          .page-title{color:#334155;}

          /* Topbar — light grey */
          .navbar-default{background-color:#f8fafc;border-bottom:1px solid #e2e8f0;}
          .topbar{box-shadow:0 1px 3px rgba(15,23,42,.06);}
          .topbar .topbar-left{background-color:#ffffff;border-right:1px solid #e2e8f0;}
          .nav > li > a{color:#475569 !important;}
          .button-menu-mobile{color:#64748b !important;}
          .navbar-default .navbar-nav > .open > a,
          .navbar-default .navbar-nav > .open > a:focus,
          .navbar-default .navbar-nav > .open > a:hover{background-color:rgba(15,23,42,.05);}

          /* Sidebar — clean white */
          .side-menu.left{background:#ffffff;border-right:1px solid #e9edf3;}
          #sidebar-menu > ul > li > a{color:#475569;border-left:3px solid transparent;}
          #sidebar-menu > ul > li > a:hover{color:#4a76fd;background:#f8fafc;}
          #sidebar-menu > ul > li > a.active{background:#f4f8fb !important;border-left:3px solid #4a76fd;color:#4a76fd !important;}
          #sidebar-menu ul li .menu-arrow{color:#94a3b8;}
          #sidebar-menu ul ul{background:#ffffff;}
          #sidebar-menu ul ul a{color:#64748b;}
          #sidebar-menu ul ul a:hover{color:#4a76fd;}
          #sidebar-menu ul ul li.active a{color:#4a76fd;}
          #sidebar-menu .subdrop{background:#f4f8fb !important;border-left:3px solid #4a76fd;color:#4a76fd !important;}
          .menu-title{color:#94a3b8;}

          /* Collapsed sidebar — same white theme */
          #wrapper.enlarged .left.side-menu{background:#ffffff;border-right:1px solid #e9edf3;}
          #wrapper.enlarged #sidebar-menu ul ul{background-color:#ffffff;border:1px solid #e2e8f0;}
          #wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > a{background:#f4f8fb;color:#4a76fd;border-color:#4a76fd;}
        </style>

        @stack('header-scripts')
    </head>


    <body class="fixed-left">

        <div id="loading" style="display: none">
                <img src="/assets/images/preloader.gif" id="loading-image">
            </div>
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    
                        <a href="{{url('/')}}" class="logo">
                            <img src="/assets/images/logo.png" alt="logo-img" class="large_logo">
                            <img src="/assets/images/logo_small.png" alt="logo-img" class="small_logo">
                        </a>
                        
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left waves-effect waves-light">
                                    <i class="md md-menu"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>

                            <!-- <ul class="nav navbar-nav hidden-xs">
                                <li><a href="#" class="waves-effect waves-light">Files</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                                       role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span
                                            class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </li>
                            </ul> -->

                            <!-- <form role="search" class="navbar-left app-search pull-left hidden-xs">
                                 <input type="text" placeholder="Search..." class="form-control">
                                 <a href=""><i class="fa fa-search"></i></a>
                            </form> -->


                            <ul class="nav navbar-nav navbar-right pull-right">
                                <!--  -->
                                <li class="hidden-xs">
                                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="icon-size-fullscreen"></i></a>
                                </li>
                                <li class="hidden-xs">
                                    <a href="{{url('cms-settings')}}" class="right-bar-toggle waves-effect waves-light"><i class="icon-settings"></i></a>
                                </li>
                                <li class="dropdown top-menu-item-xs">
                                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="/assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                    <ul class="dropdown-menu">
                                        <!-- <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                                        <li><a href="javascript:void(0)"><i class="ti-settings m-r-10 text-custom"></i> Settings</a></li>
                                        <li><a href="javascript:void(0)"><i class="ti-lock m-r-10 text-custom"></i> Lock screen</a></li> -->
                                        <!-- <li class="divider"></li> -->
                                        <li><a href="{{url('adminlogout')}}" ><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>

                            <li class="text-muted menu-title">Navigation</li>

                            
                            <li>
                                <a href="{{url('dashboard')}}" class="waves-effect"><i class="ti-home"></i><span> Dashboard</span></a>
                              
                            </li>

                            <!-- <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-paint-bucket"></i> <span> UI Kit </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="ui-buttons.html">Buttons</a></li>
                                    <li><a href="ui-loading-buttons.html">Loading Buttons</a></li>
                                    <li><a href="ui-panels.html">Panels</a></li>
                                    <li><a href="ui-portlets.html">Portlets</a></li>
                                    <li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
                                    <li><a href="ui-tabs.html">Tabs</a></li>
                                    <li><a href="ui-modals.html">Modals</a></li>
                                    <li><a href="ui-progressbars.html">Progress Bars</a></li>
                                    <li><a href="ui-notification.html">Notification</a></li>
                                    <li><a href="ui-images.html">Images</a></li>
                                    <li><a href="ui-carousel.html">Carousel</a>
                                    <li><a href="ui-video.html">Video</a>
                                    <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                                    <li><a href="ui-typography.html">Typography</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-light-bulb"></i><span class="label label-primary pull-right">9</span><span> Components </span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="components-grid.html">Grid</a></li>
                                    <li><a href="components-widgets.html">Widgets</a></li>
                                    <li><a href="components-nestable-list.html">Nesteble</a></li>
                                    <li><a href="components-range-sliders.html">Range sliders</a></li>
                                    <li><a href="components-masonry.html">Masonry</a></li>
                                    <li><a href="components-animation.html">Animation</a></li>
                                    <li><a href="components-sweet-alert.html">Sweet Alerts</a></li>
                                    <li><a href="components-treeview.html">Treeview</a></li>
                                    <li><a href="components-tour.html">Tour</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-spray"></i> <span> Icons </span> <span class="menu-arrow"></span> </a>
                                <ul class="list-unstyled">
                                    <li><a href="icons-glyphicons.html">Glyphicons</a></li>
                                    <li><a href="icons-materialdesign.html">Material Design</a></li>
                                    <li><a href="icons-ionicons.html">Ion Icons</a></li>
                                    <li><a href="icons-fontawesome.html">Font awesome</a></li>
                                    <li><a href="icons-themifyicon.html">Themify Icons</a></li>
                                    <li><a href="icons-simple-line.html">Simple line Icons</a></li>
                                    <li><a href="icons-weather.html">Weather Icons</a></li>
                                    <li><a href="icons-typicons.html">Typicons</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-pencil-alt"></i><span> Forms </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="form-elements.html">General Elements</a></li>
                                    <li><a href="form-advanced.html">Advanced Form</a></li>
                                    <li><a href="form-validation.html">Form Validation</a></li>
                                    <li><a href="form-pickers.html">Form Pickers</a></li>
                                    <li><a href="form-wizard.html">Form Wizard</a></li>
                                    <li><a href="form-mask.html">Form Masks</a></li>
                                    <li><a href="form-summernote.html">Summernote</a></li>
                                    <li><a href="form-wysiwig.html">Wysiwig Editors</a></li>
                                    <li><a href="form-code-editor.html">Code Editor</a></li>
                                    <li><a href="form-uploads.html">Multiple File Upload</a></li>
                                    <li><a href="form-xeditable.html">X-editable</a></li>
                                    <li><a href="form-image-crop.html">Image Crop</a></li>
                                </ul>
                            </li>
 -->
                        
                            <li>
                                <a href="{{url('listbrand')}}" class="waves-effect"><i class="ti-layout-grid2"></i><span>Brands</span></a>  
                            </li>
                         
                             <!-- <li>
                                <a href="{{url('listparentcategory')}}" class="waves-effect"><i class="ti-menu-alt"></i><span>Parent Categories</span></a>  
                            </li> -->
                      
                        
                           <!--  <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-menu-alt"></i><span>Category </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('listcategory')}}">Categories</a></li>   
                                </ul>
                            </li> -->

                            
                             <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-layout-menu-v"></i><span>Categories </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('listcategory')}}">Categories</a></li>
                                       
                                    <li><a href="{{url('listsubcat')}}">Sub Categories</a></li>
                                      
                                    <li><a href="{{url('transfer_category')}}">Transfer Categories</a></li>
                                        
                                </ul>
                            </li>
                     
 
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i><span> Product </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                   <!--  <li class="has_sub">
                                        <a href="javascript:void(0);" class="waves-effect"><span> Bulk Product </span> <span class="menu-arrow"></span></a>
                                        <ul class="list-unstyled">
                                             <li><a href="{{url('add-products')}}">New Product</a></li>
                                            <li><a href="{{url('add_image')}}">Upload Image</a></li>
                                        </ul>
                                    </li> -->
                                    <li><a href="{{url('add_product')}}">Single Product</a></li>
                                    <li><a href="{{url('bulkdeleteproducts')}}">Delete Products</a></li>


                                    
                                     <li><a href="{{url('listproduct')}}">Product List</a></li>
                                   
                                    <li><a href="{{url('color')}}">Colors</a></li>
                                    <li><a href="{{url('branding-options')}}">Branding Options</a></li>
                                    <li><a href="{{url('occasions')}}">Occasion Tags</a></li>
                                    <li><a href="{{url('preferences')}}">Preferences</a></li>
                                    <!-- <li><a href="{{url('variation')}}">Variations</a></li> -->

                                </ul>
                            </li>
                           
                            <li>
                                <a href="{{url('orders')}}" class="waves-effect"><i class="ti-shopping-cart-full"></i><span>Store Enquiries</span></a>
                              
                            </li>
                          

                            <li>
                                <a href="{{url('store-user')}}" class="waves-effect">
                                  <i class="ti-user"></i><span>Store Users</span>
                                </a>
                              
                            </li>

                          
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-layout-slider"></i><span> Sliders </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('show-slider')}}">New Slider</a></li>
                                    <!-- <li><a href="{{url('edit-products')}}">Edit Product</a></li> -->
                                    <li><a href="{{url('list-slider')}}">Slider List</a></li>

                                </ul>
                            </li>
                           


                            
                             <li>
                                <a href="{{url('subscribers')}}" class="waves-effect"><i class="ti-face-smile"></i><span> Subscribers</span></a>
                            </li>
                          


                            
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-layout-slider-alt"></i><span>Banners</span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="has_sub">
                                        <a href="javascript:void(0);"class="waves-effect"><span>Small Banner</span>  <span class="menu-arrow"></span></a>
                                        <ul class="list-unstyled" style="">
                                            <li><a href="{{url('small_banners_section1')}}"><span>Section 1</span></a></li>
                                            <li><a href="{{url('small_banners_section2')}}"><span>Section 2</span></a></li>
                                            <li><a href="{{url('small_banners_section3')}}"><span>Section 3</span></a></li>
                                        </ul>
                                    </li>

                                     <li><a href="{{url('banners')}}" >Full Banner</a></li>

                                     @if($role=="superadmin")

                                     <li><a href="{{url('superadmin-settings')}}" >Super Admin Restriction</a></li>
                                     @endif
                                </ul>
                            </li>
                       
                            <li>
                                <a href="{{url('instock-notifier')}}" class="waves-effect"><i class="ti-comment-alt"></i><span> Instock Notifier</span></a>
                            </li>

                              <li>
                                <a href="{{url('b2b-enquiry')}}" class="waves-effect"><i class="ti-info-alt"></i><span> B2B Enquiry</span></a>
                              </li>

                              <li>
                                <a href="{{url('reviews')}}" class="waves-effect"><i class="ti-comments-smiley"></i><span> Reviews</span></a>
                              </li>

                             <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-book"></i><span> Reports </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('date_report')}}">Date Reports</a></li>
                                    <li><a href="{{url('user_report')}}">User Reports</a></li>
                                    <li><a href="{{url('product_report')}}">Products Reports</a></li>
                                </ul>
                            </li>
                            
                             <li><a href="{{url('list-coupon')}}" ><i class="ti-tag"></i>Coupon</a></li>

                             <li>
                                <a href="{{url('abandoned_order')}}" class="waves-effect"><i class="ti-shopping-cart-full"></i><span>Abandoned Order</span></a>
                            </li>

                            <li>
                                <a href="{{url('flipbook')}}" class="waves-effect"><i class="ti-book"></i><span>Flipbook</span></a>
                            </li>

                           
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i><span> Setup & Config </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('cms-settings')}}" >CMS Settings</a></li>
                                    <li><a href="{{url('seo-settings')}}" >SEO Settings</a></li>
                                    <li><a href="{{url('header-settings')}}" >Header Settings</a></li>
                                    <li><a href="{{url('footerlevel1')}}" >Footer L1 Settings </a></li>
                                    <li class="has_sub">
                                        <a href="javascript:void(0);"class="waves-effect"><span>Footer L2 Settings</span>  <span class="menu-arrow"></span></a>
                                        <ul class="list-unstyled" style="">
                                            <li><a href="{{url('footer-section1')}}"><span>Section 1</span></a></li>
                                            <li><a href="{{url('footer-section2')}}"><span>Section 2</span></a></li>
                                            <li><a href="{{url('footer-section3')}}"><span>Section 3</span></a></li>
                                        </ul>
                                    </li>

                                    <li class="has_sub">
                                        <a href="javascript:void(0);"class="waves-effect"><span>Homepage Settings</span>  <span class="menu-arrow"></span></a>
                                        <ul class="list-unstyled" style="">
                                            <li><a href="{{url('category-section')}}"><span>Display Category</span></a></li>
                                            <li><a href="{{url('tag-section')}}"><span>Display Meta Tags</span></a></li>
                                            <li><a href="{{url('content-section')}}"><span>Homepage Content</span></a></li>
                                        </ul>
                                    </li>

                                     <li><a href="{{url('homepage-settings')}}" >Store Settings</a></li>
                                     @if($role=="superadmin")
                                     <li><a href="{{url('superadmin-settings')}}" >Super Admin Restriction</a></li>
                                     @endif
                                </ul>
                            </li>
                         

                            <!-- <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-book"></i><span> Corporate Pages </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('addprivacy')}}">Privacy Policy</a></li>
                                    <li><a href="{{url('addreturn')}}">Return Policy</a></li>
                                    <li><a href="{{url('addshippment')}}">Shippment Policy</a></li>

                                </ul>
                            </li> -->

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-truck"></i><span> Shippings </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <!-- <li><a href="{{url('shipping')}}">Area Based </a></li>
                                    <li><a href="{{url('edit-products')}}">Edit Product</a></li>  -->
                                   <li><a href="{{url('weightshipping')}}">Area & Weight Based </a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{url('products')}}" class="waves-effect"><i class="ti-info-alt"></i><span>Supplier Products</span></a>
                              
                            </li>

                            <li class="has_sub">
                              <a href="javascript:void(0);" class="waves-effect">
                                <i class="ti-layout-media-center-alt"></i><span> Portfolio </span><span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                  <li><a href="{{url('list-portfolio-category')}}">Category </a></li>
                                  <li><a href="{{url('list-portfolio-subcategory')}}">Subcategory </a></li>
                                  <li><a href="{{url('list-divisions')}}">Divisions </a></li>
                                  <li><a href="{{url('list-portfolio')}}">Portfolio </a></li>
                                </ul>
                            </li>

                             <!-- <li >
                                <a href="{{url('storecontactus')}}" class="waves-effect"><i class="ti-location-pin"></i><span>Contact us </span></a>
                              
                            </li>-->

                            <!--  <li>
                                <a href="{{url('storeaboutus')}}" class="waves-effect"><i class="ti-info-alt"></i><span>About us</span></a>
                              
                            </li> -->

                            @if($role=="superadmin")
                             <li>
                                <a href="{{url('adminusers')}}" class="waves-effect"><i class="ti-lock"></i><span>Users
                                </span></a>
                            </li> 
                            @endif
                            
                            @php 
                               $rewardsettings=\App\Models\Product_setting::where('id','1')->first();
                            @endphp

                            @if($role!="superadmin")
                            
                            @if($rewardsettings['reward']=="on")
                            <li>
                                <a href="{{url('reward')}}" class="waves-effect">
                                    <i class="ti-lock"></i><span>Reward</span>
                                </a>
                            </li>
                            @endif
                          @endif
                            <!-- <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-pencil-alt"></i><span>Store Settings </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('add-products')}}">Home Page</a></li>
                                   
                            
                           <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-bar-chart"></i><span class="label label-pink pull-right">11</span><span> Charts </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="chart-flot.html">Flot Chart</a></li>
                                    <li><a href="chart-morris.html">Morris Chart</a></li>
                                    <li><a href="chart-chartjs.html">Chartjs</a></li>
                                    <li><a href="chart-peity.html">Peity Charts</a></li>
                                    <li><a href="chart-chartist.html">Chartist Charts</a></li>
                                    <li><a href="chart-c3.html">C3 Charts</a></li>
                                    <li><a href="chart-nvd3.html"> Nvd3 Charts</a></li>
                                    <li><a href="chart-sparkline.html">Sparkline charts</a></li>
                                    <li><a href="chart-radial.html">Radial charts</a></li>
                                    <li><a href="chart-other.html">Other Chart</a></li>
                                    <li><a href="chart-ricksaw.html">Ricksaw Chart</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-location-pin"></i><span> Maps </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="map-google.html"> Google Map</a></li>
                                    <li><a href="map-vector.html"> Vector Map</a></li>
                                </ul>
                            </li>

                            <li class="text-muted menu-title">More</li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-files"></i><span> Pages </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="page-starter.html">Starter Page</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-login-v2.html">Login v2</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-register-v2.html">Register v2</a></li>
                                    <li><a href="page-signup-signin.html">Signin - Signup</a></li>
                                    <li><a href="page-recoverpw.html">Recover Password</a></li>
                                    <li><a href="page-lock-screen.html">Lock Screen</a></li>
                                    <li><a href="page-400.html">Error 400</a></li>
                                    <li><a href="page-403.html">Error 403</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                    <li><a href="page-404_alt.html">Error 404-alt</a></li>
                                    <li><a href="page-500.html">Error 500</a></li>
                                    <li><a href="page-503.html">Error 503</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-gift"></i><span> Extras </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="extra-profile.html">Profile</a></li>
                                    <li><a href="extra-timeline.html">Timeline</a></li>
                                    <li><a href="extra-sitemap.html">Site map</a></li>
                                    <li><a href="extra-invoice.html">Invoice</a></li>
                                    <li><a href="extra-email-template.html">Email template</a></li>
                                    <li><a href="extra-maintenance.html">Maintenance</a></li>
                                    <li><a href="extra-coming-soon.html">Coming-soon</a></li>
                                    <li><a href="extra-faq.html">FAQ</a></li>
                                    <li><a href="extra-search-result.html">Search result</a></li>
                                    <li><a href="extra-gallery.html">Gallery</a></li>
                                    <li><a href="extra-pricing.html">Pricing</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-crown"></i><span class="label label-success pull-right">3</span><span> Apps </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="apps-calendar.html"> Calendar</a></li>
                                    <li><a href="apps-contact.html"> Contact</a></li>
                                    <li><a href="apps-taskboard.html"> Taskboard</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-email"></i><span> Email </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="email-inbox.html"> Inbox</a></li>
                                    <li><a href="email-read.html"> Read Mail</a></li>
                                    <li><a href="email-compose.html"> Compose Mail</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-widget"></i><span> Layouts </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="layout-leftbar_2.html"> Leftbar with User</a></li>
                                    <li><a href="layout-menu-collapsed.html"> Menu Collapsed</a></li>
                                    <li><a href="layout-menu-small.html"> Small Menu</a></li>
                                    <li><a href="layout-header_2.html"> Header style</a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-share"></i><span>Multi Level </span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="has_sub">
                                        <a href="javascript:void(0);" class="waves-effect"><span>Menu Level 1.1</span>  <span class="menu-arrow"></span></a>
                                        <ul style="">
                                            <li><a href="javascript:void(0);"><span>Menu Level 2.1</span></a></li>
                                            <li><a href="javascript:void(0);"><span>Menu Level 2.2</span></a></li>
                                            <li><a href="javascript:void(0);"><span>Menu Level 2.3</span></a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><span>Menu Level 1.2</span></a>
                                    </li>
                                </ul>
                            </li>

                            <li class="text-muted menu-title">Extra</li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Crm </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="crm-dashboard.html"> Dashboard </a></li>
                                    <li><a href="crm-contact.html"> Contacts </a></li>
                                    <li><a href="crm-opportunities.html"> Opportunities </a></li>
                                    <li><a href="crm-leads.html"> Leads </a></li>
                                    <li><a href="crm-customers.html"> Customers </a></li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i><span class="label label-warning pull-right">6</span><span> eCommerce </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="ecommerce-dashboard.html"> Dashboard</a></li>
                                    <li><a href="ecommerce-products.html"> Products</a></li>
                                    <li><a href="ecommerce-product-detail.html"> Product Detail</a></li>
                                    <li><a href="ecommerce-product-edit.html"> Product Edit</a></li>
                                    <li><a href="ecommerce-orders.html"> Orders</a></li>
                                    <li><a href="ecommerce-sellers.html"> Sellers</a></li>
                                </ul>
                            </li> -->

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>


            <!-- Left Sidebar End -->

        <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"></h4> 
                    </div>
                    <div class="modal-body">
                        <div id="upload-demo" class="center-block"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
                    </div>
                </div>
            </div>
        </div>

            </div>
            @yield('content')
        

                <footer class="footer text-right">

                    @php
                    $year = date("Y"); 

                    @endphp
                    © {{$year}} {{$settings->site_name}}.  All rights reserved. Developed By {{$settings->develop_company}} © {{$settings->cms_version}}
                </footer>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <div class="side-bar right-bar nicescroll">
                <h4 class="text-center">Chat</h4>
                <div class="contact-list nicescroll">
                    <ul class="list-group contacts-list">
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-1.jpg" alt="">
                                </div>
                                <span class="name">Chadengle</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <span class="name">Tomaslau</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <span class="name">Stillnotdavid</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-4.jpg" alt="">
                                </div>
                                <span class="name">Kurafire</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-5.jpg" alt="">
                                </div>
                                <span class="name">Shahedk</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-6.jpg" alt="">
                                </div>
                                <span class="name">Adhamdannaway</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-7.jpg" alt="">
                                </div>
                                <span class="name">Ok</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-8.jpg" alt="">
                                </div>
                                <span class="name">Arashasghari</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-9.jpg" alt="">
                                </div>
                                <span class="name">Joshaustin</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-10.jpg" alt="">
                                </div>
                                <span class="name">Sortino</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->



        <script>
            var resizefunc = [];
        </script>

         <!-- jQuery  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/detect.js"></script>
        <script src="/assets/js/fastclick.js"></script>
        <script src="/assets/js/jquery.slimscroll.js"></script>
        <script src="/assets/js/jquery.blockUI.js"></script>
        <script src="/assets/js/waves.js"></script>
        <script src="/assets/js/wow.min.js"></script>
        <script src="/assets/js/jquery.nicescroll.js"></script>
        <script src="/assets/js/jquery.scrollTo.min.js"></script>

        <script src="/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
        <script src="/assets/plugins/switchery/js/switchery.min.js"></script>
        <script type="text/javascript" src="/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="/assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
        <script src="/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
        <script src="/assets/plugins/dropzone/dropzone.js"></script>

       <!--  <script type="text/javascript" src="/assets/plugins/autocomplete/jquery.mockjax.js"></script>
        <script type="text/javascript" src="/assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>
        <script type="text/javascript" src="/assets/plugins/autocomplete/countries.js"></script>
        <script type="text/javascript" src="{{asset('assets/pages/autocomplete.js')}}"></script> -->

        <script type="text/javascript" src="/assets/pages/jquery.form-advanced.init.js"></script>

         <!-- Modal-Effect -->
        <script src="/assets/plugins/custombox/js/custombox.min.js"></script>
        <script src="/assets/plugins/custombox/js/legacy.min.js"></script>

              <!-- Pickers -->
      <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
      <script src="/assets/plugins/timepicker/bootstrap-timepicker.js"></script>
      <script src="/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
      <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
      <script src="/assets/plugins/clockpicker/js/bootstrap-clockpicker.min.js"></script>
      <script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
      <script src="/assets/pages/jquery.form-pickers.init.js"></script>

        <script src="/assets/js/jquery.core.js"></script>
        <script src="/assets/js/jquery.app.js"></script>
        <script src="/assets/js/image-uploader.min.js"></script>
        <script src="/assets/plugins/summernote/summernote.min.js"></script>

        <script src="/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>
        <script src="/assets/pages/jquery.sweet-alert.init.js"></script>
        <script src="/assets/plugins/notifyjs/js/notify.js"></script>
        <script src="/assets/plugins/notifications/notify-metro.js"></script>
        <script src="/assets/plugins/croppie/croppie.js"></script>
        <!-- datatables -->


        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.bootstrap.js"></script>

        <script src="/assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="/assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="/assets/plugins/datatables/jszip.min.js"></script>
        <script src="/assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="/assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="/assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="/assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="/assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.colVis.js"></script>
        <script src="/assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>
        <script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <!--Morris Chart-->
        <script src="/assets/plugins/morris/morris.min.js"></script>
        <script src="/assets/plugins/raphael/raphael-min.js"></script>
        <script src="/assets/pages/morris.init.js"></script>
         <!-- Chart JS -->
        <script src="assets/plugins/chart.js/chart.min.js"></script>
        <script src="assets/pages/jquery.chartjs.init.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({keys: true});
        $('#datatable-responsive').DataTable();
        $('#datatable-colvid').DataTable({
            "dom": 'C<"clear">lfrtip',
            "colVis": {
                "buttonText": "Change columns"
            }
        });
        $('#datatable-scroller').DataTable({
            ajax: "assets/plugins/datatables/json/scroller-demo.json",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true
        });
        var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
        var table = $('#datatable-fixed-col').DataTable({
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            }
        });
    });
    TableManageButtons.init();

</script>
<script>


$(".gambar").attr("src", "https://user.gadjian.com/static/images/personnel_boy.png");
                        var $uploadCrop,
                        tempFilename,
                        rawImg,
                        imageId;
                        function readFile(input) {
                            if (input.files && input.files[0]) {
                              var reader = new FileReader();
                                reader.onload = function (e) {
                                    $('.upload-demo').addClass('ready');
                                    $('#cropImagePop').modal('show');
                                    rawImg = e.target.result;
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                            else {
                                swal("Sorry - you're browser doesn't support the FileReader API");
                            }
                        }

                        $uploadCrop = $('#upload-demo').croppie({
                            viewport: {
                                width: 200,
                                height: 200,
                            },
                            enforceBoundary: false,
                            enableExif: true
                        });
                        $('#cropImagePop').on('shown.bs.modal', function(){
                            // alert('Shown pop');
                            $uploadCrop.croppie('bind', {
                                url: rawImg
                            }).then(function(){
                                console.log('jQuery bind complete');
                            });
                        });

                        $('.item-img').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
                        $('#cancelCropBtn').data('id', imageId); readFile(this); });
                        $('#cropImageBtn').on('click', function (ev) {
                            $uploadCrop.croppie('result', {
                                type: 'base64',
                                format: 'jpg',
                                backgroundColor:'#fff',
                                size: {width: 600, height: 600}
                            }).then(function (resp) {
                                $('#item-img-output').attr('src', resp);
                                $('#thumbnailval').val(resp);
                                $('#cropImagePop').modal('hide');
                            });
                        });
               
</script>

        <script type="text/javascript">
           
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                $(".knob").knob();

           
        </script>

<script >
@if(Session::has('error'))

 $.Notification.notify('error','top right', 'Error ', "{{ Session::get('error') }}");

@endif  


@if(Session::has('success'))

 $.Notification.notify('success','top right', 'Success ', "{{ Session::get('success') }}");

@endif  

</script>
<script>
function deletebrand_thumbnail($id)

{
       
    $.ajax({
            type:"POST",
            url:"{{ route('delete_brand_thumbnail') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#thumbdiv").load(location.href + " #thumbdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
                }
                
            } 
    });
}

    function deletebrand_banner($id)
    {
        
     $.ajax({
            type:"POST",
            url:"{{ route('delete_brand_banner') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#bannerdiv").load(location.href + " #bannerdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
               
                }
                
            } 
        });
    }


function deleteparentcat_thumbnail($id)

{
       
    $.ajax({
            type:"POST",
            url:"{{ route('delete_parentcat_thumbnail') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#parentthumbdiv").load(location.href + " #parentthumbdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
                }
                
            } 
    });
}


function deleteparentcat_banner($id)
    {
        
     $.ajax({
            type:"POST",
            url:"{{ route('delete_parentcat_banner') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#parentbannerdiv").load(location.href + " #parentbannerdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
               
                }
                
            } 
        });
    }

    function deletecategory_thumbnail($id)

{
       
    $.ajax({
            type:"POST",
            url:"{{ route('delete_category_thumbnail') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#categorythumbdiv").load(location.href + " #categorythumbdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
                }
                
            } 
    });
}


function deletecategory_banner($id)
    {
        
     $.ajax({
            type:"POST",
            url:"{{ route('delete_category_banner') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#categorybannerdiv").load(location.href + " #categorybannerdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
               
                }
                
            } 
        });
    }

   

    function deletesubcategory_thumbnail($id)

{
       
    $.ajax({
            type:"POST",
            url:"{{ route('delete_subcategory_thumbnail') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#subcategorythumbdiv").load(location.href + " #subcategorythumbdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
                }
                
            } 
    });
}


function deletesubcategory_banner($id)
    {
        
     $.ajax({
            type:"POST",
            url:"{{ route('delete_subcategory_banner') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#subcategorybannerdiv").load(location.href + " #subcategorybannerdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
               
                }
                
            } 
        });
    }


function deletechildsubcategory_thumbnail($id)

{
       
    $.ajax({
            type:"POST",
            url:"{{ route('delete_childsubcategory_thumbnail') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#childsubcategorythumbdiv").load(location.href + " #childsubcategorythumbdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
                }
                
            } 
    });
}


function deletechildsubcategory_banner($id)
    {
        
     $.ajax({
            type:"POST",
            url:"{{ route('delete_childsubcategory_banner') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                console.log(data);
                if(data.status=="success")
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                    $("#childsubcategorybannerdiv").load(location.href + " #childsubcategorybannerdiv");
                }
                else
                {
                    $.Notification.notify('success','top right', 'Success ', "Image Not Delete !");
               
                }
                
            } 
        });
    }
</script>
<script>
    function deleteimage1($id)
    {
       
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage1') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }

    function deleteimage2($id)
    {
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage2') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }

    function deleteimage3($id)
    {
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage3') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }

    function deleteimage4($id)
    {
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage4') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }

    function deleteimage5($id)
    {
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage5') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }



    function deleteimage6($id)
    {
     $.ajax({
            type:"POST",
            url:"{{ route('deleteimage6') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id":$id
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
    }


   function delete_sku_image($str)
    {
       var price=$("#price").val();
       var sku=$("#sku").val();
       var qty=$("#qty").val();
       var img=$("#myFile").val();

       $.ajax({
            type:"POST",
            url:"{{ route('delete_sku_image') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","price":price,"sku":sku,"qty":qty,"img":img
            },
            success:function (data)
            {
                $.Notification.notify('success','top right', 'Success ', "Image Delete Successfully !");
                location.reload();
            } 
        });
       

    }
</script>

        @stack('custom-scripts')


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