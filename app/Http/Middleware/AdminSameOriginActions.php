<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSameOriginActions
{
    /**
     * Admin controller methods that only display something. Every other admin
     * method changes data, and many of them run on a plain GET link (delete,
     * toggle active/featured, import, send email...), which another website
     * could trigger in a logged-in admin's browser. Those GET requests must
     * come from this site (or be typed into the address bar).
     */
    private const VIEW_METHODS = '/^(index|list|list_\w+|show|showslider|listslider|edit\w*|banners|small_banners_section\d|cms|seosettings|header|footerlevel1|fsection\d|homepage|superadmin|addprivacy|addreturn|addshippment|add_products|tierPricing|shipping|weightshipping|color|adminusers|subscribers|date_report|user_report|product_report|abandoned_order|abandoned_orderdetails|visited_page|mostvisitedpage|usersbydate|category_section|tag_section|content_section|storeaboutus|storecontactus|divisions|add_division|add_portfolio|add_portfolio_category|add_portfolio_subcategory|transfer_category|availableOrders|create|search_monthly|search_yearly|dashboard|orderdetails|printinvoice|storeuser|bulkdeleteproducts|add_flipbook|find\w*|get\w+|products)$/';

    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request); // POST etc. are covered by the CSRF token
        }

        $action = optional($request->route())->getActionMethod();
        if (!$action || preg_match(self::VIEW_METHODS, $action) || $this->fromThisSite($request)) {
            return $next($request);
        }

        \Log::warning('Blocked cross-site admin action', ['path' => $request->path(), 'site' => $request->header('Sec-Fetch-Site'), 'referer' => $request->header('Referer')]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'This action must be started from the admin panel.'], 403);
        }
        return redirect('dashboard')->with('error', 'That action was blocked because it did not come from the admin panel. Please try again from the admin.');
    }

    private function fromThisSite(Request $request)
    {
        $site = $request->header('Sec-Fetch-Site');
        if ($site !== null) {
            // "none" = typed into the address bar or opened from a bookmark
            return in_array($site, ['same-origin', 'none'], true);
        }

        // Older browsers: fall back to the Referer header
        $referer = $request->header('Referer');
        return $referer && parse_url($referer, PHP_URL_HOST) === $request->getHost();
    }
}
