<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Backend\SupplyCountryController;
use App\Http\Controllers\Backend\FilterSettingController;
use App\Http\Controllers\Backend\HomeLogoController;
use App\Http\Controllers\Backend\PreferenceController;
use App\Http\Controllers\Backend\ExhibitionEventController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\FilterController;
use App\Http\Controllers\Frontend\BrandingAssetController;
use App\Http\Controllers\GeneralSettings;
use App\Http\Controllers\Backend\HomepagesettingController;
use App\Http\Controllers\Backend\ApiController;
use App\Http\Controllers\Backend\AnalyticsController;
use App\Http\Controllers\Backend\FastexcelController;
use App\Http\Controllers\Backend\PaytabController;
use App\Http\Controllers\Backend\B2BenquiryController;
use App\Http\Controllers\Backend\Instock_notifierController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\PlannerLogController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\FlipbookController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\PortfolioController;
use App\Http\Controllers\Backend\OrderTrackingController;
use App\Http\Controllers\Backend\OrderWorkflowController;
use App\Http\Controllers\Backend\MembershipController;
use App\Http\Controllers\Frontend\MembershipPageController;
use App\Http\Controllers\NewBrandController;
use App\Http\Controllers\OrderProcessingController;

/*Store*/
use App\Http\Controllers\Frontend\HomepageController;
use App\Http\Controllers\Frontend\UsersController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CustomOrderController;
use App\Http\Controllers\Frontend\GiftPlannerController;
use App\Http\Controllers\Frontend\CustomerOrderController;
use App\Http\Controllers\Frontend\CustomerReportController;
use App\Http\Controllers\Frontend\CorporateEmployeeController;
use App\Http\Controllers\Frontend\StoreCategoryController;
use App\Http\Controllers\Frontend\StoreBrandController;
use App\Http\Controllers\Frontend\FiltersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Public storefront routes come first. Every admin panel route is inside
| the "admin.auth" group at the bottom of this file.
|
| CORS is handled by config/cors.php, not by header() calls here.
|
*/

Route::any('500', [HomepageController::class, 'servererror'])->name('servererror');
Route::any('404', [HomepageController::class, 'fileerror'])->name('fileerror');


/*Frontend Homepage*/

Route::any('/', [HomepageController::class, 'index']);
Route::get('/search', [HomepageController::class, 'search'])->name('home.search');
Route::get('product/{slug}', [HomepageController::class, 'webProduct'])->name('web.product');
Route::any('product/{product_slug}/{slug}', [HomepageController::class, 'variationproduct'])->name('variationproduct');
Route::any('variant_price', [HomepageController::class, 'variant_price'])->name('variant_price');
Route::get('compare-products', [HomepageController::class, 'compareProducts'])->name('compare.products');

// Compare Products AJAX routes
Route::prefix('api/compare')->group(function () {
    Route::get('search', [App\Http\Controllers\Frontend\CompareController::class, 'searchProducts'])->name('compare.search');
    Route::post('product-details', [App\Http\Controllers\Frontend\CompareController::class, 'getProductDetails'])->name('compare.product-details');
    Route::post('add-to-cart', [App\Http\Controllers\Frontend\CompareController::class, 'addToCart'])->name('compare.add-to-cart');
});

Route::post('compare/save-selection', [App\Http\Controllers\Frontend\CompareController::class, 'saveSelection'])->name('compare.save-selection');

Route::any('incrementquantity', [HomepageController::class, 'incrementquantity'])->name('incrementquantity');
Route::any('decrementquantity', [HomepageController::class, 'decrementquantity'])->name('decrementquantity');
Route::any('deleteproduct', [HomepageController::class, 'deleteproduct'])->name('deleteproduct');
Route::any('clearcart', [HomepageController::class, 'clearcart'])->name('clearcart');

// The old checkout could never submit (docheckout was commented out); use the new cart
Route::redirect('checkout', '/cart');

Route::redirect('guestcheckout', '/cart');

// Logged-in customer's own orders (the admin list is /admin/orders)
Route::any('orders', [HomepageController::class, 'orders']);
Route::get('orders/{orderid}', [CustomerOrderController::class, 'show'])->name('customer.orders.show')->where('orderid', '[0-9]+');
Route::get('orders/{orderid}/invoices/{number}', [CustomerOrderController::class, 'invoice'])->name('customer.orders.invoice')
    ->where(['orderid' => '[0-9]+', 'number' => '[A-Z0-9-]+']);
Route::post('orders/{orderid}/steps/{step}/{action}', [CustomerOrderController::class, 'act'])->name('customer.orders.act')
    ->where(['orderid' => '[0-9]+', 'step' => '[0-9]+', 'action' => '[a-z]+'])->middleware('throttle:order-answer');
// Signed link from order emails: approve / request changes / rate without logging in
Route::match(['get', 'post'], 'order-response/{orderid}/{step}/{round}', [CustomerOrderController::class, 'respond'])->name('customer.orders.respond')
    ->where(['orderid' => '[0-9]+', 'step' => '[0-9]+', 'round' => '[0-9]+'])->middleware('throttle:order-answer');

Route::any('contactus', [HomepageController::class, 'contactus']);
Route::any('aboutus', [HomepageController::class, 'aboutus']);




Route::any('privacy-policy', [HomepageController::class, 'privacy']);
Route::any('return-policy', [HomepageController::class, 'return']);
Route::any('shippment-policy', [HomepageController::class, 'shippment']);


/*Users Login*/

Route::any('/store/login', [UsersController::class, 'storelogin']);
Route::post('/store/dologin', [UsersController::class, 'dostorelogin'])->name('dostorelogin')->middleware('throttle:5,1');
Route::any('/store/dologout', [UsersController::class, 'dostorelogout'])->name('dostorelogout');
Route::any('/store/register', [UsersController::class, 'storeregister']);
Route::post('/store/doregister', [UsersController::class, 'dostoreregister'])->name('dostoreregister')->middleware('throttle:5,1');
Route::any('/store/myaccount', [UsersController::class, 'myaccount']);

// Corporate Portal Authentication Routes
Route::get('/corporate-portal', [UsersController::class, 'corporatePortal'])
    ->name('corporate.portal')
    ->middleware('corporate.auth');

// Customer's own reports and CSV downloads
Route::get('/corporate-reports', [CustomerReportController::class, 'index'])
    ->name('corporate.reports')
    ->middleware('corporate.auth');
Route::get('/corporate-reports/download/{type}', [CustomerReportController::class, 'download'])
    ->name('corporate.reports.download')
    ->where('type', 'orders|spending|approvals')
    ->middleware('corporate.auth');

// Customer's employees (gift recipients) and team logins; roles are enforced by CorporateTeamSession
Route::middleware('corporate.auth')->group(function () {
    Route::get('/corporate-employees', [CorporateEmployeeController::class, 'index'])->name('corporate.employees');
    Route::post('/corporate-employees', [CorporateEmployeeController::class, 'store'])->name('corporate.employees.store');
    Route::put('/corporate-employees/{id}', [CorporateEmployeeController::class, 'update'])->name('corporate.employees.update')->where('id', '[0-9]+');
    Route::delete('/corporate-employees/{id}', [CorporateEmployeeController::class, 'destroy'])->name('corporate.employees.destroy')->where('id', '[0-9]+');
    Route::post('/corporate-employees/import', [CorporateEmployeeController::class, 'import'])->name('corporate.employees.import');
    Route::get('/corporate-employees/export', [CorporateEmployeeController::class, 'export'])->name('corporate.employees.export');
    Route::get('/corporate-employees/template', [CorporateEmployeeController::class, 'template'])->name('corporate.employees.template');
    Route::post('/corporate-employees/team', [CorporateEmployeeController::class, 'invite'])->name('team.invite')->middleware('throttle:20,1');
    Route::post('/corporate-employees/team/{id}/resend', [CorporateEmployeeController::class, 'resend'])->name('team.resend')->where('id', '[0-9]+')->middleware('throttle:20,1');
    Route::post('/corporate-employees/team/{id}/role', [CorporateEmployeeController::class, 'updateRole'])->name('team.role')->where('id', '[0-9]+');
    Route::post('/corporate-employees/team/{id}/remove', [CorporateEmployeeController::class, 'remove'])->name('team.remove')->where('id', '[0-9]+');
});
// Invitation link (no login): the invited person sets their name and password
Route::get('/corporate-team/accept/{token}', [CorporateEmployeeController::class, 'acceptForm'])->name('team.accept')->where('token', '[A-Za-z0-9]+');
Route::post('/corporate-team/accept/{token}', [CorporateEmployeeController::class, 'accept'])->name('team.accept.submit')->where('token', '[A-Za-z0-9]+')->middleware('throttle:10,1');

Route::get('/corporate/login', [UsersController::class, 'corporateLogin'])
    ->name('corporate.login');

Route::post('/corporate/login', [UsersController::class, 'doCorporateLogin'])
    ->name('corporate.login.submit')
    ->middleware('throttle:5,1');

Route::get('/corporate/register', [UsersController::class, 'corporateRegister'])
    ->name('corporate.register');

Route::post('/corporate/register', [UsersController::class, 'doCorporateRegister'])
    ->name('corporate.register.submit')
    ->middleware('throttle:5,1');

Route::get('/corporate/forgot-password', [UsersController::class, 'resetpassword'])
    ->name('corporate.forgot.password');

Route::post('/corporate/forgot-password', [UsersController::class, 'doresetpassword'])
    ->name('corporate.forgot.password.submit')
    ->middleware('throttle:5,1');

Route::get('/corporate/reset-password/{token}', [UsersController::class, 'showResetPasswordForm'])
    ->name('corporate.password.reset');

Route::post('/corporate/reset-password', [UsersController::class, 'doResetPasswordWithToken'])
    ->name('corporate.password.update')
    ->middleware('throttle:5,1');

Route::get('/corporate-addresses', [UsersController::class, 'corporateAddresses'])
    ->name('corporate.addresses');

Route::post('/corporate-addresses/update', [UsersController::class, 'updateCorporateAddress'])
    ->name('corporate.addresses.update');

Route::post('/corporate-addresses', [UsersController::class, 'storeCorporateAddress'])
    ->name('corporate.addresses.store');

Route::post('/corporate-addresses/{id}/select', [UsersController::class, 'selectCorporateAddress'])
    ->name('corporate.addresses.select');

Route::match(['put', 'post'], '/corporate-addresses/{id}', [UsersController::class, 'updateSingleCorporateAddress'])
    ->where('id', '[0-9]+')
    ->name('corporate.addresses.update-single');

Route::delete('/corporate-addresses/{id}', [UsersController::class, 'deleteCorporateAddress'])
    ->name('corporate.addresses.delete');

Route::get('/corporate-account-settings', [UsersController::class, 'accountSettings'])
    ->name('corporate.account-settings');

Route::post('/corporate-account-settings', [UsersController::class, 'updateAccountSettings'])
    ->name('corporate.account-settings.update');

// Branding Assets Routes
Route::get('/branding-assets', [BrandingAssetController::class, 'index'])
    ->name('branding-assets.index')
    ->middleware('corporate.auth');
Route::post('/branding-assets', [BrandingAssetController::class, 'store'])
    ->name('branding-assets.store')
    ->middleware('corporate.auth');
Route::get('/branding-assets/{id}', [BrandingAssetController::class, 'show'])
    ->name('branding-assets.show')
    ->middleware('corporate.auth');
Route::put('/branding-assets/{id}', [BrandingAssetController::class, 'update'])
    ->name('branding-assets.update')
    ->middleware('corporate.auth');
Route::delete('/branding-assets/{id}', [BrandingAssetController::class, 'destroy'])
    ->name('branding-assets.destroy')
    ->middleware('corporate.auth');
Route::get('/branding-assets/{id}/download', [BrandingAssetController::class, 'download'])
    ->name('branding-assets.download')
    ->middleware('corporate.auth');

Route::match(['get', 'post'], '/corporate/logout', [UsersController::class, 'dostorelogout'])
    ->name('corporate.logout');
Route::post('/collections', [UsersController::class, 'createCollection'])->name('collections.create');
Route::get('/collections/{id}', [UsersController::class, 'viewCollection'])->name('collections.view');
Route::delete('/collections/{id}', [UsersController::class, 'deleteCollection'])->name('collections.delete');
Route::put('/collections/{id}', [UsersController::class, 'updateCollection'])->name('collections.update');
Route::get('/collections-all', [UsersController::class, 'allCollections'])->name('collections.all');
Route::post('/collections/{id}/to-cart', [UsersController::class, 'collectionToCart'])->name('collections.to-cart');
Route::get('/collection-products/search', [UsersController::class, 'collectionProductSearch'])->name('collections.products');
Route::get('/collections-for-product', [UsersController::class, 'collectionsForProduct'])->name('collections.for-product');
Route::post('/collections/{id}/items', [UsersController::class, 'addItemToCollection'])->name('collections.add-item');
Route::delete('/collections/{id}/items/{itemId}', [UsersController::class, 'removeItemFromCollection'])->name('collections.remove-item');
Route::put('/collections/{id}/items/{itemId}', [UsersController::class, 'updateItemQuantity'])->name('collections.update-item');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
Route::post('/cart/add-bundle', [CartController::class, 'addBundle'])->name('cart.add-bundle')->middleware('throttle:30,1');
Route::post('/cart/item/{id}', [CartController::class, 'updateItem'])->name('cart.update-item');
Route::delete('/cart/item/{id}', [CartController::class, 'removeItem'])->name('cart.remove-item');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/send-enquiry', [CartController::class, 'sendEnquiry'])->name('cart.send-enquiry');
Route::get('/request-quote', [CustomOrderController::class, 'create'])->name('custom-order.create');
Route::post('/request-quote', [CustomOrderController::class, 'store'])->name('custom-order.store')->middleware('throttle:10,1');
Route::post('/cart/register', [UsersController::class, 'storeCartRegister'])->name('cart.register')->middleware('throttle:5,1');
Route::get('/enquiry/success/{id}', [CartController::class, 'enquirySuccess'])->name('enquiry.success');
Route::get('/corporate/auth/google', [UsersController::class, 'redirectToGoogleCorporate'])->name('corporate.google');
Route::get('/corporate/auth/google/callback', [UsersController::class, 'handleGoogleCallback'])->name('corporate.google.callback');
Route::get('/track-order', [CustomerOrderController::class, 'trackForm'])->name('track.form');
Route::post('/track-order', [CustomerOrderController::class, 'trackLookup'])->name('track.lookup')->middleware('throttle:order-lookup');
Route::get('/track-order/{orderid}', [CustomerOrderController::class, 'trackShow'])->name('track.show')->where('orderid', '[0-9]+');
Route::get('/annual-corporate-membership', [MembershipPageController::class, 'show'])->name('membership.page');
Route::get('/corporate-gifting-budget-planner', [GiftPlannerController::class, 'index'])->name('planner.index');
Route::post('/corporate-gifting-budget-planner/recommend', [GiftPlannerController::class, 'recommend'])->name('planner.recommend')->middleware('throttle:planner-recommend');
Route::post('/corporate-gifting-budget-planner/image', [GiftPlannerController::class, 'image'])->name('planner.image')->middleware('throttle:planner-image');
// The customer's logo and brand assets for the bundle pictures
Route::post('/corporate-gifting-budget-planner/brand', [GiftPlannerController::class, 'brandStore'])->name('planner.brand.store')->middleware('throttle:20,1');
Route::post('/corporate-gifting-budget-planner/brand/remove', [GiftPlannerController::class, 'brandRemove'])->name('planner.brand.remove')->middleware('throttle:20,1');
Route::get('/corporate-gifting-budget-planner/brand/{id}', [GiftPlannerController::class, 'brandShow'])->name('planner.brand.show')->where('id', 'logo|asset-[0-9a-f]{12}');
Route::any('/store/resetpassword', [UsersController::class, 'resetpassword']);
Route::post('/store/doresetpassword', [UsersController::class, 'doresetpassword'])->name('doresetpassword')->middleware('throttle:5,1');

/*Store Category*/

Route::any('category/{slug}', [StoreCategoryController::class, 'category']);
Route::any('listview/category/{slug}/', [StoreCategoryController::class, 'listview'])->name('listview');
Route::any('subcategory/{slug}', [StoreCategoryController::class, 'subcategory']);
Route::any('listview/subcategory/{slug}', [StoreCategoryController::class, 'subcategorylist'])->name('subcategorylist');

/*Store Brand */
Route::any('brand/{slug}', [StoreBrandController::class, 'brand']);
Route::any('listview/brand/{slug}/', [StoreBrandController::class, 'listview'])->name('brand.listview');

/*Filters*/
Route::any('searchbar', [FiltersController::class, 'searchbar'])->name('searchbar');
Route::any('search_bar', [FiltersController::class, 'search_bar'])->name('search_bar');
Route::any('search_bar/{search_val}', [FiltersController::class, 'searching'])->name('searching');
Route::any('attfilter', [FiltersController::class, 'attfilter'])->name('attfilter');
Route::post('contactform', [HomepageController::class, 'contactform'])->name('contactform')->middleware('throttle:10,1');
Route::any('category_filter', [HomepageController::class, 'category_filter'])->name('category_filter');
Route::any('sub_category_filter', [HomepageController::class, 'sub_category_filter'])->name('sub_category_filter');
Route::any('brands', [HomepageController::class, 'brands'])->name('brands');

/*Checkout helpers used by the storefront*/
Route::any('storeshipping', [ShippingController::class, 'storeshipping'])->name('storeshipping');
Route::any('storeweightshipping', [ShippingController::class, 'storeweightshipping'])->name('storeweightshipping');
Route::any('coupon_code', [CouponController::class, 'coupon_code'])->name('coupon_code');
Route::any('remove_coupon_code', [CouponController::class, 'remove_coupon_code'])->name('remove_coupon_code');
Route::any('getinputvalue', [CouponController::class, 'getinputvalue'])->name('getinputvalue');

/* Paytabs (called by PayTabs, must stay public) */
Route::post('callback', [PaytabController::class, 'callback'])->name('callback');


/*API routes (JSON endpoints used by the old Angular storefront)*/

Route::get('getslider', [SliderController::class, 'getslider'])->name('getslider');

Route::get('getbannerlevel1', [ApiController::class, 'getbannerlevel1'])->name('getbannerlevel1');
Route::get('getbannerlevel2', [ApiController::class, 'getbannerlevel2'])->name('getbannerlevel2');
Route::get('getbannerlevel3', [ApiController::class, 'getbannerlevel3'])->name('getbannerlevel3');
Route::get('getbannerlevel6', [ApiController::class, 'getbannerlevel6'])->name('getbannerlevel6');

Route::get('categoryslider', [ApiController::class, 'categoryslider'])->name('categoryslider');
Route::get('mostselling', [ApiController::class, 'mostselling'])->name('mostselling');

Route::get('productl1', [ApiController::class, 'productl1'])->name('productl1');
Route::get('productl2', [ApiController::class, 'productl2'])->name('productl2');
Route::get('productl3', [ApiController::class, 'productl3'])->name('productl3');
Route::get('productl4', [ApiController::class, 'productl4'])->name('productl4');
Route::get('productl5', [ApiController::class, 'productl5'])->name('productl5');
Route::get('productl6', [ApiController::class, 'productl6'])->name('productl6');
Route::get('productl7', [ApiController::class, 'productl7'])->name('productl7');
Route::get('productl8', [ApiController::class, 'productl8'])->name('productl8');
Route::get('productl9', [ApiController::class, 'productl9'])->name('productl9');


Route::post('addtocart', [ApiController::class, 'addtocart'])->name('addtocart');
Route::post('getcatproducts', [ApiController::class, 'getcatproducts'])->name('getcatproducts');
Route::post('getsubcatproducts', [ApiController::class, 'getsubcatproducts'])->name('getsubcatproducts');

Route::post('cartdata', [ApiController::class, 'cartdata'])->name('cartdata');
Route::get('gethomedata', [ApiController::class, 'gethomedata'])->name('gethomedata');

Route::get('getallparentcategories', [ApiController::class, 'getallparentcategories'])->name('getallparentcategories');
Route::get('getallcategories', [ApiController::class, 'getallcategories'])->name('getallcategories');
Route::get('getallbrands', [ApiController::class, 'getallbrands'])->name('getallbrands');

Route::post('addsubscriber', [ApiController::class, 'addsubscriber'])->name('addsubscriber')->middleware('throttle:10,1');

Route::post('getproductdetails', [ApiController::class, 'getproductdetails'])->name('getproductdetails');
Route::post('getbrandproducts', [ApiController::class, 'getbrandproducts'])->name('getbrandproducts');

Route::post('searchproduct', [ApiController::class, 'searchproduct'])->name('searchproduct');

Route::any('paytab_returnurl', [ApiController::class, 'paytab_returnurl'])->name('paytab_returnurl');


// Removed: getuserdetails, getuserorderdetails and the API version of
// updateuserdetails. They read or changed any customer's account (including
// the password) from just a user id, with no login.

Route::post('stocknotify', [ApiController::class, 'stocknotify'])->name('stocknotify')->middleware('throttle:10,1');
Route::post('b2benquiry', [ApiController::class, 'b2benquiry'])->name('b2benquiry')->middleware('throttle:10,1');
Route::post('abandancart', [ApiController::class, 'abandancart'])->name('abandancart');
// Removed: enquiry, search_or_update and contact (old Angular storefront). They
// emailed customer enquiries and orders to outside addresses (FX-02); website
// quotes go through CartController::sendEnquiry.

Route::get('portfolio', [ApiController::class, 'portfolio'])->name('portfolio');
Route::get('portfolio-detail', [ApiController::class, 'portfolio_detail'])->name('portfolio_detail');
Route::get('featured_portfolio', [ApiController::class, 'featured_portfolio'])->name('featured_portfolio');


/*Admin login*/

Route::any('/admin', [AdminController::class, 'login']);
Route::post('/doadminlogin', [AdminController::class, 'doadminlogin'])->name('doadminlogin')->middleware('throttle:5,1');
Route::any('/adminlogout', [AdminController::class, 'adminlogout'])->name('adminlogout');


/*
|--------------------------------------------------------------------------
| Admin panel (login required)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin.auth', 'admin.permission', 'admin.same-origin'])->group(function () {

Route::any('crop-image-upload', [CropImageController::class, 'uploadCropImage']);

/*Dashboard*/
Route::any('/dashboard', [AdminController::class, 'dashboard']);
Route::any('search_monthly', [AdminController::class, 'search_monthly'])->name('search_monthly');
Route::any('search_yearly', [AdminController::class, 'search_yearly'])->name('search_yearly');

// Brands
Route::any('add-brands', [BrandController::class, 'index']);
Route::any('edit-brands/{id}', [BrandController::class, 'edit']);
Route::post('addbrand', [BrandController::class, 'add'])->name('addbrand');
Route::any('updatebrand', [BrandController::class, 'updatebrand'])->name('updatebrand');
Route::any('deletebrand/{id}', [BrandController::class, 'deletebrand']);
Route::any('listbrand', [BrandController::class, 'list']);
Route::any('brandfeatured', [BrandController::class, 'brandfeatured'])->name('brandfeatured');
Route::any('brandactive', [BrandController::class, 'brandactive'])->name('brandactive');
Route::any('delete_brand_thumbnail', [BrandController::class, 'delete_brand_thumbnail'])->name('delete_brand_thumbnail');
Route::any('delete_brand_banner', [BrandController::class, 'delete_brand_banner'])->name('delete_brand_banner');

// New Brand Management
Route::prefix('newbrand')->name('newbrand.')->group(function () {
    Route::get('/', [NewBrandController::class, 'index'])->name('index');
    Route::get('/create', [NewBrandController::class, 'create'])->name('create');
    Route::post('/', [NewBrandController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NewBrandController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NewBrandController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewBrandController::class, 'destroy'])->name('destroy');
});

// Order Processing Workflow
Route::prefix('orderprocessing')->name('orderprocessing.')->group(function () {
    Route::get('/', [OrderProcessingController::class, 'index'])->name('index');
    Route::get('/available', [OrderProcessingController::class, 'availableOrders'])->name('available');
    Route::get('/create/{cartorderId}', [OrderProcessingController::class, 'createWorkflow'])->name('create');
    Route::get('/{id}', [OrderProcessingController::class, 'show'])->name('show');
    Route::post('/{id}/complete/{step}', [OrderProcessingController::class, 'completeStep'])->name('completeStep');
    Route::post('/{id}/reset/{step}', [OrderProcessingController::class, 'resetStep'])->name('resetStep');
});

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
Route::any('updatesubcat', [SubcategoryController::class, 'updatecat'])->name('updatesubcat');
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
Route::any('updatechildsubcat', [ChildsubcategoryController::class, 'updatechildsubcat'])->name('updatechildsubcat');
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


/*variants*/



/*corporate pages*/

Route::any('addprivacy', [GeneralSettings::class, 'addprivacy']);
Route::any('updateprivacy', [GeneralSettings::class, 'updateprivacy'])->name('updateprivacy');

Route::any('addreturn', [GeneralSettings::class, 'addreturn']);
Route::any('updatereturn', [GeneralSettings::class, 'updatereturn'])->name('updatereturn');

Route::any('addshippment', [GeneralSettings::class, 'addshippment']);
Route::any('updateshippment', [GeneralSettings::class, 'updateshippment'])->name('updateshippment');


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
Route::any('add_product', [ProductController::class, 'add_products'])->name('add_products');
Route::any('single_product_add', [ProductController::class, 'single_product_add'])->name('single_product_add');
Route::any('sku_combination', [ProductController::class, 'sku_combination'])->name('sku_combination');
Route::any('sku_combination_edit', [ProductController::class, 'sku_combination_edit'])->name('sku_combination_edit');
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
Route::any('deleteproduct_filter', [ProductController::class, 'deleteproduct_filter'])->name('deleteproduct_filter');


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


/*Weightshipping */

Route::any('weightshipping', [ShippingController::class, 'weightshipping']);
Route::any('add-weightshipping', [ShippingController::class, 'weightadd'])->name('addweightshipping');
Route::any('find-weightshipping', [ShippingController::class, 'weightfind'])->name('findweightshipping');
Route::any('update-weightshipping', [ShippingController::class, 'weightupdate'])->name('updateweightshipping');
Route::any('delete-weightshipping', [ShippingController::class, 'weightdelete'])->name('deleteweightshipping');

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

/*Homepage brands and clients*/
Route::get('home-logos', [HomeLogoController::class, 'index'])->name('home-logos.index');
Route::post('home-logos', [HomeLogoController::class, 'store'])->name('home-logos.store');
Route::post('home-logos/{id}', [HomeLogoController::class, 'update'])->name('home-logos.update')->where('id', '[0-9]+');
Route::post('home-logos/{id}/remove', [HomeLogoController::class, 'destroy'])->name('home-logos.destroy')->where('id', '[0-9]+');

/*Supplies To*/
Route::get('filter-settings', [FilterSettingController::class, 'index'])->name('filter-settings.index');
Route::post('filter-settings', [FilterSettingController::class, 'update'])->name('filter-settings.update');
Route::get('supply-countries', [SupplyCountryController::class, 'index'])->name('supply-countries.index');
Route::post('supply-countries', [SupplyCountryController::class, 'store'])->name('supply-countries.store');
Route::post('supply-countries/{id}', [SupplyCountryController::class, 'update'])->name('supply-countries.update')->where('id', '[0-9]+');
Route::post('supply-countries/{id}/remove', [SupplyCountryController::class, 'destroy'])->name('supply-countries.destroy')->where('id', '[0-9]+');

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

/*Exhibition Events*/
Route::any('exhibition-events', [ExhibitionEventController::class, 'index']);
Route::any('add-exhibition-event', [ExhibitionEventController::class, 'add'])->name('addexhibitionevent');
Route::any('find-exhibition-event', [ExhibitionEventController::class, 'find'])->name('findexhibitionevent');
Route::any('update-exhibition-event', [ExhibitionEventController::class, 'update'])->name('updateexhibitionevent');
Route::any('delete-exhibition-event', [ExhibitionEventController::class, 'delete'])->name('deleteexhibitionevent');

/*Tier Pricing*/
Route::any('tier-pricing', [ProductController::class, 'tierPricing'])->name('tier_pricing');
Route::any('add-tier-pricing', [ProductController::class, 'addTierPricing'])->name('add_tier_pricing');
Route::any('find-tier-pricing', [ProductController::class, 'findTierPricing'])->name('find_tier_pricing');
Route::any('update-tier-pricing', [ProductController::class, 'updateTierPricing'])->name('update_tier_pricing');
Route::any('delete-tier-pricing', [ProductController::class, 'deleteTierPricing'])->name('delete_tier_pricing');
Route::post('apply-tier-pricing', [ProductController::class, 'applyTierPricing'])->name('apply_tier_pricing');


/*Admins */

Route::any('adminusers', [AdminController::class, 'adminusers']);
Route::any('add-adminuser', [AdminController::class, 'add'])->name('addadminuser');
Route::any('find-adminuser', [AdminController::class, 'find'])->name('findadminuser');
Route::any('update-adminuser', [AdminController::class, 'update'])->name('updateadminuser');
Route::any('delete-adminuser', [AdminController::class, 'delete'])->name('deleteadminuser');


/* Referral / Reward: removed, its database tables were never created */

/* Coupon */
Route::any('add-coupon', [CouponController::class, 'index']);
Route::any('add', [CouponController::class, 'add'])->name('add');
Route::any('list-coupon', [CouponController::class, 'list'])->name('coupon.list');
Route::any('edit-coupon/{slug}', [CouponController::class, 'edit'])->name('edit');
Route::any('updatecoupon', [CouponController::class, 'updatecoupon'])->name('updatecoupon');
Route::any('deletecoupon/{slug}', [CouponController::class, 'deletecoupon'])->name('deletecoupon');

/* B2B Enquiry */
Route::any('b2b-enquiry', [B2BenquiryController::class, 'list'])->name('b2benquiry.list');

/*Instock Notifier */
Route::any('instock-notifier', [Instock_notifierController::class, 'list'])->name('instock.list');

/* Reports (the three old URLs open admin/reports) */
Route::get('admin/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('admin/reports/export', [ReportController::class, 'export'])->name('reports.export');
Route::get('admin/planner-logs', [PlannerLogController::class, 'index'])->name('planner-logs.index');

/* Email templates: previews and a test send to the logged-in admin */
Route::get('admin/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
Route::get('admin/email-templates/{key}', [EmailTemplateController::class, 'show'])->name('email-templates.show')->where('key', '[a-z_]+');
Route::post('admin/email-templates/{key}/test', [EmailTemplateController::class, 'test'])->name('email-templates.test')->where('key', '[a-z_]+')->middleware('throttle:10,1');
Route::any('date_report', [ReportController::class, 'date_report'])->name('date_report');
Route::any('user_report', [ReportController::class, 'user_report'])->name('user_report');
Route::any('product_report', [ReportController::class, 'product_report'])->name('product_report');

/*Orders - Backend Store Enquiries */
Route::any('admin/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('admin/orders/{order}', [OrderWorkflowController::class, 'show'])->name('orders.show')->where('order', '[0-9]+');
Route::post('admin/orders/{order}/invoices/{type}', [OrderWorkflowController::class, 'issueInvoice'])->name('orders.invoice.issue')
    ->where(['order' => '[0-9]+', 'type' => 'advance|balance']);
Route::get('admin/orders/{order}/invoices/{invoice}', [OrderWorkflowController::class, 'showInvoice'])->name('orders.invoice.show')
    ->where(['order' => '[0-9]+', 'invoice' => '[0-9]+']);
Route::post('admin/orders/{order}/steps/{step}/{action}', [OrderWorkflowController::class, 'act'])->name('orders.act')
    ->where(['order' => '[0-9]+', 'step' => '[0-9]+', 'action' => '[a-z]+']);
Route::any('abandoned_order', [OrderController::class, 'abandoned_order'])->name('abandoned_order');
Route::any('abandoned_orderdetails/{id}', [OrderController::class, 'abandoned_orderdetails'])->name('abandoned_orderdetails');
Route::any('send_abandoned_mail', [OrderController::class, 'send_abandoned_mail'])->name('send_abandoned_mail');
Route::any('printinvoice/{slug}', [HomepageController::class, 'printinvoice']);
Route::any('deleteorder/{slug}', [HomepageController::class, 'deleteorder']);
Route::any('orderdetails/{id}', [HomepageController::class, 'orderdetails']);
Route::any('update_orderstatus', [HomepageController::class, 'update_orderstatus'])->name('update_orderstatus');

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

// Annual Membership page content (stats, plans section, plans)
Route::prefix('admin/annual-membership')->name('membership.')->group(function () {
    Route::get('/', [MembershipController::class, 'index'])->name('index');
    Route::post('/content', [MembershipController::class, 'updateContent'])->name('content');
    Route::get('/plans/create', [MembershipController::class, 'create'])->name('plans.create');
    Route::post('/plans', [MembershipController::class, 'store'])->name('plans.store');
    Route::get('/plans/{id}/edit', [MembershipController::class, 'edit'])->name('plans.edit')->where('id', '[0-9]+');
    Route::post('/plans/{id}', [MembershipController::class, 'update'])->name('plans.update')->where('id', '[0-9]+');
    Route::post('/plans/{id}/delete', [MembershipController::class, 'destroy'])->name('plans.destroy')->where('id', '[0-9]+');
});

// Order Tracking (Backend)
Route::prefix('order-tracking')->name('ordertracking.')->group(function () {
    Route::get('/', [OrderTrackingController::class, 'index'])->name('index');
    Route::get('/list', [OrderTrackingController::class, 'list'])->name('list');
    Route::get('/{id}', [OrderTrackingController::class, 'show'])->name('show');
    Route::match(['get', 'post'], '/step/{step}/save', [OrderTrackingController::class, 'saveStep'])->name('saveStep');
});


/*Common*/

Route::any('storeaboutus', [CommonController::class, 'storeaboutus']);
Route::any('updateaboutus', [CommonController::class, 'updateaboutus'])->name('updateaboutus');

Route::any('storecontactus', [CommonController::class, 'storecontactus']);
Route::any('updatecontactus', [CommonController::class, 'updatecontactus'])->name('updatecontactus');


/*Filters*/
Route::any('find_subcat_filter', [FilterController::class, 'find_subcat_filter'])->name('find_subcat_filter');
Route::any('find_cat_filter', [FilterController::class, 'find_cat_filter'])->name('find_cat_filter');

// Homepage Category settings
Route::any('category-section', [HomepagesettingController::class, 'category_section'])->name('category_section');
Route::any('categorysettingupdate', [HomepagesettingController::class, 'categorysettingupdate'])->name('categorysettingupdate');
Route::any('tag-section', [HomepagesettingController::class, 'tag_section'])->name('tag_section');
Route::any('tagsettingupdate', [HomepagesettingController::class, 'tagsettingupdate'])->name('tagsettingupdate');
Route::any('content-section', [HomepagesettingController::class, 'content_section'])->name('content_section');
Route::any('contentupdate', [HomepagesettingController::class, 'contentupdate'])->name('contentupdate');


/*Analytics */
Route::any('visited_page', [AnalyticsController::class, 'visited_page'])->name('visited_page');
Route::any('mostvisitedpage', [AnalyticsController::class, 'mostvisitedpage'])->name('mostvisitedpage');
Route::any('usersbydate', [AnalyticsController::class, 'usersbydate'])->name('usersbydate');

/* FastExcel */
Route::any('exportcategory', [FastexcelController::class, 'exportcategory'])->name('exportcategory');
Route::any('importcategory', [FastexcelController::class, 'importcategory'])->name('importcategory');
Route::any('exportsubcategory', [FastexcelController::class, 'exportsubcategory'])->name('exportsubcategory');
Route::any('importsubcategory', [FastexcelController::class, 'importsubcategory'])->name('importsubcategory');
Route::any('exportproduct', [FastexcelController::class, 'exportproduct'])->name('exportproduct');
Route::any('importproduct', [FastexcelController::class, 'importproduct'])->name('importproduct');
Route::any('exportstoreuser', [FastexcelController::class, 'exportstoreuser'])->name('exportstoreuser');
Route::any('store-user', [UsersController::class, 'storeuser'])->name('storeuser');
Route::any('exportappliances', [FastexcelController::class, 'exportappliances'])->name('exportappliances');
Route::any('exportaccessories', [FastexcelController::class, 'exportaccessories'])->name('exportaccessories');
Route::any('bulkdeleteproducts', [FastexcelController::class, 'bulkdeleteproducts'])->name('bulkdeleteproducts');
Route::any('dobulkdeleteproducts', [FastexcelController::class, 'dobulkdeleteproducts'])->name('dobulkdeleteproducts');
Route::any('exportb2benquiry', [FastexcelController::class, 'exportb2benquiry'])->name('exportb2benquiry');


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

});
