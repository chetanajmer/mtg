# MoreThanGiftz — Complete Project Audit

| | |
|---|---|
| Audit date | 6 Nov 2026 |
| Project folder | `C:\xampp\htdocs\mtg_first\mtg\mtg` |
| Git branch | `dev-naresh` (HEAD `c50e6a5 Update product page and header design`) |
| Uncommitted work at audit time | `HomepageController.php`, `routes/web.php`, `compare-products.blade.php`, `annual-corporate-membership.blade.php` + `.css` modified; `Frontend/CompareController.php` new (untracked). **These were audited as they are on disk.** |
| Files changed by this audit | **None** except this report. `git status` was identical before and after. No database rows were inserted, updated or deleted. |

## How this audit was done (and what it could not do)

**Verified by actually running something**

| Check | Result |
|---|---|
| `php -l` on every PHP file in `app/`, `routes/`, `config/`, `database/` | 0 syntax errors |
| Route table (`php artisan route:list`) + script that checks every route's controller class and method exists | 432 routes, **0 missing classes or methods**; 306 routes use `Route::any` (accept every HTTP verb) |
| `php artisan migrate:status` (read-only) | 36 ran, **3 pending** (see F-1) |
| Database connection + read-only `information_schema` and `SELECT COUNT(*)` integrity queries | Connected (MariaDB 10.4.32, database `mtg`, 79 tables) |
| HTTP crawl on a private test server (`php -S 127.0.0.1:8014`, `MAIL_MAILER=log`): visitor, logged-in customer (id 58), superadmin (id 1), limited staff admin (id 14) | ~180 GET requests, details in each section |
| Cross-customer access tests (customer 58 opening other customers' records) | Orders, collections, branding assets and enquiries return 404 — **isolation works** |
| Admin permission tests (staff account without product/order rights) | Correctly redirected away from restricted sections |
| `node --check` on 7 site JS files + parsing of every inline `<script>` on 17 rendered pages | 0 syntax errors |
| Built-in browser: 9 public pages at 1400px and 375px; broken images; console errors | Results in G |
| `composer validate` | `composer.lock` is out of date with `composer.json` |
| `storage/logs/laravel.log` review | 39 errors logged on 6 Nov (23 before this audit; the rest are this audit's own test requests, explained below) |

The test sessions created for logged-in checks were session *files* in `storage/framework/sessions`; all three were deleted afterwards. The test server was stopped.

**Not verified (and why)**

| Not tested | Reason |
|---|---|
| Any form submit or POST that writes data (add/edit/delete product, cart add, quote send, address save, collection edit, order workflow actions, uploads) | You asked for no database or file changes. These were checked by **reading the code and comparing it with the real schema**; every such finding is marked "code + schema" rather than "HTTP-tested". |
| Email delivery | Not allowed to send email; local `.env` points at `mailhog`, which does not exist. |
| PayTabs payments, Google login, Google Analytics, MidOcean import | No credentials in the local `.env`; external services; not allowed to start transactions. |
| Automated test suite | There is none: no `tests/` folder, although `phpunit.xml` expects one. |
| Product images | `public/upload/` locally contains only `branding_options/`; all 9 products have **no image file** on this machine, so image display could not be judged. |
| Live site (beta.morethangiftz.com) | Out of scope: local project only. |
| `composer audit` (security advisories) | Not run: it sends the package list to an external service. The handoff notes say 69 advisories. |

Status words in this report: **Verified Working**, **Partially Working**, **Not Working**, **Not Implemented**, **Unable to Verify** (as you asked). They describe what was actually observed.

---

## A. Project Overview

| Item | Finding |
|---|---|
| Framework | Laravel **8.42.1** (`laravel/framework ^8.40`). Laravel 8 stopped receiving security fixes in Jan 2023. |
| PHP | CLI **7.4.33** (end of life Nov 2022). `composer.json` allows `^7.3\|^8.0`. |
| Database | **MariaDB 10.4.32** (XAMPP), database `mtg`, user `root` with an empty password. *Note:* `docs/PROJECT-HANDOFF.md` says "Laragon MySQL 8.4"; this machine is XAMPP + MariaDB. |
| Tables | 79 tables. **35 MyISAM** (no transactions, no foreign keys) incl. `products`, `orderids`, `brands`, `categories`, `subcategories`, `password_resets`, `migrations`. Only **1 foreign key** in the whole database (`cartorders.userid → storeusers.id`). |
| Key packages | `laravel/socialite` (Google login), `laravel/ui`, `rap2hpoutre/fast-excel` (Excel import/export), two PayTabs packages (both excluded from auto-discovery; payments use a hand-written cURL helper in `app/helpers.php`), `spatie/laravel-analytics`, `akki-io/laravel-google-analytics`, `intervention/image`, `spatie/laravel-image-optimizer`, `fruitcake/laravel-cors`. |
| Front-end build | `webpack.mix.js` / `package.json` exist, but `public/js/app.js` (1.5 MB) is not referenced by any view. Pages load Bootstrap 5.3.3 and Bootstrap Icons from jsDelivr, plus hand-written CSS/JS in `public/web_frontend/assets`. |
| Architecture | Classic server-rendered Laravel MVC. Two front-ends live side by side: the **new storefront** (`resources/views/web_frontend/*`, layout `web_frontend.layouts.app`) and the **old theme** (`resources/views/frontend/*`, layout `layouts/frontapp.blade.php`, plus JSON endpoints for an old Angular site). The **admin panel** (`resources/views/backend/*`) is a separate Bootstrap theme. |
| Authentication | No Laravel `Auth` guards. **Customers:** `session('userid')`; middleware `corporate.auth` (`CorporatePortalAuth`) on some routes, and manual `session()->has('userid')` checks in others. **Admins:** `session('admin_id')`; middleware `admin.auth` + `admin.permission` (flags on the `admins` row) + `admin.same-origin` (blocks cross-site GET actions). Passwords are bcrypt (0 plain-text passwords left in `storeusers` and `admins`; verified by query). |
| Business logic layer | `app/Services/OrderWorkflow.php` (10-step order workflow), `app/Services/OrderReports.php` (reports), pricing helpers on `App\Models\Product`. Everything else is in very large controllers (`ProductController` 4,026 lines, `ApiController` 1,528, `UsersController` 1,253, `GeneralSettings` 1,037). |
| Files/uploads | Admin uploads go to `public/upload/...` with `->move()` (50 call sites). Customer uploads go to `storage/app/public/...` (public via the `public/storage` symlink, which exists). Global middleware `RejectDangerousUploads` allow-lists extensions; `public/.htaccess` blocks script execution in upload folders (Apache only). |
| Environment | `.env`: `APP_ENV=local`, `APP_DEBUG=true`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`, `MAIL_HOST=mailhog`, no PayTabs/Google/MidOcean keys. `.env` is git-ignored (good). Deployment: `.cpanel.yml` rsyncs `main` to `beta.morethangiftz.com` on push. |
| Modules discovered | **38** (listed in section B) |

### How frontend, backend and database connect

```
Browser ──> routes/web.php ──> middleware (web group: session, CSRF, RejectDangerousUploads)
              │                     + corporate.auth / admin.auth / admin.permission / admin.same-origin
              ▼
        Controllers (Frontend\*, Backend\*, GeneralSettings, OrderProcessingController …)
              │  Eloquent models (app/Models, 64 files) and many direct DB::table() queries
              │  Services: OrderWorkflow, OrderReports; config/order_steps.php (10 steps)
              ▼
        MariaDB `mtg` (InnoDB + MyISAM)      Files: public/upload, storage/app/public
              │
              ▼
        Blade views: web_frontend/* (new), frontend/* (old theme), backend/* (admin), mail/*, emails/*
```

---

## B. Module-by-Module Status

Priority = how urgently the module's issues need fixing (Critical / High / Medium / Low).

### Storefront

| # | Module | Files | Status | Working (verified) | Issues found | Missing | Priority |
|---|---|---|---|---|---|---|---|
| 1 | **Homepage** | `Frontend/HomepageController@index`, `web_frontend/home.blade.php` | Partially Working | 200 OK; lists 14 online categories and latest 8 products from DB; all 14 category links and 8 product links on the page return 200; no horizontal scroll at 1400px / 375px. | Shows test products "Vishakha Upadhyay" and "A5 Notebook … aaasdf" (DB data). No product images locally. | — | Medium |
| 2 | **Header** | `web_frontend/partials/header.blade.php`, view composer in `AppServiceProvider` | Partially Working | Cart badge (count of cart rows), login/dashboard/logout icons, category links. | **Search box is a bare `<input>` with no form or JS** (does nothing). "Best Sellers", "Sustainable", "Branding" link to `#`. Phone number hard-coded. Logout is a GET link. | Working search | High |
| 3 | **Footer** | `web_frontend/partials/footer.blade.php` | Not Implemented | — | Entire footer is inside an HTML comment; its links are all `href="#"`. The new storefront has **no visible footer**. | Footer | Medium |
| 4 | **Product Listing (category / subcategory / brand / search)** | `StoreCategoryController`, `StoreBrandController`, `FiltersController`, `frontend/category*.blade.php`, `frontend/search.blade.php`, `layouts/frontapp.blade.php` | Partially Working | `/search_bar/bottle`, `/brands` return 200; unknown category returns 404. | These pages use the **old theme** (different look). 12 old-theme images 404 (logo, banners, icons). Old-layout JS contains hard-coded URLs to `https://meemindustrial.spdemoserver.com/...` (old agency demo server) — broken. Contact page shows old "Meem/SilverGiftz" branding. Random ordering via `ORDER BY RAND()`. | New-design listing pages | Medium |
| 5 | **Product Details** | `HomepageController@webProduct`, `web_frontend/product.blade.php` | Partially Working | 200 OK; unknown slug → 404; MOQ-locked +/− quantity; tier table shown only when tier pricing is on; "Add to Quote" posts to `/cart/add`. | **Price shown does not follow quantity** (see BUG-03): the page always shows the *last/highest-quantity* tier price, and the server-rendered HTML shows **AED 0.00** (reads a non-existent `cost_price` column) until JS runs. "Request a Sample" button does nothing. Description/spec rendered as raw HTML (`{!! !!}`). | Sample request flow | **Critical** |
| 6 | **Compare Products** (uncommitted rework) | `Frontend/CompareController.php` (new), `HomepageController@compareProducts`, `web_frontend/compare-products.blade.php`, routes `api/compare/*`, `compare/save-selection` | Not Working (after first selection) | Page loads (200); search with nothing selected returns products (200). | **`/api/compare/search` crashes (HTTP 500) as soon as one product is selected** — `excluded_ids` is sent as `"1,2"` but used as an array (BUG-01; 23 occurrences in today's log before this audit). Quantity fixed at 500 and "500–999 pcs" text hard-coded; prices ignore MOQ; "Add to cart" API adds offline products; product names injected into HTML without escaping; page is **593px wide on a 375px phone** (sideways scroll). | MOQ-aware compare, validation | **Critical** |
| 7 | **Cart (Quote Cart)** | `Frontend/CartController.php`, `CartItem`, `web_frontend/cart.blade.php` | Partially Working | `/cart` 200 for visitor and customer; lines re-priced from `Product::priceForQuantity()` on every view; MOQ enforced on add/update; 5% tax from `config('app.cart_tax_rate')`. | `addItem` returns **total pieces** as `count`, but the header badge shows **number of lines** → badge jumps (e.g. 2 → 600) after "Add to Quote" (BUG-07). Offline products can be added and quoted. One DB query per line for tier prices. | — | High |
| 8 | **Request a Quote (send enquiry)** | `CartController@sendEnquiry`, `enquirySuccess`, `Enquiry`, `Cartorder`, `OrderWorkflow::createForCartorders`, `mail/quote_request_*` | Unable to Verify (POST) — code reviewed | Code: one transaction creates enquiry + `cartorders` lines + `orders` row; staff + customer emails, failures logged; success page restricted to the sender (verified: other enquiry → 404). | Order number from `orderids` (MyISAM, no unique index) with `lockForUpdate()` — **the lock does nothing on MyISAM**, and the old `addtocart` endpoint uses a *different* numbering method on the same table → duplicate order numbers possible (BUG-09). | — | High |
| 9 | **Track Order (public lookup)** | `CustomerOrderController@trackForm/trackLookup/trackShow`, `web_frontend/order-tracking.blade.php` | Partially Working | Form 200; `/track-order/{id}` without a prior lookup redirects to the form (verified); lookup throttled. | POST lookup not exercised. | — | Low |
| 10 | **Annual Corporate Membership** | `Route::view`, `web_frontend/annual-corporate-membership.blade.php` (modified, uncommitted) | Not Implemented (static) | Page 200. | **No `<form>`** — captures no leads. Slight overflow at 375px (367px in a 360px viewport). | Lead form + storage + email | Medium |
| 11 | **Corporate Gifting Budget Planner** | `Route::view`, `web_frontend/corporate-gifting-budget-planner.blade.php` | Partially Working (static page) | 200, responsive. | No form/back-end; nothing is saved or sent. | Save/enquiry | Low |
| 12 | **About / Contact / Policies** | `HomepageController@aboutus/contactus/privacy/return/shippment/contactform`, `frontend/*` | Partially Working | All 200. Contact form handler validates and emails staff (code). | Old theme; **7 broken images** on Contact Us; old brand names in `frontend/contactus.blade.php` and `layouts/frontapp.blade.php`; Contact page 427px wide on phone. | New-design pages | Medium |
| 13 | **Old-theme Wishlist** | `HomepageController@addtowishlist/wishlist/deletewishlist`, `frontend/wishlist.blade.php` | Partially Working | Visitor → redirect to old `/store/login` (verified). | Delete is a GET link (`deletewishlist/{id}`). Separate from the corporate wishlist. | — | Low |
| 14 | **Legacy Angular JSON API** | `Backend/ApiController.php`, routes `getslider` … `abandancart` (web.php lines 281–336) | Not Working safely | Read endpoints return 200 JSON. | **`POST /addtocart` (public, CSRF-exempt, no validation) can overwrite any customer's email/name/address and builds the PayTabs amount from browser-supplied prices** (SEC-01, SEC-02). `abandancart`, `stocknotify`, `b2benquiry` write rows without validation. No caller in the new storefront except the old layout. | — | **Critical** |

### Accounts and corporate portal

| # | Module | Files | Status | Working (verified) | Issues found | Missing | Priority |
|---|---|---|---|---|---|---|---|
| 15 | **Login / Registration (corporate)** | `UsersController@corporateLogin/doCorporateLogin/corporateRegister/doCorporateRegister/storeCartRegister`, `web_frontend/auth/*` | Partially Working | Pages 200; login throttled 5/min; login regenerates the session; safe redirect check; bcrypt. | **Registration does not regenerate the session ID** (session fixation, SEC-06). **No email verification** — and orders are matched by email too (SEC-07). Logout only forgets two keys (no invalidate; `fullname` remains) and is a GET. | Email verification | High |
| 16 | **Login / Registration (old store)** | `UsersController@storelogin/dostorelogin/storeregister/dostoreregister` | Partially Working | Pages 200. | `dostoreregister` has **no validation** and sends mail synchronously after saving — any mail error gives a 500 after the account is created. | — | Medium |
| 17 | **Forgot / Reset Password** | `UsersController@resetpassword/doresetpassword/showResetPasswordForm/doResetPasswordWithToken` | Partially Working | Page 200. Code: hashed one-time token, 60-min expiry, same message for unknown emails, throttled. | Existing sessions are not logged out after a reset. Email delivery not tested. | — | Low |
| 18 | **Google login** | `UsersController@redirectToGoogleCorporate/handleGoogleCallback` | Unable to Verify | Code shows a friendly message when keys are missing. | Links an existing account by email automatically. Dead duplicate in `GeneralSettings`. | Keys from owner | Low |
| 19 | **Customer Dashboard (corporate portal)** | `UsersController@corporatePortal/dashboardStats`, `web_frontend/myaccount.blade.php`, `corporate-sidebar.blade.php` | Partially Working | 200 for customer; visitor → login (verified). Cards and Recent Orders read the `orders` table. | `/store/myaccount` renders the same view with **different variables** (`userdetails`, `items_array`) — returned 200 but shows the old data model. Some dashboard panels still demo content (handoff FX-38). Loads *all* the customer's orders with relations on each visit. | — | Medium |
| 20 | **Corporate Orders (My Orders + order page)** | `HomepageController@orders`, `CustomerOrderController@show/act/respond`, `web_frontend/orders.blade.php`, `order-progress.blade.php` | Partially Working | `/orders` and `/orders/10155` 200 for the owner; another customer's order → 404 (verified); signed email links. | Ownership also by `customer_email`, combined with no email verification (SEC-07). Approve/reject/rate POSTs not exercised. | — | High |
| 21 | **Collections** | `UsersController@allCollections/viewCollection/createCollection/...`, `Collection`, `CollectionItem`, `collections-list.blade.php`, `collection-detail.blade.php` | Partially Working | List 200; another user's collection → 404 (verified); picker search returns JSON. | Write actions not exercised. Estimated value runs one tier query per item (N+1). Routes have no middleware (each method checks the session itself — works, but easy to miss on new methods). | — | Medium |
| 22 | **Corporate Wishlist** | `Route::view('/corporate-portal/wishlist')`, `web_frontend/corporate-wishlist.blade.php` | Not Implemented (mock-up) | — | **Open to anyone without login** (verified 200 as visitor); static fake data ("ABC Technologies", fixed counts); no form. | Real wishlist | Medium |
| 23 | **Corporate Addresses** | `UsersController@corporateAddresses/storeCorporateAddress/updateSingleCorporateAddress/selectCorporateAddress/deleteCorporateAddress/updateCorporateAddress`, `StoreuserAddress`, `addresses.blade.php`, `partials/address-card.blade.php` | **Not Working (add/edit)** — code + schema | List page 200 for customer. | **Saving or editing an address will fail with a SQL error**: the form and model write `state` and `pincode`, but the `storeuser_addresses` table has neither column — migration `2026_11_02_095728` has not run (BUG-02). Two update routes do overlapping things (`/corporate-addresses/update` vs `/{id}`). | Run/fix migration | **Critical** |
| 24 | **Account Settings** | `UsersController@accountSettings/updateAccountSettings`, `account-settings.blade.php` | Partially Working | 200 for customer; visitor → login. | Update not exercised. No password change and no email change in the page. | Change password | Medium |
| 25 | **Branding Assets (customer)** | `Frontend/BrandingAssetController.php`, `BrandingAsset`, `branding-assets.blade.php` | Partially Working | 200 for customer; another customer's asset → 404 (verified). | **Files are publicly downloadable without login** at `/storage/branding_assets/<timestamp>_<original name>` (verified: 200 as visitor) (SEC-04). **SVG uploads allowed** → stored script served from the site (SEC-05). Original file names kept. Table exists but its migration is recorded as *not run* and the foreign key failed (F-1). | Private storage | High |
| 26 | **Reports (customer)** | `Frontend/CustomerReportController.php`, `Services/OrderReports.php`, `reports.blade.php` | Verified Working (GET) | Page 200; CSV download 200; `corporate.auth`; filtered by `storeuser_id`. | Filters/paginates in PHP (fine at today's volume). | — | Low |

### Orders and workflow

| # | Module | Files | Status | Working (verified) | Issues found | Missing | Priority |
|---|---|---|---|---|---|---|---|
| 27 | **Order Tracking workflow (10 steps, staff side)** | `Backend/OrderWorkflowController.php`, `Services/OrderWorkflow.php`, `config/order_steps.php`, `Order`, `OrderStepState`, `OrderEvent`, `backend/orders/workflow.blade.php` | Partially Working | `/admin/orders/{id}` 200; step definitions in one config; step-order rule; append-only event log; signed customer links; files validated. 14 orders, 140 step states, 0 orphan step states. | Step actions (POST) not exercised. Step 1 customer label "Order confirmed" shows before staff confirm (FX-42). Confirmed total not copied to `orders.total` (FX-39). No due dates / overdue (FX-13). Payment steps record payment manually; **no link to PayTabs status**. Legacy `cartorders.orderstatus` holds mixed values ("Pending", "Mass Production · In progress"…). | SLA/overdue, payment link | High |
| 28 | **Store Enquiries (admin order list)** | `Backend/OrderController@index`, `abandoned_order`, `send_abandoned_mail` | Partially Working | `/admin/orders` and page 2 → 200. | Loads **every** `cartorders` row and paginates in PHP; same for abandoned carts. No search/filters. `orderdetails/{orderid}` and `printinvoice/{orderid}` 200. | Filters/search | Medium |
| 29 | **Legacy Order Processing + old Order Tracking** | `OrderProcessingController.php`, `Backend/OrderTrackingController.php`, tables `order_processing_workflows` (3 rows), `order_workflow_steps` (14 rows) | Partially Working (superseded) | Pages load; `/order-tracking` redirects to `/admin/orders`. | Duplicate, unused workflow kept alongside the new one (confusing, more attack surface). | Remove after owner OK | Low |
| 30 | **Payments (PayTabs)** | `Backend/PaytabController@callback`, `ApiController@paytab_returnurl`, `app/helpers.php` | Unable to Verify | Code: callback signature (HMAC) checked; return URL re-queries PayTabs. | Amount is never compared with the expected order total, and the amount sent to PayTabs comes from the browser (SEC-02). Writes `paytab_status` to `tmporders`/`cartorders` only — the new `orders` workflow never sees it. | Amount check, workflow link | **Critical** (if PayTabs is live) |

### Admin panel

| # | Module | Files | Status | Working (verified) | Issues found | Missing | Priority |
|---|---|---|---|---|---|---|---|
| 31 | **Admin login, dashboard, permissions** | `AdminController`, `AdminAuth`, `AdminPermission`, `AdminSameOriginActions` | Verified Working | Logged-out → redirect; staff without rights redirected (verified 9 pages); cross-site GET action blocked (verified on `brandactive`); login throttled; session regenerated. | Logout doesn't regenerate/invalidate the session. 306 `Route::any` routes; state changes on GET still rely on the same-origin safety net. | POST-only actions | Medium |
| 32 | **Product Management (add/edit/delete/list/import/export)** | `Backend/ProductController.php` (4,026 lines), `FastexcelController`, `backend/singleproducts/add-products.blade.php`, `backend/product/*` | Partially Working | List 200; edit page 200 for real products. | **Add Product with tier pricing turned on is refused** (form sends `tier_percentage[]`, server reads `tier_price[]`) (BUG-04). **No `validate()` call anywhere in ProductController** — prices can be negative (product 10 has sale price −2). Edit pages return **500** instead of 404 for unknown slugs. Delete removes the product row only — tier prices, cart lines and collection items are left behind (21 `cartorders` already point to deleted products). | Server validation | High |
| 33 | **Product Categories (parent / category / sub / child)** | `ParentcategoryController`, `CategoryController`, `SubcategoryController`, `ChildsubcategoryController`, `FilterController` | Partially Working | List and edit pages 200 with real records. | Edit pages 500 for unknown slug. Deletes via GET (`deletecategory/{id}`…). | — | Medium |
| 34 | **Tier Pricing (global template)** | `ProductController@tierPricing/addTierPricing/...`, table `tier_pricing` (4 rows), `backend/product/tier_pricing.blade.php` | Partially Working | Page 200. | Second pricing table (`tier_pricing`, percentages) separate from the real one (`product_tier_prices`). Only used to pre-fill the Add Product form, where the "percentage + cost price" maths adds a percentage number to a price. No validation. | One pricing model | High |
| 35 | **Product attributes: Brands, New Brands, Colors, Branding Options, Occasions, Preferences, Exhibition Events** | `BrandController`, `NewBrandController`, `ColorController`, `BrandingOptionController`, `OccasionController`, `PreferenceController`, `ExhibitionEventController` | Partially Working | List pages 200. | Two brand systems (`brands` and `new_brands`). Branding Options image column exists but its migration is recorded as not run (F-1). | — | Low |
| 36 | **CMS / settings: Header, Footer, SEO, Homepage, CMS, Policies, About, Contact, Sliders, Banners, Homepage sections, Flipbook, Portfolio, Coupons, Shipping, Weight shipping, Subscribers, Reviews, B2B enquiry, In-stock notifier, Analytics, Store users** | `GeneralSettings`, `CommonController`, `SliderController`, `CategorybannerController`, `HomepagesettingController`, `FlipbookController`, `PortfolioController`, `CouponController`, `ShippingController`, `SubscriberController`, `ReviewController`, `B2BenquiryController`, `Instock_notifierController`, `AnalyticsController`, `UsersController@storeuser` | Partially Working | ~80 admin view pages 200 as superadmin. | Edit pages for sliders/coupons/flipbook/portfolio return 500 when the record doesn't exist. Many settings belong to the old theme and don't affect the new storefront (e.g. footer). Coupons/shipping are for the old checkout, which is disabled (`checkout` redirects to `/cart`). | — | Low |
| 37 | **Reports (admin)** | `Backend/ReportController.php`, `Services/OrderReports.php` | Verified Working (GET) | Page and CSV export 200. | In-memory filtering (FX-43). | — | Low |
| 38 | **Email Notifications** | `app/Mail/*` (6 classes), `resources/views/mail/*`, `emails/*`, `OrderWorkflow::notify/notifyStaff` | Unable to Verify | Quote, contact, order-update and reset emails wrapped in try/catch (code). | Old agency names/social links remain in some templates (handoff FX-25, e.g. `mail/abandoned_cart_mail`, `mail/password`). `QUEUE_CONNECTION=sync`: emails are sent during the request. `MAIL_FROM_ADDRESS` empty in `.env.example`. | Real SMTP | Medium |

Also present and lightly checked: MidOcean product import console command (`app/Console/Commands/Midocean.php`, `Mainproduct.php`; needs API key — Unable to Verify), crop-image upload (`CropImageController`), CORS config.

---

## C. Bug and Error Report

Evidence key: **HTTP** = reproduced with a real request; **LOG** = in `laravel.log`; **DB** = confirmed by schema/data query; **CODE** = by reading the code (not executed because it would write data).

| ID | Issue | File | Function / method | Exact problem | Reason | Recommended solution | Evidence |
|---|---|---|---|---|---|---|---|
| BUG-01 | Compare search crashes once a product is selected | `app/Http/Controllers/Frontend/CompareController.php` (+ JS in `compare-products.blade.php` line 173) | `searchProducts()` | `GET /api/compare/search?search=a&excluded_ids=1,2` → **HTTP 500** "Argument 1 passed to `Builder::cleanBindings()` must be of the type array, string given". | JS sends `excluded_ids` as a comma string; PHP passes it straight to `whereNotIn()`. | Accept both: `$ids = is_array($x) ? $x : array_filter(explode(',', $x));` cast to int; validate `excluded_ids`. Escape `%`/`_` in the LIKE search. | HTTP + LOG (23× today before the audit) |
| BUG-02 | Adding/editing a corporate address fails | `app/Http/Controllers/Frontend/UsersController.php`, `app/Models/StoreuserAddress.php` | `validateAddress()`, `storeCorporateAddress()`, `updateSingleCorporateAddress()` | Inserts/updates `state` and `pincode`, which do not exist in `storeuser_addresses`. | Migration `2026_11_02_095728_add_state_pincode_to_storeuser_addresses_table` is pending. | Fix F-1 first (migrations are blocked), then run that migration. | DB + CODE |
| BUG-03 | Product page price doesn't match what the cart charges | `resources/views/web_frontend/product.blade.php` lines 294–305 and 636–647 | inline PHP block + JS `unitPriceFor()` | Server HTML shows `AED 0.00` (reads `$product->cost_price`, a column that doesn't exist); JS then always shows the **highest-quantity tier's price**, whatever the quantity. Cart uses `Product::priceForQuantity()` (correct tier). Example verified: product 2 shows **AED 400.00** at 50 pcs; the cart would charge **AED 12,930.00/unit** for 50 pcs (its 50–99 tier). | Regression in commit `c50e6a5`; `$startUnitPrice` is computed and never used. | Use `$startUnitPrice` in the HTML; restore the JS rule "tier where min ≤ qty ≤ max, else base price" (same as the PHP). Show the line total too. | HTTP + browser + DB |
| BUG-04 | Add Product with tier pricing is rejected | `resources/views/backend/singleproducts/add-products.blade.php` line 2251; `ProductController.php` | `invalidTierPrices()`, `syncTierPrices()`, `single_product_add()` | Add form fields are `tier_percentage[]`; the server only reads `tier_price[]`, so every tier counts as price 0 and the form returns "every quantity tier needs a price above 0 … Nothing was saved." | Add form still uses the old percentage design; edit form was updated to prices. | Use the same tier inputs (`tier_price[]`) on Add and Edit; drop or rethink the global percentage template. | CODE |
| BUG-05 | Migrations cannot run | `database/migrations/2026_11_03_064327_add_image_to_branding_options_table.php`, `2026_11_03_071045_create_branding_assets_table.php` | `up()` | See F-1: `php artisan migrate` would stop with "Duplicate column 'image'" / "Table 'branding_assets' already exists". | Migrations were run, failed half-way, and the tables were completed by hand. | See F-1. | DB + LOG |
| BUG-06 | Admin edit pages return 500 for unknown records | `BrandController@edit`, `ParentcategoryController@edit`, `CategoryController@edit`, `SubcategoryController@edit`, `ChildsubcategoryController@edit`, `ProductController@editproduct`, `SliderController@edit`, `CouponController@edit`, `FlipbookController@edit_flipbook`, `PortfolioController@edit_*` | `edit*()` | `->first()` returns null and the view reads properties → 500 (13 pages verified). | No not-found check. | `firstOrFail()` / `abort(404)`. | HTTP |
| BUG-07 | Header cart badge jumps after "Add to Quote" | `CartController.php` | `addItem()` | Returns `sum('quantity')` (pieces); the header composer and other endpoints count lines. | Two different meanings of "count". | Return `->count()` like the header. | CODE |
| BUG-08 | Offline products can be quoted | `CartController.php`, `CompareController.php` | `addItem()`, `addProductToCart()`, `addToCart()`, `sendEnquiry()` | No `is_active === 'online'` check (collections do check). | Missing rule. | Check in `addProductToCart()` and skip/flag in `sendEnquiry()`. | CODE |
| BUG-09 | Duplicate order numbers possible | `CartController@sendEnquiry`, `ApiController@addtocart`, table `orderids` | — | `orderids` is MyISAM with no unique index on `orderid`; `lockForUpdate()` has no effect on MyISAM; `addtocart` uses `orderByDesc('id')->first()+1` while `sendEnquiry` uses `max(orderid)+1`. | Engine + two allocation methods. | Convert `orderids` to InnoDB, add a unique index, use one allocator (or an auto-increment). `orders.orderid` is unique, so a clash would fail the request rather than corrupt data. | DB + CODE |
| BUG-10 | Compare page values not validated / not MOQ-aware | `CompareController.php` | `getProductDetails()` | `selected_quantity` unvalidated (0, negative or text accepted; total can be negative); default 500 ignores each product's MOQ; key `base_price` actually holds the first tier price. | — | Validate `selected_quantity` (integer ≥ 1), use `max(qty, moq)`, rename the field. | CODE |
| BUG-11 | Old registration crashes when mail fails | `UsersController.php` | `dostoreregister()` | No validation; `Mail::send` not in try/catch → 500 *after* the account is saved. | — | Validate; wrap mail; or retire the old page in favour of `/corporate/register`. | CODE |
| BUG-12 | `/store/myaccount` uses the dashboard view with the wrong data | `UsersController.php` | `myaccount()` | Passes `userdetails`, `items_array`, `footerlevel1`, `contact` to `web_frontend.myaccount`, which is built for `corporatePortal()` variables. | Old method left after the dashboard rebuild. | Redirect `/store/myaccount` to `/corporate-portal`. | HTTP (200, old data) + CODE |
| BUG-13 | Header search does nothing | `web_frontend/partials/header.blade.php` line 25 | — | Bare `<input>`. | Never wired. | Wrap in a GET form to an existing search route. | CODE |
| BUG-14 | Product page "Request a Sample" does nothing | `web_frontend/product.blade.php` line 311 | — | Button without handler. | — | Wire to an enquiry, or hide. | CODE |
| BUG-15 | Product delete leaves orphan rows | `ProductController.php` | `deletemyproduct()` | Deletes only `products`; `product_tier_prices`, `cart_items`, `collection_items` remain. 21 `cartorders` already reference missing products. | MyISAM, no FKs. | Soft-delete products (`is_active = offline`) instead of deleting, or clean related rows in a transaction. | DB + CODE |
| BUG-16 | Bad pricing data | table `product_tier_prices`, `products` | — | 8 tier rows ≤ 0 (products 4, 5 — ignored by code, but still wrong); product 2 tiers 12,930 / 100 / 100 / 400 (not descending, first tier absurd); product 10 `sprice = -2`, tier 3–4 below its MOQ 23; product 9 sprice 10 vs price 200. | No validation (BUG-04, SEC-10). | Owner to correct prices; add validation (min ≤ max, no overlap, price > 0, tiers ≥ MOQ). | DB |
| BUG-17 | `composer.lock` out of date | `composer.json` / `composer.lock` | — | `composer validate`: "The lock file is not up to date with the latest changes in composer.json". | `composer.json` edited without updating the lock. | Find the changed line (`git log -p composer.json`) and update just that package; do **not** run a blanket `composer update` (handoff warns it breaks on PHP 8.3). | Command |
| BUG-19 | Line subtotals lose their decimals | `CartController.php`; table `cartorders` | `sendEnquiry()` | Saves `round(price × quantity, 2)` into `cartorders.subtotal`, which is **INT(11)** — e.g. 45.50 × 3 = 136.50 is stored as 137 (or 136, depending on SQL mode). `orders.total` (DECIMAL) is correct, so the workflow total is fine; Store Enquiries/report lines that read `subtotal` can be off by up to 0.50 per line. | Wrong column type. | Change `cartorders.subtotal` to `DECIMAL(12,2)` (F-10). | DB + CODE |
| BUG-18 | Footer missing on the new storefront | `web_frontend/partials/footer.blade.php` | — | Commented out; `href="#"` links. | — | Build footer (needs owner content). | CODE |

---

## D. Security Report

No secrets are reproduced in this report. Severity: **Critical** (exploitable now, serious impact) → **Low**.

| ID | Severity | Vulnerability | Affected file(s) | Details | Fix |
|---|---|---|---|---|---|
| SEC-01 | **Critical** | Unauthenticated account takeover through the legacy `addtocart` API | `routes/web.php` line 302; `app/Http/Controllers/Backend/ApiController.php` `addtocart()`; `VerifyCsrfToken::$except` | `POST /addtocart` is public, CSRF-exempt and unvalidated. With `userinfo[updateaddress]` set, it runs `UPDATE storeusers SET email=…, fname=…, mobile=…, address=… WHERE id = <userid from the request>`. Anyone can change any customer's email to their own, then use "Forgot password" to take the account and see its orders, addresses and files. (Confirmed by code; not executed.) | Remove the route (the new storefront doesn't use it; only `layouts/frontapp.blade.php` does), or require login and use the session user only. Remove `addtocart` from the CSRF exceptions. |
| SEC-02 | **Critical** (if PayTabs is live) | Payment amount set by the browser and never checked | `ApiController::addtocart()`, `PaytabController::callback()`, `app/helpers.php` `paytabs()` | `cart_amount` = sum of `price × quantity` from the request; the callback marks the order "Completed" on `response_status = A` without comparing the paid amount to a server-calculated total. | Price from the database; compare `cart_amount`/`tran_total` in the callback and return-URL check. |
| SEC-03 | High | Other unauthenticated write endpoints without validation | `ApiController@abandancart`, `stocknotify`, `b2benquiry`, `addsubscriber`; `addtocart` also burns order numbers | Spam rows, arbitrary `userid` on `tmporders`, 500s on missing keys. Some are throttled (10/min), `addtocart`/`abandancart` are not. | Remove unused endpoints (handoff FX-09) or validate + throttle + CSRF. |
| SEC-04 | High | Customer files publicly downloadable | `Frontend/BrandingAssetController@store/update`; also order files in `storage/app/public/order-files/` (handoff FX-34) | Stored in the public disk with predictable names (`<unix time>_<original name>`); a visitor got **HTTP 200** for an existing asset without logging in. The controller's ownership check only protects the `/branding-assets/{id}/download` route. | Store on a private disk (`storage/app/private`) and serve only via the checked download route; use random names. |
| SEC-05 | High | Stored XSS via SVG upload | `BrandingAssetController` (`mimes:...svg`), `RejectDangerousUploads` allow-lists `svg` | An SVG with `<script>` uploaded by a customer is served from the site's own origin under `/storage/...`; whoever opens it (e.g. staff) runs the script. (OrderWorkflow refuses SVGs with script; branding assets do not.) | Drop SVG, or sanitise it, or serve with `Content-Disposition: attachment` from a private disk. |
| SEC-06 | Medium | Session fixation on registration | `UsersController@doCorporateRegister`, `storeCartRegister` | Logs the user in without `session()->regenerate()`. | Regenerate on every login/registration; on logout `invalidate()` + `regenerateToken()`. |
| SEC-07 | Medium | Order access by unverified email | `CustomerOrderController::ownOrder()`, `HomepageController@orders` | Orders are shown if `customer_email` equals the account email, and emails are never verified. 2 orders have no `storeuser_id` (legacy) — someone registering that email would see them. | Verify email addresses, or match on `storeuser_id` only. |
| SEC-08 | Medium | State-changing GET routes | 306 `Route::any` routes (e.g. `deletecategory/{id}`, `deletewishlist/{id}`, `clearcart`, `deleteproduct`, `incrementquantity`, logout routes) | Admin side is protected by `AdminSameOriginActions` (verified), storefront side is not. | Move to POST/DELETE forms (handoff FX-08). |
| SEC-09 | Medium | Debug mode / environment | `.env` | `APP_DEBUG=true`: the 500 page is a full Ignition page (~640 KB) with code and environment details. DB `root` with no password. Fine on a local machine — must not reach the server. | Production `.env`: `APP_DEBUG=false`, `APP_ENV=production`, dedicated DB user with a password. |
| SEC-10 | Medium | No server-side validation in Product admin | `ProductController.php` (0 `validate()` calls in 4,026 lines) | Negative/empty prices, broken tiers, any text in numeric fields. Admin-only, so not public, but corrupts pricing (see BUG-16). | Form Request for add/update. |
| SEC-11 | Medium | Raw HTML output of product text | `web_frontend/product.blade.php` lines 218, 400, 445; `frontend/product.blade.php`; `backend/product/edit-products.blade.php` (inside `<textarea>`) | `{!! $product->description !!}` etc. Admin-entered rich text; risk only if an admin account is compromised or MidOcean import data is malicious. Compare page JS also inserts product names via `innerHTML` without escaping. | Sanitise rich text on save (HTML Purifier); escape in JS. |
| SEC-12 | Medium | Secrets in git history | git history (per `docs/PROJECT-HANDOFF.md` / FX-04; one commit in history touches `server_key`) | PayTabs/MidOcean keys were committed in the past; current tracked files contain none (checked). | Rotate the keys (owner/server task). |
| SEC-13 | Low | Unsupported runtime | PHP 7.4, Laravel 8 | No security patches. | Plan an upgrade (handoff FX-11). |
| SEC-14 | Low | Password policy / reset | `UsersController` | Minimum 6 characters; sessions not revoked after reset. | Raise to 8+; log out other sessions on reset. |
| SEC-15 | Low | Corporate wishlist mock open to the public | `routes/web.php` line 218 | No sensitive data (static), but it's a portal page outside the login. | Add `corporate.auth` or remove. |
| SEC-16 | Low | CORS `*` on `api/*` | `config/cors.php` | Now covers `api/compare/*`; `supports_credentials=false`, so cookies are not sent — low risk. `HandleCors` is registered twice in `Kernel::$middleware`. | Restrict origins; remove the duplicate. |

**Checked and found OK:** SQL injection — all raw SQL uses bindings or fixed strings (no user input concatenated; `ApiController` line 1486 uses a whitelisted column name, to be confirmed if that endpoint is kept). CSRF on the new storefront — POST/PUT/DELETE routes use the token. Passwords — bcrypt for admins and customers, 0 plain-text rows. Customer data isolation on orders, collections, addresses, branding assets, enquiries — verified 404 for other customers. Admin permission flags — verified. Mass assignment — models use `$fillable`; the three `$guarded = ['id']` models are only written by server code. Upload filter blocks script extensions; `.htaccess` blocks script execution in upload folders (Apache only).

---

## E. Performance Report

| ID | Issue | Where | Impact | Recommendation |
|---|---|---|---|---|
| PERF-01 | One SQL query per product per price lookup (N+1) | `Product::priceForQuantity()` called in loops: `CartController::refreshPrices`, `UsersController::allCollections/viewCollection`, `CompareController`, product page | Small today (9 products), grows with cart/collection size | Eager-load tier rows (`hasMany` relation + `with('tierPrices')`) and pick the tier in PHP. |
| PERF-02 | Load-all-then-paginate in PHP | `Backend/OrderController@index` (all `cartorders`), `abandoned_order` (all `cart_items`), `AdminController@search_monthly/yearly`, `OrderReports::orders()`, `Collection::macro('paginate')` | Fine at 36 cart orders; slow at thousands | SQL `paginate()` on the `orders` table with filters. |
| PERF-03 | `ORDER BY RAND()` | `StoreCategoryController`, `StoreBrandController` | Full table scan per page | Order by ranking/id, or random within a cached ID list. |
| PERF-04 | Missing indexes | `orderids.orderid` (no index, no unique), `storeusers.email` (not unique), `collection_items(product_id)`, `product_tier_prices(product_id,min_qty)`, `wishlists(userid)`, `branding_assets(user_id)` | Lookups by these columns scan the table | Add indexes (unique where it is a business rule). |
| PERF-05 | `imageUrl()` calls `is_file()` up to 6× per product per render | `Product::imageUrl()`, `Cartorder::productImageUrl()` | Disk I/O on list pages | Store the resolved path, or cache it. |
| PERF-06 | Unused large assets | `public/web_frontend/assets/images/dashboard-reference.png` (2.0 MB, referenced nowhere); `public/js/app.js` (1.5 MB) and `public/assets/js/main.min.js` (0.98 MB) not referenced by any view; `public/assets/less` (18 MB of LESS sources) | Repository/deploy size | Remove after confirming. |
| PERF-07 | Large images | `membership-hero.png` (1.9 MB), `Thoughtful-Gifts.png` (1.7 MB) used in CSS/pages | Slow first load on mobile | Convert to WebP/AVIF, resize, lazy-load. |
| PERF-08 | Synchronous email | `QUEUE_CONNECTION=sync` | Quote submit and workflow actions wait for SMTP | Database queue + cron worker (jobs table exists). |
| PERF-09 | Admin permission lookup per request | `AdminPermission::allowed()` static cache per request — OK | — | No action. |
| PERF-10 | Duplicate global CORS middleware | `app/Http/Kernel.php` | Runs twice per request | Remove one. |

Page timings on the local server were 0.16–0.29 s for every page tested (small data set; not representative of production load).

---

## F. Database Report

Read-only analysis of the live local database plus migrations and models.

| ID | Issue | Details | Recommendation |
|---|---|---|---|
| F-1 | **Migration history out of sync — `php artisan migrate` is blocked** | 3 migrations pending: `2026_11_02_095728_add_state_pincode_to_storeuser_addresses_table` (columns **missing** → BUG-02), `2026_11_03_064327_add_image_to_branding_options_table` (column `image` **already exists**), `2026_11_03_071045_create_branding_assets_table` (table **already exists**, 2 rows, **without** its foreign key). Log shows the FK failed: `branding_assets.user_id` is BIGINT UNSIGNED but `storeusers.id` is INT(11) signed. Running `migrate` now would add the address columns, then stop on "Duplicate column 'image'". The same will happen on the server deploy. | Make the two 3-Nov migrations idempotent (`if (!Schema::hasColumn/hasTable)`), make `branding_assets.user_id` the same type as `storeusers.id` (signed `INT`) or leave the FK out, then run the migrations on a database backup first. The `down()` of the branding-options migration is empty — add `dropColumn('image')`. |
| F-2 | Mixed storage engines | 35 of 79 tables are MyISAM: `products`, `orderids`, `brands`, `categories`, `subcategories`, `childsubcategories`, `password_resets`, `branding_options`, `occasions`, `preferences`, `shippings`, … | Transactions don't protect them (e.g. quote submit, order numbers); no FKs possible. Convert to InnoDB after a backup. |
| F-3 | Almost no foreign keys | Only `cartorders.userid → storeusers.id`. `orders.storeuser_id`, `order_step_states.order_id`, `collection_items.*`, `cart_items.*`, `product_tier_prices.product_id`, `storeuser_addresses.user_id`, `branding_assets.user_id` are unenforced. | Add FKs once engines and ID types match. |
| F-4 | ID type mismatch | `storeusers.id`, `products.id`, `admins.id`, `cartorders.id` = INT(11) signed; new tables use BIGINT UNSIGNED (`collections`, `storeuser_addresses`, `branding_assets.user_id`) or INT UNSIGNED (`orders.id`). | Pick one convention; it's why FKs fail. |
| F-5 | Orphaned rows | 21 `cartorders` → missing products; 2 `orders` → no store user (legacy). 0 orphans in step states, collection items, cart items, tier prices, branding assets, enquiries. | Keep products instead of deleting (soft delete). |
| F-6 | Duplicate/overlapping tables | Pricing: `product_tier_prices` (used) vs `tier_pricing` (template). Workflow: `orders`/`order_step_states`/`order_events` (used) vs `order_processing_workflows`/`order_workflow_steps` (legacy). Brands: `brands` vs `new_brands` (0 rows). Products: `products` (9) vs `products1` (0), `newproducts` (0), `myproducts` (3,596, MyISAM), `midocean_products` (1,899). Orders: `cartorders` vs `tmporders` (old). Also `users` (Laravel default, 1 row) unused next to `storeusers`; `old_portfolios`, `frontend_settings`, `discount_coupon` vs `coupons`. | Owner decision (handoff D6), then drop with backups. |
| F-7 | Missing unique constraints | `storeusers.email` (app checks, DB doesn't); `orderids.orderid`; `collections(user_id,name)`; `collection_items(collection_id,product_id)`; `wishlists(userid,productid)`. | Add unique indexes after de-duplicating (0 duplicate emails today). |
| F-8 | Data-quality issues | See BUG-16 (tier prices ≤ 0, inconsistent tiers, negative sale price). `cartorders.orderstatus` mixes old words and new step labels. Test products online ("Vishakha Upadhyay", "…aaasdf"). | Clean with owner. |
| F-9 | Seeders | `DatabaseSeeder`, `ProductSampleSeeder` only; no factories for main models; no test DB. | Needed for automated tests. |
| F-10 | Money and quantities stored as text or integers | Verified types: `products.price`, `products.sprice`, `products.min_order_quantity`, `cartorders.price`, `cartorders.ordertotal` = **VARCHAR(250)**; `cartorders.subtotal` = **INT(11)** (decimals cut off, BUG-19); `products.base_price` DECIMAL(10,2); `cart_items.price`, `orders.total`, `product_tier_prices.price` DECIMAL(12,2). | Convert money columns to `DECIMAL(12,2)` and quantities to `INT` after cleaning the data. |

Relationships in models: `Order` ↔ `OrderStepState`/`OrderEvent`/items, `Collection` ↔ `CollectionItem` ↔ `Product`, `BrandingAsset` → `Storeuser` exist. `Product` has **no relationship** to its tier prices (queried with `DB::table` in four places), `Cartorder`/`CartItem` have no `product()` relation.

---

## G. UI/UX Report

| ID | Page / area | Problem | Evidence |
|---|---|---|---|
| UI-01 | Product page | Price block shows `AED 0.00` until JS runs; afterwards shows the cheapest tier for every quantity; no line total shown. | HTTP + browser (BUG-03) |
| UI-02 | Compare Products | 593px wide at a 375px phone viewport (sideways scroll); quantity/price ranges hard-coded to 500–999; search breaks after first pick. | Browser (iframe at 375px) + HTTP |
| UI-03 | Contact Us (old theme) | 7 broken images; 427px wide at phone width; old agency branding. | Browser |
| UI-04 | Annual Membership | 367px in a 360px viewport (slight sideways scroll); no form. | Browser |
| UI-05 | Two visual designs | New storefront (`web_frontend`) vs old theme (`frontend` + `layouts/frontapp`) for category, brand, search, about, contact, policies, old login/register. Users switch look when navigating. | HTTP |
| UI-06 | Header | Search does nothing; 3 menu links go to `#`. | CODE |
| UI-07 | Footer | None on the new storefront. | CODE |
| UI-08 | Images | No product images on this machine (all 9 products) and 12 old-theme images 404; product page shows a broken image placeholder. Old JS templates request `${product.thumbnail}` and `{3}` literally (broken template strings). | HTTP link check |
| UI-09 | Corporate sidebar | One `href="#"` item; Wishlist page is a static mock-up. | CODE |
| UI-10 | Header width | Handoff FX-40: header ~17px wider than a 1440px screen. In this audit's 1400px check, homepage `scrollWidth` was 1385 within a 1400 frame (no overflow observed at 1400px); not re-tested at 1440px. | Browser (partial) |
| UI-11 | Dashboard | Some panels still demo content (Switch Company toast, fixed "ABC" branding files) per handoff FX-38; not re-verified visually. | Unable to verify fully |
| UI-12 | Logout | Done by a GET link (red icon); no confirmation; session not fully cleared. | CODE |

Checked OK: homepage, cart, track-order, budget planner, corporate login fit 375px with no sideways scroll; no JavaScript exceptions in the browser console (only 404s for missing images).

---

## H. Missing Functionality

| Feature | Current state |
|---|---|
| Working site search from the header | Not implemented (input only) |
| Storefront footer | Commented out |
| Corporate wishlist | Static mock-up, public |
| Annual membership lead form | No form |
| Request a Sample | Button without action |
| Email verification on registration | None |
| Change password / change email in Account Settings | Not present |
| Order step due dates / overdue flags | Not implemented (FX-13) |
| Store Enquiries search & filters | Not implemented (FX-14) |
| Link between PayTabs payments and the 10-step workflow | Not implemented (payment steps are manual) |
| Server-side validation in product admin | Not implemented |
| Tier price consistency rules (no overlap, min ≤ max, ≥ MOQ, descending) | Not implemented |
| Private storage for customer files | Not implemented |
| New-design category / brand / search / static pages | Still old theme |
| Automated tests | None (`tests/` folder missing) |
| Queue worker for email | `sync` driver |

---

## I. Priority-Based Action Plan

Nothing below has been changed. Waiting for your approval.

### Critical
1. **SEC-01 / SEC-02** — Remove (or lock down) `POST /addtocart` and the other legacy Angular write endpoints; remove them from the CSRF exceptions. Add amount verification to the PayTabs callback.
2. **F-1 / BUG-05** — Repair the two 3-Nov migrations so `migrate` can run; then **BUG-02** (address add/edit) is fixed by running the pending address migration. Test on a DB backup first.
3. **BUG-03** — Product page price: use the server's tier rule in HTML and JS.
4. **BUG-01** — Compare search crash (`excluded_ids`).

### High
5. **SEC-04 / SEC-05** — Move branding-asset (and order) files to private storage; block or sanitise SVG.
6. **BUG-04** — Add Product tier inputs (`tier_price[]`), and **SEC-10** product form validation (prices > 0, tier rules).
7. **BUG-09 / F-2** — `orderids` to InnoDB + unique index + one allocator.
8. **SEC-06 / SEC-07** — Regenerate session on registration; full logout; email verification (or `storeuser_id`-only order access).
9. **BUG-07 / BUG-08 / BUG-19** — Cart badge count; block offline products in cart/compare/quote; fix the `cartorders.subtotal` column type.
10. **BUG-16** — Owner to correct product 2/4/5/9/10 prices; remove test products.
11. **BUG-13** — Header search.

### Medium
12. **BUG-06** — `firstOrFail()` on admin edit pages.
13. **SEC-08** — Convert GET actions to POST (storefront first: `clearcart`, `deleteproduct`, `deletewishlist`, logout).
14. **PERF-01 / PERF-02 / PERF-04** — eager-load tiers, SQL pagination, indexes.
15. **F-3 / F-4** — Align ID types, add foreign keys (after InnoDB conversion).
16. **UI-02 / UI-03 / UI-04** — Mobile overflow fixes; **BUG-18** footer; **H** membership form; corporate wishlist (or hide it, SEC-15).
17. **BUG-11 / BUG-12** — Retire old store register/myaccount pages (redirect to corporate ones).
18. **SEC-09 / SEC-12** — Production `.env` hardening and key rotation (server task).
19. Email templates: remove old agency branding (FX-25); set up a queue worker.

### Low
20. **BUG-17** — Sync `composer.lock` carefully.
21. **PERF-03 / PERF-05 / PERF-06 / PERF-07 / PERF-10** — RAND(), image checks, unused/oversized assets, duplicate CORS middleware.
22. **F-6** — Drop duplicate/legacy tables after owner sign-off; remove legacy workflow code (FX-15).
23. Add PHPUnit feature tests for quote flow, pricing, workflow, permissions, isolation.
24. **SEC-13** — Plan PHP 8.x / Laravel upgrade.

---

## J. Overall Project Health Score: **55 %**

Each area was scored from what was actually observed, then weighted by business importance.

| Area | Weight | Score | Why |
|---|---|---|---|
| Security | 25 % | 45 | Strong admin lock-down, bcrypt, verified customer isolation and CSRF on the new storefront — but one public endpoint allows account takeover and payment tampering, customer files are public, SVG XSS, debug on locally. |
| Storefront & pricing | 20 % | 55 | Pages load and the cart prices correctly, but the product page shows wrong prices, compare search crashes, Add Product with tiers is broken, and price data is inconsistent. |
| Corporate portal & data isolation | 15 % | 70 | Login/portal/orders/collections/reports/branding load and isolation holds; address saving is broken by the schema; wishlist is a public mock-up. |
| Order workflow | 10 % | 75 | Clean service design, pages load, permissions OK; actions not exercised in this audit; no due dates and no payment link. |
| Database & migrations | 10 % | 40 | Migrations blocked, 35 MyISAM tables, 1 FK, mismatched ID types, orphan rows, duplicate tables. |
| Admin panel | 10 % | 75 | ~100 admin pages load, permissions verified; edit pages 500 on missing records; no product validation. |
| Code quality, tests, performance | 10 % | 35 | No automated tests, 4,000-line controllers, unsupported PHP/Laravel, lock file out of date, N+1 and load-all queries (not yet a problem at current data size). |

Weighted total: 0.25×45 + 0.20×55 + 0.15×70 + 0.10×75 + 0.10×40 + 0.10×75 + 0.10×35 = **55.25 → 55 %**.

The score is limited by what could be tested without writing data: all write flows were judged from code and schema. A full end-to-end test on a copy of the database would make it more precise.

---

## Appendix — Notes on this audit's own footprint

- `storage/logs/laravel.log` gained entries from this audit's test requests: the compare-search 500 (1 request, deliberate reproduction) and the admin edit pages requested with a placeholder ID that does not exist (13 requests, which revealed BUG-06). No other log changes were caused by the audit.
- Three temporary session files were created for the logged-in checks and deleted afterwards.
- No migrations, seeders, `db`, `DROP`, `TRUNCATE` or reset commands were run. No emails were sent. No payments or external API calls were made.
