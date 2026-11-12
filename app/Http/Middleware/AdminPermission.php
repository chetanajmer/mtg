<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class AdminPermission
{
    /**
     * Admin pages by permission flag (columns on the admins table). Patterns
     * are matched against the request path with Str::is(). Superadmins may
     * open everything; paths not listed here (e.g. the dashboard) are open to
     * every logged-in admin.
     */
    public const MAP = [
        'superadmin' => [
            'adminusers', 'add-adminuser', 'find-adminuser', 'update-adminuser', 'delete-adminuser',
            'superadmin-settings', 'superadmin-update',
        ],
        'brand_status' => [
            'add-brands', 'edit-brands/*', 'addbrand', 'updatebrand', 'deletebrand/*', 'listbrand',
            'brandfeatured', 'brandactive', 'delete_brand_*', 'newbrand', 'newbrand/*',
        ],
        // Read-only lookups used by several forms (findsubcat, find_subcat_filter,
        // find_cat_filter, findsubcategory, getparentcategories, find*forproduct)
        // are deliberately left open to every admin.
        'parentcategory_status' => [
            'add-parentcategories', 'edit-parentcategories/*', 'addparentcategory', 'findparentcat', 'updateparentcat',
            'deleteparentcategory/*', 'listparentcategory', 'catparentfeatured', 'catparentactive', 'delete_parentcat_*',
        ],
        'category_status' => [
            'add-categories', 'edit-categories/*', 'addcategory', 'updatecat', 'deletecategory/*', 'listcategory',
            'catfeatured', 'catactive', 'delete_category_*', 'transfer_category', 'dotransfer*',
            'add-subcategories', 'edit-subcategories/*', 'addsubcategory', 'updatesubcat', 'deletesubcat/*',
            'listsubcat', 'deletefilter', 'subcatactive', 'delete_subcategory_*',
            'add-childsubcategories', 'edit-childsubcategories/*', 'addchildsubcategory', 'updatechildsubcat',
            'deletechildsubcat/*', 'listchildsubcat', 'childsubcatactive', 'delete_childsubcategory_*',
            'exportcategory', 'importcategory', 'exportsubcategory', 'importsubcategory',
        ],
        'product_status' => [
            'add-products', 'addproducts', 'edit-products/*', 'updateproduct', 'deletemyproduct', 'listproduct',
            'deleteimage*', 'deletebulkimage', 'newarrival', 'sale', 'active', 'add_product', 'single_product_add',
            'sku_combination*', 'most_selling', 'featured', 'delete_installation',
            'appliances', 'accessories', 'delete_variation_image', 'products', 'deleteproduct_filter',
            '*color*', '*branding-option*', '*occasion*', '*preference*', '*exhibition-event*', '*tier-pricing*',
            'exportproduct', 'importproduct', 'exportappliances', 'exportaccessories', 'bulkdeleteproducts', 'dobulkdeleteproducts',
            'crop-image-upload',
        ],
        'order_status' => [
            'admin/orders', 'admin/orders/*', 'abandoned_order', 'abandoned_orderdetails/*', 'send_abandoned_mail',
            'printinvoice/*', 'deleteorder/*', 'orderdetails/*', 'update_orderstatus',
            'order-tracking', 'order-tracking/*', 'orderprocessing', 'orderprocessing/*',
            'date_report', 'user_report', 'product_report', 'admin/reports', 'admin/reports/*', 'admin/planner-logs', 'admin/planner-recommendations', 'admin/planner-recommendations/*', 'admin/planner-pictures', 'admin/planner-pictures/*','b2b-enquiry', 'exportb2benquiry', 'instock-notifier',
            'store-user', 'exportstoreuser', 'search_monthly', 'search_yearly',
        ],
        'slider_status' => [
            'show-slider', 'add-slider', 'list-slider', 'edit-slider/*', 'updateslider', 'deleteslider/*',
        ],
        'subscriber_status' => [
            'subscribers', 'deletesubscribers/*',
        ],
        'banner_status' => [
            'banners', 'updatebanner', '*small_banners_section*',
            'category-section', 'categorysettingupdate', 'tag-section', 'tagsettingupdate', 'content-section', 'contentupdate',
        ],
        'config_status' => [
            'filter-settings', 'admin/email-templates', 'admin/email-templates/*', 'supply-countries', 'supply-countries/*', 'home-logos', 'home-logos/*',
            'cms-settings', 'update-cms-settings', 'seo-settings', 'updateseo-settings', 'header-settings', 'updateheader-settings',
            'footerlevel1', 'updatefooterlevel1', 'footer-section*', 'footer-updatesection*', 'homepage-settings', 'homepage-update',
            'addprivacy', 'updateprivacy', 'addreturn', 'updatereturn', 'addshippment', 'updateshippment',
            '*shipping*', 'add-coupon', 'add', 'list-coupon', 'edit-coupon/*', 'updatecoupon', 'deletecoupon/*',
            '*flipbook*', '*portfolio*', '*division*', 'visited_page', 'mostvisitedpage', 'usersbydate', 'reviews',
            'storeaboutus', 'updateaboutus', 'storecontactus', 'updatecontactus',
            'admin/annual-membership', 'admin/annual-membership/*',
        ],
    ];

    public function handle(Request $request, Closure $next)
    {
        $flag = self::flagFor($request->path());
        if ($flag && !self::allowed(session()->get('admin_id'), $flag)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'You do not have permission for this section.'], 403);
            }
            return redirect('dashboard')->with('error', 'You do not have permission to open that page.');
        }

        return $next($request);
    }

    /**
     * Permission flag needed for a path, or null when any admin may open it.
     */
    public static function flagFor($path)
    {
        $path = trim($path, '/');
        foreach (self::MAP as $flag => $patterns) {
            foreach ($patterns as $pattern) {
                if (\Illuminate\Support\Str::is($pattern, $path)) {
                    return $flag;
                }
            }
        }
        return null;
    }

    /**
     * Whether an admin may use a permission flag ('superadmin' = superadmin only).
     */
    public static function allowed($adminId, $flag)
    {
        static $admins = [];
        if (!array_key_exists($adminId, $admins)) {
            $admins[$adminId] = Admin::find($adminId);
        }
        $admin = $admins[$adminId];
        if (!$admin) {
            return false;
        }
        if ($admin->role === 'superadmin') {
            return true;
        }
        return $flag !== 'superadmin' && $admin->{$flag} === 'on';
    }
}
