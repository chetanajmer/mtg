<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin:  *');
//header('Access-Control-Allow-Credentials:  false');
header('Access-Control-Allow-Methods:  *');
header('Access-Control-Allow-Headers: *');


use App\Http\Controllers\CropImageController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CommonController;
use App\Http\Controllers\Backend\SubcategoryController;
use App\Http\Controllers\Backend\ChildsubcategoryController;
use App\Http\Controllers\Backend\ParentcategoryController;
use App\Http\Controllers\Backend\SubscriberController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\ShippingController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategorybannerController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\Backend\BrandingOptionController;
use App\Http\Controllers\Backend\OccasionController;
use App\Http\Controllers\Backend\PreferenceController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\RewardController;
use App\Http\Controllers\Backend\FilterController;
use App\Http\Controllers\GeneralSettings;
use App\Http\Controllers\Backend\HomepagesettingController;
use App\Http\Controllers\Backend\ApiController;
use App\Http\Controllers\Backend\MiscController;
use App\Http\Controllers\Backend\AnalyticsController;
use App\Http\Controllers\Backend\FastexcelController;
use App\Http\Controllers\Backend\PaytabController;
use App\Http\Controllers\Backend\B2BenquiryController;
use App\Http\Controllers\Backend\Instock_notifierController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\FlipbookController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\PortfolioController;

/*Store*/
use App\Http\Controllers\Frontend\HomepageController;
use App\Http\Controllers\Frontend\Storeproductcontroller;
use App\Http\Controllers\Frontend\UsersController;
use App\Http\Controllers\Frontend\StoreCategoryController;
use App\Http\Controllers\Frontend\StoreBrandController;
use App\Http\Controllers\Frontend\FiltersController;


header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Credentials:  true');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE, PATCH');
header('Access-Control-Allow-Headers:  X-Requested-With,Content-Type, X-Auth-Token, Origin, Authorization, Accept,country,lang');

/*
/*


|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/ea-login', function () {
    return view('home');
});
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/
/*Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/product','product/product');
Route::view('/crop','product/crop');*/

Route::any('500', [HomepageController::class, 'servererror'])->name('servererror');
Route::any('404', [HomepageController::class, 'fileerror'])->name('fileerror');

Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    return "Cache is cleared";
});


Route::any('crop-image-upload', [CropImageController::class, 'uploadCropImage']);


// Brands
Route::any('add-brands', [BrandController::class, 'index']);
Route::any('edit-brands/{id}', [BrandController::class, 'edit']);
Route::post('addbrand', [BrandController::class, 'add'])->name('addbrand');
Route::any('findbrand', [BrandController::class, 'findbrand']);
Route::any('updatebrand', [BrandController::class, 'updatebrand'])->name('updatebrand');
Route::any('deletebrand/{id}', [BrandController::class, 'deletebrand']);
Route::any('listbrand', [BrandController::class, 'list']);
Route::any('brandfeatured', [BrandController::class, 'brandfeatured'])->name('brandfeatured');
Route::any('brandactive', [BrandController::class, 'brandactive'])->name('brandactive');
Route::any('delete_brand_thumbnail', [BrandController::class, 'delete_brand_thumbnail'])->name('delete_brand_thumbnail');
Route::any('delete_brand_banner', [BrandController::class, 'delete_brand_banner'])->name('delete_brand_banner');

// Parent Category
Route::any('add-parentcategories', [ParentcategoryController::class, 'index']);
Route::any('edit-parentcategories/{id}', [ParentcategoryController::class, 'edit']);
Route::post('addparentcategory', [ParentcategoryController::class, 'addparentcat'])->name('addparentcat');
Route::any('findparentcat', [ParentcategoryController::class, 'findparentcat']);
Route::any('updateparentcat', [ParentcategoryController::class, 'updateparentcat'])->name('updateparentcat');
Route::any('deleteparentcategory/{id}', [ParentcategoryController::class, 'deleteparentcat']);
Route::any('listparentcategory', [ParentcategoryController::class, 'list']);
Route::any('catparentfeatured', [ParentcategoryController::class, 'catparentfeatured'])->name('catparentfeatured');
Route::any('catparentactive', [ParentcategoryController::class, 'catparentactive'])->name('catparentactive');
Route::any('delete_parentcat_thumbnail', [ParentcategoryController::class, 'delete_parentcat_thumbnail'])->name('delete_parentcat_thumbnail');
Route::any('delete_parentcat_banner', [ParentcategoryController::class, 'delete_parentcat_banner'])->name('delete_parentcat_banner');
Route::any('getparentcategories', [ParentcategoryController::class, 'getparentcategories'])->name('getparentcategories');

// Category
Route::any('add-categories', [CategoryController::class, 'index']);
Route::any('edit-categories/{id}', [CategoryController::class, 'edit']);
Route::post('addcategory', [CategoryController::class, 'add'])->name('addcat');
Route::any('findcat', [CategoryController::class, 'findcat']);
Route::any('updatecat', [CategoryController::class, 'updatecat'])->name('updatecat');
Route::any('deletecategory/{id}', [CategoryController::class, 'deletecat']);
Route::any('listcategory', [CategoryController::class, 'list']);
Route::any('catfeatured', [CategoryController::class, 'catfeatured'])->name('catfeatured');
Route::any('catactive', [CategoryController::class, 'catactive'])->name('catactive');
Route::any('delete_category_thumbnail', [CategoryController::class, 'delete_category_thumbnail'])->name('delete_category_thumbnail');
Route::any('delete_category_banner', [CategoryController::class, 'delete_category_banner'])->name('delete_category_banner');
Route::any('transfer_category', [CategoryController::class, 'transfer_category'])->name('transfer_category');
Route::any('dotransfercategory', [CategoryController::class, 'dotransfercategory'])->name('dotransfercategory');
Route::any('dotransfersubcategory', [CategoryController::class, 'dotransfersubcategory'])->name('dotransfersubcategory');

// Subcategory
Route::any('add-subcategories', [SubcategoryController::class, 'index']);
Route::any('edit-subcategories/{slug}', [SubcategoryController::class, 'edit']);
Route::post('addsubcategory', [SubcategoryController::class, 'add'])->name('addsubcat');
Route::any('findsubcat', [SubcategoryController::class, 'findsubcat'])->name('findsubcat');
Route::any('updatesubcat', [SubcategoryController::class, 'updatecat'])->name('updatesubcat');;
Route::any('deletesubcat/{id}', [SubcategoryController::class, 'deletecat']);
Route::any('listsubcat', [SubcategoryController::class, 'list']);
Route::any('findsubcategory', [SubcategoryController::class, 'findsubcategory'])->name('findsubcategory');
Route::any('findsubcat_filter', [SubcategoryController::class, 'findsubcat_filter'])->name('findsubcat_filter');
Route::any('deletefilter', [SubcategoryController::class, 'deletefilter'])->name('deletefilter');

Route::any('subcatactive', [SubcategoryController::class, 'subcatactive'])->name('subcatactive');
Route::any('delete_subcategory_thumbnail', [SubcategoryController::class, 'delete_subcategory_thumbnail'])->name('delete_subcategory_thumbnail');
Route::any('delete_subcategory_banner', [SubcategoryController::class, 'delete_subcategory_banner'])->name('delete_subcategory_banner');


// Child subcategory
Route::any('add-childsubcategories', [ChildsubcategoryController::class, 'index']);
Route::any('edit-childsubcategories/{slug}', [ChildsubcategoryController::class, 'edit']);
Route::post('addchildsubcategory', [ChildsubcategoryController::class, 'add'])->name('addchildsubcat');
Route::any('updatechildsubcat', [ChildsubcategoryController::class, 'updatechildsubcat'])->name('updatechildsubcat');;
Route::any('deletechildsubcat/{id}', [ChildsubcategoryController::class, 'deletecat']);
Route::any('listchildsubcat', [ChildsubcategoryController::class, 'list']);
Route::any('childsubcatactive', [ChildsubcategoryController::class, 'childsubcatactive'])->name('childsubcatactive');
Route::any('delete_childsubcategory_thumbnail', [ChildsubcategoryController::class, 'delete_childsubcategory_thumbnail'])->name('delete_childsubcategory_thumbnail');
Route::any('delete_childsubcategory_banner', [ChildsubcategoryController::class, 'delete_childsubcategory_banner'])->name('delete_childsubcategory_banner');


/*Banners*/
Route::any('banners', [CategorybannerController::class, 'banners']);
Route::any('updatebanner', [CategorybannerController::class, 'updatebanner'])->name('updatebanner');
Route::any('small_banners_section1',[CategorybannerController::class,'small_banners_section1']);
Route::any('updatesmall_banners_section1', [CategorybannerController::class, 'updatesmall_banners_section1'])->name('updatesmall_banners_section1');
Route::any('small_banners_section2',[CategorybannerController::class,'small_banners_section2']);
Route::any('updatesmall_banners_section2', [CategorybannerController::class, 'updatesmall_banners_section2'])->name('updatesmall_banners_section2');
Route::any('small_banners_section3',[CategorybannerController::class,'small_banners_section3']);
Route::any('updatesmall_banners_section3', [CategorybannerController::class, 'updatesmall_banners_section3'])->name('updatesmall_banners_section3');









/*General Settings*/

Route::any('cms-settings', [GeneralSettings::class, 'cms']);
Route::any('update-cms-settings', [GeneralSettings::class, 'updatecms'])->name('updatecms');

Route::any('seo-settings', [GeneralSettings::class, 'seosettings'])->name('seosettings');
Route::any('updateseo-settings', [GeneralSettings::class, 'updateseosettings'])->name('updateseosettings');


Route::any('header-settings', [GeneralSettings::class, 'header'])->name('header');
Route::any('updateheader-settings', [GeneralSettings::class, 'updateheader'])->name('updateheader');


Route::any('footerlevel1', [GeneralSettings::class, 'footerlevel1'])->name('footerlevel1');
Route::any('updatefooterlevel1', [GeneralSettings::class, 'updatefooterlevel1'])->name('updatefooterlevel1');


Route::any('footer-section1', [GeneralSettings::class, 'fsection1'])->name('fsection1');
Route::any('footer-updatesection1', [GeneralSettings::class, 'updatesection1'])->name('updatesection1');

Route::any('footer-section2', [GeneralSettings::class, 'fsection2'])->name('fsection2');
Route::any('footer-updatesection2', [GeneralSettings::class, 'updatesection2'])->name('updatesection2');

Route::any('footer-section3', [GeneralSettings::class, 'fsection3'])->name('fsection3');
Route::any('footer-updatesection3', [GeneralSettings::class, 'updatesection3'])->name('updatesection3');


Route::any('homepage-settings', [GeneralSettings::class, 'homepage'])->name('homepage');
Route::any('homepage-update', [GeneralSettings::class, 'homepageupdate'])->name('homepageupdate');


Route::any('superadmin-settings', [GeneralSettings::class, 'superadmin'])->name('superadmin');
Route::any('superadmin-update', [GeneralSettings::class, 'updatesuperadmin'])->name('updatesuperadmin');



/*SMTP*/
Route::any('env_key_update', [GeneralSettings::class, 'env_key_update'])->name('env_key_update');



/*variants*/

Route::any('variants', [GeneralSettings::class, 'variants']);
Route::any('add-variants', [GeneralSettings::class, 'addvariants'])->name('addvariants');



/*corporate pages*/

Route::any('addprivacy', [GeneralSettings::class, 'addprivacy']);
Route::any('updateprivacy', [GeneralSettings::class, 'updateprivacy'])->name('updateprivacy');


Route::any('addreturn', [GeneralSettings::class, 'addreturn']);
Route::any('updatereturn', [GeneralSettings::class, 'updatereturn'])->name('updatereturn');


Route::any('addshippment', [GeneralSettings::class, 'addshippment']);
Route::any('updateshippment', [GeneralSettings::class, 'updateshippment'])->name('updateshippment');


// Route::any('extract', [GeneralSettings::class, 'extract'])->name('extract');




/*Products Controller*/
Route::any('add-products', [ProductController::class, 'index']);
Route::any('addproducts', [ProductController::class, 'add'])->name('addproduct');
Route::any('edit-products/{slug}/{paginationid}', [ProductController::class, 'editproduct'])->name('editproduct');
Route::any('updateproduct', [ProductController::class, 'updateproduct'])->name('updateproduct');
Route::any('deletemyproduct', [ProductController::class, 'deletemyproduct'])->name('deletemyproduct');
Route::any('listproduct', [ProductController::class, 'list']);
Route::any('deleteimage1', [ProductController::class, 'deleteimage1'])->name('deleteimage1');
Route::any('deleteimage2', [ProductController::class, 'deleteimage2'])->name('deleteimage2');
Route::any('deleteimage3', [ProductController::class, 'deleteimage3'])->name('deleteimage3');
Route::any('deleteimage4', [ProductController::class, 'deleteimage4'])->name('deleteimage4');
Route::any('deleteimage5', [ProductController::class, 'deleteimage5'])->name('deleteimage5');
Route::any('deleteimage6', [ProductController::class, 'deleteimage6'])->name('deleteimage6');
Route::any('deletebulkimage', [ProductController::class, 'deletebulkimage'])->name('deletebulkimage');
Route::any('newarrival', [ProductController::class, 'newarrival'])->name('newarrival');
Route::any('sale', [ProductController::class, 'sale'])->name('sale');
Route::any('active', [ProductController::class, 'active'])->name('active');
Route::any('add_image', [ProductController::class, 'add_image'])->name('add_image');
Route::any('addimage', [ProductController::class, 'addimage'])->name('addimage');
Route::any('edit_image/{modelno}', [ProductController::class, 'edit_image'])->name('edit_image');
Route::any('editimage', [ProductController::class, 'editimage'])->name('editimage');
Route::any('add_product', [ProductController::class, 'add_products'])->name('add_products');
Route::any('single_product_add', [ProductController::class, 'single_product_add'])->name('single_product_add');
Route::any('sku_combination', [ProductController::class, 'sku_combination'])->name('sku_combination');
Route::any('sku_combination_edit', [ProductController::class, 'sku_combination_edit'])->name('sku_combination_edit');
Route::any('delete_sku_image', [ProductController::class, 'delete_sku_image'])->name('delete_sku_image');
Route::any('test', [ProductController::class, 'test']);
Route::any('dotest', [ProductController::class, 'dotest'])->name('dotest');
Route::any('findcategoryforproduct', [ProductController::class, 'findcategoryforproduct'])->name('findcategoryforproduct');
Route::any('findsubcategoryforproduct', [ProductController::class, 'findsubcategoryforproduct'])->name('findsubcategoryforproduct');
Route::any('findchildsubcategoryforproduct', [ProductController::class, 'findchildsubcategoryforproduct'])->name('findchildsubcategoryforproduct');
Route::any('most_selling', [ProductController::class, 'most_selling'])->name('most_selling');
Route::any('featured', [ProductController::class, 'featured'])->name('featured');
Route::any('delete_installation', [ProductController::class, 'delete_installation'])->name('delete_installation');
Route::any('appliances', [ProductController::class, 'appliances'])->name('appliances');
Route::any('accessories', [ProductController::class, 'accessories'])->name('accessories');
Route::any('delete_variation_image', [ProductController::class, 'delete_variation_image'])->name('delete_variation_image');
Route::any('products', [ProductController::class, 'products'])->name('products');


/*Sliders*/
Route::any('show-slider', [SliderController::class, 'showslider']);
Route::any('add-slider', [SliderController::class, 'addslider'])->name('addslider'); 
Route::any('list-slider', [SliderController::class, 'listslider']);
Route::any('edit-slider/{slug}', [SliderController::class, 'edit']);
Route::any('updateslider', [SliderController::class, 'updateslider'])->name('updateslider');
Route::any('deleteslider/{slug}', [SliderController::class, 'deleteslider']);


/*Subscribers*/

Route::any('subscribers', [SubscriberController::class, 'subscribers']);
Route::any('deletesubscribers/{slug}', [SubscriberController::class, 'deletesubscribers']);


/*shipping */

Route::any('shipping', [ShippingController::class, 'shipping']);
Route::any('add-shipping', [ShippingController::class, 'add'])->name('addshipping');
Route::any('find-shipping', [ShippingController::class, 'find'])->name('findshipping');
Route::any('update-shipping', [ShippingController::class, 'update'])->name('updateshipping');
Route::any('delete-shipping', [ShippingController::class, 'delete'])->name('deleteshipping');

Route::any('storeshipping', [ShippingController::class, 'storeshipping'])->name('storeshipping');
	

/*Weightshipping */

Route::any('weightshipping', [ShippingController::class, 'weightshipping']);
Route::any('add-weightshipping', [ShippingController::class, 'weightadd'])->name('addweightshipping');
Route::any('find-weightshipping', [ShippingController::class, 'weightfind'])->name('findweightshipping');
Route::any('update-weightshipping', [ShippingController::class, 'weightupdate'])->name('updateweightshipping');
Route::any('delete-weightshipping', [ShippingController::class, 'weightdelete'])->name('deleteweightshipping');

Route::any('storeweightshipping', [ShippingController::class, 'storeweightshipping'])->name('storeweightshipping');

/*Colors*/
Route::any('color', [ColorController::class, 'color']);
Route::any('add-color', [ColorController::class, 'add'])->name('addcolor');
Route::any('find-color', [ColorController::class, 'find'])->name('findcolor');
Route::any('update-color', [ColorController::class, 'update'])->name('updatecolor');
Route::any('delete-color', [ColorController::class, 'delete'])->name('deletecolor');

/*Branding Options*/
Route::any('branding-options', [BrandingOptionController::class, 'index']);
Route::any('add-branding-option', [BrandingOptionController::class, 'add'])->name('addbrandingoption');
Route::any('find-branding-option', [BrandingOptionController::class, 'find'])->name('findbrandingoption');
Route::any('update-branding-option', [BrandingOptionController::class, 'update'])->name('updatebrandingoption');
Route::any('delete-branding-option', [BrandingOptionController::class, 'delete'])->name('deletebrandingoption');

/*Occasion Tags*/
Route::any('occasions', [OccasionController::class, 'index']);
Route::any('add-occasion', [OccasionController::class, 'add'])->name('addoccasion');
Route::any('find-occasion', [OccasionController::class, 'find'])->name('findoccasion');
Route::any('update-occasion', [OccasionController::class, 'update'])->name('updateoccasion');
Route::any('delete-occasion', [OccasionController::class, 'delete'])->name('deleteoccasion');

/*Preferences*/
Route::any('preferences', [PreferenceController::class, 'index']);
Route::any('add-preference', [PreferenceController::class, 'add'])->name('addpreference');
Route::any('find-preference', [PreferenceController::class, 'find'])->name('findpreference');
Route::any('update-preference', [PreferenceController::class, 'update'])->name('updatepreference');
Route::any('delete-preference', [PreferenceController::class, 'delete'])->name('deletepreference');


/*Variation */
Route::any('variation', [VariationController::class, 'variation']);
Route::any('add-variation', [VariationController::class, 'add'])->name('addvariation');
Route::any('find-variation', [VariationController::class, 'find'])->name('findvariation');
Route::any('update-variation', [VariationController::class, 'update'])->name('updatevariation');
Route::any('delete-variation', [VariationController::class, 'delete'])->name('deletevariation');



/*Admins */

Route::any('adminusers', [AdminController::class, 'adminusers']);
Route::any('add-adminuser', [AdminController::class, 'add'])->name('addadminuser');
Route::any('find-adminuser', [AdminController::class, 'find'])->name('findadminuser');
Route::any('update-adminuser', [AdminController::class, 'update'])->name('updateadminuser');
Route::any('delete-adminuser', [AdminController::class, 'delete'])->name('deleteadminuser');




//Route::any('deletesubscribers/{slug}', [SubscriberController::class, 'deletesub

/*Referral */
Route::any('reward', [RewardController::class, 'index']);
Route::any('addreward', [RewardController::class, 'addreward'])->name('addreward');
Route::any('add_reward', [RewardController::class, 'add_reward'])->name('add_reward');
Route::any('edit-reward/{id}', [RewardController::class, 'edit_reward'])->name('edit_reward');
Route::any('update_reward/id', [RewardController::class, 'update_reward'])->name('update_reward');
Route::any('deletereward/id', [RewardController::class, 'deletereward'])->name('deletereward');
Route::any('totalsale_active', [RewardController::class, 'totalsale_active'])->name('totalsale_active');
Route::any('rewardproductstatus', [RewardController::class, 'rewardproductstatus'])->name('rewardproductstatus');
Route::any('rewardtotalsalestatus', [RewardController::class, 'rewardtotalsalestatus'])->name('rewardtotalsalestatus');
Route::any('totalsalestatus', [RewardController::class, 'totalsalestatus'])->name('totalsalestatus');

/* Coupon */
Route::any('add-coupon', [CouponController::class, 'index']);
Route::any('add', [CouponController::class, 'add'])->name('add');
Route::any('list-coupon', [CouponController::class, 'list'])->name('list');
Route::any('edit-coupon/{slug}', [CouponController::class, 'edit'])->name('edit');
Route::any('updatecoupon', [CouponController::class, 'updatecoupon'])->name('updatecoupon');
Route::any('deletecoupon/{slug}', [CouponController::class, 'deletecoupon'])->name('deletecoupon');
Route::any('coupon_code', [CouponController::class, 'coupon_code'])->name('coupon_code');
Route::any('remove_coupon_code', [CouponController::class, 'remove_coupon_code'])->name('remove_coupon_code');
Route::any('getinputvalue', [CouponController::class, 'getinputvalue'])->name('getinputvalue');

/* B2B Enquiry */
Route::any('b2b-enquiry', [B2BenquiryController::class, 'list'])->name('list');

/*Instock Notifier */
Route::any('instock-notifier', [Instock_notifierController::class, 'list'])->name('list');

/* Reports*/
Route::any('date_report', [ReportController::class, 'date_report'])->name('date_report');
Route::any('user_report', [ReportController::class, 'user_report'])->name('user_report');
Route::any('product_report', [ReportController::class, 'product_report'])->name('product_report');

/*Orders */
Route::any('abandoned_order', [OrderController::class, 'abandoned_order'])->name('abandoned_order');
Route::any('abandoned_orderdetails/{id}', [OrderController::class, 'abandoned_orderdetails'])->name('abandoned_orderdetails');
Route::any('send_abandoned_mail', [OrderController::class, 'send_abandoned_mail'])->name('send_abandoned_mail');

/*Flipbook*/
Route::any('flipbook', [FlipbookController::class, 'index']);
Route::any('add_flipbook', [FlipbookController::class, 'add_flipbook'])->name('add_flipbook');
Route::any('doaddflipbook', [FlipbookController::class, 'doaddflipbook'])->name('doaddflipbook');
Route::any('edit_flipbook/{id}', [FlipbookController::class, 'edit_flipbook'])->name('edit_flipbook');
Route::any('doupdateflipbook', [FlipbookController::class, 'doupdateflipbook'])->name('doupdateflipbook');
Route::any('deleteflipbookimage', [FlipbookController::class, 'deleteflipbookimage'])->name('deleteflipbookimage');
Route::any('delete_flipbook/{id}', [FlipbookController::class, 'delete_flipbook'])->name('delete_flipbook');


// Reviews
Route::any('reviews', [ReviewController::class, 'index']);



/*Frontend Homepage*/

Route::any('/', [HomepageController::class, 'index']);
Route::any('product/{slug}', [HomepageController::class, 'product']);
Route::any('product/{product_slug}/{slug}', [HomepageController::class, 'variationproduct'])->name('variationproduct');
/*Route::any('addtocart', [HomepageController::class, 'addtocart'])->name('addtocart'); */
Route::any('variant_price', [HomepageController::class, 'variant_price'])->name('variant_price'); 

Route::any('cart', [HomepageController::class, 'cart']); 
Route::any('incrementquantity', [HomepageController::class, 'incrementquantity'])->name('incrementquantity'); 
Route::any('decrementquantity', [HomepageController::class, 'decrementquantity'])->name('decrementquantity');
Route::any('deleteproduct', [HomepageController::class, 'deleteproduct'])->name('deleteproduct'); 
Route::any('clearcart', [HomepageController::class, 'clearcart'])->name('clearcart');

Route::any('checkout', [HomepageController::class, 'checkout']); 
Route::any('docheckout', [HomepageController::class, 'docheckout'])->name('docheckout');

Route::any('guestcheckout', [HomepageController::class, 'guestcheckout']); 

Route::any('orders', [HomepageController::class, 'orders']); 
Route::any('printinvoice/{slug}', [HomepageController::class, 'printinvoice']); 
Route::any('deleteorder/{slug}', [HomepageController::class, 'deleteorder']); 
Route::any('orderdetails/{id}', [HomepageController::class, 'orderdetails']); 
Route::any('update_orderstatus', [HomepageController::class, 'update_orderstatus'])->name('update_orderstatus');

Route::any('contactus', [HomepageController::class, 'contactus']); 
Route::any('aboutus', [HomepageController::class, 'aboutus']); 


Route::any('addtowishlist', [HomepageController::class, 'addtowishlist'])->name('addtowishlist'); 
Route::any('wishlist', [HomepageController::class, 'wishlist'])->name('wishlist'); 
Route::any('deletewishlist/{id}', [HomepageController::class, 'deletewishlist'])->name('deletewishlist'); 

Route::any('extract1', [HomepageController::class, 'extract1'])->name('extract1');
//Route::any('addsubscriber', [HomepageController::class, 'addsubscriber'])->name('addsubscriber');


Route::any('privacy-policy', [HomepageController::class, 'privacy']);
Route::any('return-policy', [HomepageController::class, 'return']);
Route::any('shippment-policy', [HomepageController::class, 'shippment']);
Route::any('quick-view/{slug}', [HomepageController::class, 'quickview'])->name('quickview');
Route::any('category/quick-view/{slug}', [HomepageController::class, 'quickview'])->name('quickview');
Route::any('product/quick-view/{slug}', [HomepageController::class, 'quickview'])->name('quickview');
Route::any('category/quick-view/{slug}/gridview', [HomepageController::class, 'quickview'])->name('quickview');


/*Users Login*/

Route::any('/store/login', [UsersController::class, 'storelogin']);
Route::any('/store/dologin', [UsersController::class, 'dostorelogin'])->name('dostorelogin'); 
Route::any('/store/dologout', [UsersController::class, 'dostorelogout'])->name('dostorelogout'); 
Route::any('/store/register', [UsersController::class, 'storeregister']);
Route::any('/store/doregister', [UsersController::class, 'dostoreregister'])->name('dostoreregister'); 
Route::any('/store/myaccount', [UsersController::class, 'myaccount']);
Route::any('/store/resetpassword', [UsersController::class, 'resetpassword']);
Route::any('/store/doresetpassword', [UsersController::class, 'doresetpassword'])->name('doresetpassword'); 
Route::any('updateuserdetails', [UsersController::class, 'updateuserdetails'])->name('updateuserdetails');
Route::any('store-user', [UsersController::class, 'storeuser'])->name('storeuser'); 
/*Store Category*/

Route::any('category/{slug}', [StoreCategoryController::class, 'category']);
Route::any('listview/category/{slug}/', [StoreCategoryController::class, 'listview'])->name('listview');
Route::any('subcategory/{slug}', [StoreCategoryController::class, 'subcategory']);
Route::any('listview/subcategory/{slug}', [StoreCategoryController::class, 'subcategorylist'])->name('subcategorylist');

/*Store Brand */
Route::any('brand/{slug}', [StoreBrandController::class, 'brand']);
Route::any('listview/brand/{slug}/', [StoreBrandController::class, 'listview'])->name('listview');
/*Dashboard*/

Route::any('/dashboard', [AdminController::class, 'dashboard']);
Route::any('/admin', [AdminController::class, 'login']);
Route::any('/doadminlogin', [AdminController::class, 'doadminlogin'])->name('doadminlogin');
Route::any('/adminlogout', [AdminController::class, 'adminlogout'])->name('adminlogout'); 
Route::any('search_monthly', [AdminController::class, 'search_monthly'])->name('search_monthly');
Route::any('search_yearly', [AdminController::class, 'search_yearly'])->name('search_yearly');


/*Common*/

Route::any('storeaboutus', [CommonController::class, 'storeaboutus']); 
Route::any('updateaboutus', [CommonController::class, 'updateaboutus'])->name('updateaboutus');

Route::any('storecontactus', [CommonController::class, 'storecontactus']); 
Route::any('updatecontactus', [CommonController::class, 'updatecontactus'])->name('updatecontactus');


/*Filters*/
Route::any('searchbar', [FiltersController::class, 'searchbar'])->name('searchbar');
Route::any('search_bar', [FiltersController::class, 'search_bar'])->name('search_bar');
Route::any('search_bar/{search_val}', [FiltersController::class, 'searching'])->name('searching');
Route::any('attfilter', [FiltersController::class, 'attfilter'])->name('attfilter');
Route::any('contactform', [HomepageController::class, 'contactform'])->name('contactform');

Route::any('deleteproduct_filter', [ProductController::class, 'deleteproduct_filter'])->name('deleteproduct_filter');
Route::any('find_subcat_filter', [FilterController::class, 'find_subcat_filter'])->name('find_subcat_filter');
Route::any('find_cat_filter', [FilterController::class, 'find_cat_filter'])->name('find_cat_filter');
Route::any('category_filter', [HomepageController::class, 'category_filter'])->name('category_filter');
Route::any('sub_category_filter', [HomepageController::class, 'sub_category_filter'])->name('sub_category_filter');
Route::any('brands', [HomepageController::class, 'brands'])->name('brands');

Route::any('getslug', [BrandController::class, 'getslug'])->name('getslug');

// Homepage Category settings
Route::any('category-section', [HomepagesettingController::class, 'category_section'])->name('category_section');
Route::any('categorysettingupdate', [HomepagesettingController::class, 'categorysettingupdate'])->name('categorysettingupdate');
Route::any('tag-section', [HomepagesettingController::class, 'tag_section'])->name('tag_section');
Route::any('tagsettingupdate', [HomepagesettingController::class, 'tagsettingupdate'])->name('tagsettingupdate');
Route::any('content-section', [HomepagesettingController::class, 'content_section'])->name('content_section');
Route::any('contentupdate', [HomepagesettingController::class, 'contentupdate'])->name('contentupdate');

/* Social Login */
Route::get('auth/google', [GeneralSettings::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GeneralSettings::class, 'GoogleCallback']);
Route::get('auth/facebook', [GeneralSettings::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback',[GeneralSettings::class, 'FacebookCallback']);


/*Analytics */
Route::any('visited_page', [AnalyticsController::class, 'visited_page'])->name('visited_page');
Route::any('mostvisitedpage', [AnalyticsController::class, 'mostvisitedpage'])->name('mostvisitedpage');
Route::any('usersbydate', [AnalyticsController::class, 'usersbydate'])->name('usersbydate');
Route::any('usersbycountry', [AnalyticsController::class, 'usersbycountry'])->name('usersbycountry');

/* FastExcel */
Route::any('exportcategory', [FastexcelController::class, 'exportcategory'])->name('exportcategory');
Route::any('importcategory', [FastexcelController::class, 'importcategory'])->name('importcategory');
Route::any('exportsubcategory', [FastexcelController::class, 'exportsubcategory'])->name('exportsubcategory');
Route::any('importsubcategory', [FastexcelController::class, 'importsubcategory'])->name('importsubcategory');
Route::any('exportproduct', [FastexcelController::class, 'exportproduct'])->name('exportproduct');
Route::any('importproduct', [FastexcelController::class, 'importproduct'])->name('importproduct');
Route::any('exportstoreuser', [FastexcelController::class, 'exportstoreuser'])->name('exportstoreuser');

Route::any('exportappliances', [FastexcelController::class, 'exportappliances'])->name('exportappliances');

Route::any('exportaccessories', [FastexcelController::class, 'exportaccessories'])->name('exportaccessories');


Route::any('bulkdeleteproducts', [FastexcelController::class, 'bulkdeleteproducts'])->name('bulkdeleteproducts');

Route::any('dobulkdeleteproducts', [FastexcelController::class, 'dobulkdeleteproducts'])->name('dobulkdeleteproducts');
Route::any('exportb2benquiry', [FastexcelController::class, 'exportb2benquiry'])->name('exportb2benquiry');

/* Paytabs*/
Route::any('paytabs', [PaytabController::class, 'paytabs']);
Route::any('return_url', [PaytabController::class, 'return_url'])->name('return_url');
Route::any('callback', [PaytabController::class, 'callback'])->name('callback');


// Portfolio
Route::any('list-portfolio', [PortfolioController::class, 'list_portfolio'])->name('list_portfolio');
Route::any('add-portfolio', [PortfolioController::class, 'add_portfolio']);
Route::any('do_add_portfolio', [PortfolioController::class, 'do_add_portfolio'])->name('do_add_portfolio');
Route::any('edit-portfolio/{slug}/{paginationid}', [PortfolioController::class, 'edit_portfolio']);
Route::any('do_update_portfolio', [PortfolioController::class, 'do_update_portfolio'])->name('do_update_portfolio');
Route::any('delete_portfolio_bulkimage', [PortfolioController::class, 'delete_portfolio_bulkimage'])->name('delete_portfolio_bulkimage');
Route::any('delete_portfolio_logo', [PortfolioController::class, 'delete_portfolio_logo'])->name('delete_portfolio_logo');
Route::any('delete-portfolio/{slug}/{paginationid}', [PortfolioController::class, 'delete_portfolio'])->name('delete_portfolio');
Route::any('do_active_portfolio', [PortfolioController::class, 'do_active_portfolio'])->name('do_active_portfolio');
Route::any('do_featured_portfolio', [PortfolioController::class, 'do_featured_portfolio'])->name('do_featured_portfolio');

Route::any('add-division', [PortfolioController::class, 'add_division']);
Route::any('do_add_division', [PortfolioController::class, 'do_add_division'])->name('do_add_division');
Route::any('list-divisions', [PortfolioController::class, 'divisions'])->name('divisions');
Route::any('edit-division/{slug}/{paginationid}', [PortfolioController::class, 'edit_division'])->name('edit_division');
Route::any('do_update_division', [PortfolioController::class, 'do_update_division'])->name('do_update_division');
Route::any('delete-division/{slug}/{paginationid}', [PortfolioController::class, 'delete_division'])->name('delete_division');
Route::any('do_active_division', [PortfolioController::class, 'do_active_division'])->name('do_active_division');

Route::any('list-portfolio-category', [PortfolioController::class, 'list_portfolio_category'])->name('list_portfolio_category');
Route::any('add-portfolio-category', [PortfolioController::class, 'add_portfolio_category'])->name('add_portfolio_category');
Route::any('do_add_portfolio_category', [PortfolioController::class, 'do_add_portfolio_category'])->name('do_add_portfolio_category');
Route::any('edit-portfolio-category/{slug}/{paginationid}', [PortfolioController::class, 'edit_portfolio_category'])->name('edit_portfolio_category');
Route::any('do_update_portfolio_category', [PortfolioController::class, 'do_update_portfolio_category'])->name('do_update_portfolio_category');
Route::any('delete-portfolio-category/{slug}/{paginationid}', [PortfolioController::class, 'delete_portfolio_category'])->name('delete_portfolio_category');
Route::any('do_active_portfolio_category', [PortfolioController::class, 'do_active_portfolio_category'])->name('do_active_portfolio_category');

Route::any('list-portfolio-subcategory', [PortfolioController::class, 'list_portfolio_subcategory'])->name('list_portfolio_subcategory');
Route::any('add-portfolio-subcategory', [PortfolioController::class, 'add_portfolio_subcategory'])->name('add_portfolio_subcategory');
Route::any('do_add_portfolio_subcategory', [PortfolioController::class, 'do_add_portfolio_subcategory'])->name('do_add_portfolio_subcategory');
Route::any('edit-portfolio-subcategory/{slug}/{paginationid}', [PortfolioController::class, 'edit_portfolio_subcategory'])->name('edit_portfolio_subcategory');
Route::any('do_update_portfolio_subcategory', [PortfolioController::class, 'do_update_portfolio_subcategory'])->name('do_update_portfolio_subcategory');
Route::any('delete-portfolio-subcategory/{slug}/{paginationid}', [PortfolioController::class, 'delete_portfolio_subcategory'])->name('delete_portfolio_subcategory');
Route::any('do_active_portfolio_subcategory', [PortfolioController::class, 'do_active_portfolio_subcategory'])->name('do_active_portfolio_subcategory');
Route::any('find_subcategory_for_portfolio', [PortfolioController::class, 'find_subcategory_for_portfolio'])->name('find_subcategory_for_portfolio');
Route::any('delete_portfolio_service', [PortfolioController::class, 'delete_portfolio_service'])->name('delete_portfolio_service');
// Order



/*API routes*/

Route::get('getslider', [SliderController::class, 'getslider'])->name('getslider');

Route::get('getbannerlevel1', [ApiController::class, 'getbannerlevel1'])->name('getbannerlevel1');
Route::get('getbannerlevel2', [ApiController::class, 'getbannerlevel2'])->name('getbannerlevel2');
Route::get('getbannerlevel3', [ApiController::class, 'getbannerlevel3'])->name('getbannerlevel3');
Route::get('getbannerlevel4', [ApiController::class, 'getbannerlevel4'])->name('getbannerlevel4');
Route::get('getbannerlevel5', [ApiController::class, 'getbannerlevel5'])->name('getbannerlevel5');
Route::get('getbannerlevel6', [ApiController::class, 'getbannerlevel6'])->name('getbannerlevel6');

Route::get('categoryslider', [ApiController::class, 'categoryslider'])->name('categoryslider');
Route::get('mostselling', [ApiController::class, 'mostselling'])->name('mostselling');

/*Route::get('getbannerlevel2', [ApiController::class, 'getbannerlevel2'])->name('getbannerlevel2');
Route::get('getbannerlevel3', [ApiController::class, 'getbannerlevel3'])->name('getbannerlevel3');
Route::get('getbannerlevel4', [ApiController::class, 'getbannerlevel4'])->name('getbannerlevel4');
Route::get('getbannerlevel5', [ApiController::class, 'getbannerlevel5'])->name('getbannerlevel5');*/



Route::get('productl1', [ApiController::class, 'productl1'])->name('productl1');
Route::get('productl2', [ApiController::class, 'productl2'])->name('productl2');
Route::get('productl3', [ApiController::class, 'productl3'])->name('productl3');
Route::get('productl4', [ApiController::class, 'productl4'])->name('productl4');
Route::get('productl5', [ApiController::class, 'productl5'])->name('productl5');
Route::get('productl6', [ApiController::class, 'productl6'])->name('productl6');
Route::get('productl7', [ApiController::class, 'productl7'])->name('productl7');
Route::get('productl8', [ApiController::class, 'productl8'])->name('productl8');
Route::get('productl9', [ApiController::class, 'productl9'])->name('productl9');

Route::post('storeuser', [ApiController::class, 'storeuser'])->name('storeuser');

Route::post('addtocart', [ApiController::class, 'addtocart'])->name('addtocart');
Route::post('getcatproducts', [ApiController::class, 'getcatproducts'])->name('getcatproducts');
Route::post('getsubcatproducts', [ApiController::class, 'getsubcatproducts'])->name('getsubcatproducts');



Route::post('cartdata', [ApiController::class, 'cartdata'])->name('cartdata');
Route::get('gethomedata', [ApiController::class, 'gethomedata'])->name('gethomedata');

Route::get('getallparentcategories', [ApiController::class, 'getallparentcategories'])->name('getallparentcategories');

Route::get('getallcategories', [ApiController::class, 'getallcategories'])->name('getallcategories');

Route::get('getallsubcategories', [ApiController::class, 'getallsubcategories'])->name('getallsubcategories');

Route::get('getallbrands', [ApiController::class, 'getallbrands'])->name('getallbrands');

Route::post('addsubscriber', [ApiController::class, 'addsubscriber'])->name('addsubscriber');

Route::post('getproductdetails', [ApiController::class, 'getproductdetails'])->name('getproductdetails');
Route::post('getbrandproducts', [ApiController::class, 'getbrandproducts'])->name('getbrandproducts');

Route::post('searchproduct', [ApiController::class, 'searchproduct'])->name('searchproduct');
Route::post('search_or_update', [ApiController::class, 'search_or_update'])->name('search_or_update');

Route::any('paytab_returnurl', [ApiController::class, 'paytab_returnurl'])->name('paytab_returnurl');

Route::post('registeruser', [ApiController::class, 'registeruser'])->name('registeruser');
Route::post('registersocialuser', [ApiController::class, 'registersocialuser'])->name('registersocialuser');

Route::post('loginuser', [ApiController::class, 'loginuser'])->name('loginuser');
Route::post('getuserorderdetails', [ApiController::class, 'getuserorderdetails'])->name('getuserorderdetails');

Route::post('getuserdetails', [ApiController::class, 'getuserdetails'])->name('getuserdetails');

Route::post('updateuserdetails', [ApiController::class, 'updateuserdetails'])->name('updateuserdetails');

Route::post('stocknotify', [ApiController::class, 'stocknotify'])->name('stocknotify');

Route::post('b2benquiry', [ApiController::class, 'b2benquiry'])->name('b2benquiry');

Route::post('addtocompare', [ApiController::class, 'addtocompare'])->name('addtocompare');

Route::post('getcomparelist', [ApiController::class, 'getcomparelist'])->name('getcomparelist');

Route::post('abandancart', [ApiController::class, 'abandancart'])->name('abandancart');

Route::post('enquiry', [ApiController::class, 'enquiry'])->name('enquiry');
Route::post('contact', [ApiController::class, 'contact'])->name('contact');
Route::get('mytest', [ApiController::class, 'mytest'])->name('mytest');

Route::get('headerfilter', [ApiController::class, 'headerfilter'])->name('headerfilter');
Route::get('portfolio', ['page'=>'{page}',ApiController::class, 'portfolio'])->name('portfolio');
Route::get('portfolio-detail', ['slug'=>'{slug}',ApiController::class, 'portfolio_detail'])->name('portfolio_detail');
Route::get('featured_portfolio', [ApiController::class, 'featured_portfolio'])->name('featured_portfolio');
//Route::any('postcontact', [MiscController::class, 'postcontact'])->name('postcontact');

Route::any('midocean', [HomepageController::class, 'midocean'])->name('midocean');
Route::any('extract', [HomepageController::class, 'extract'])->name('extract');
// Route::any('portfolio', [HomepageController::class, 'portfolio'])->name('portfolio');