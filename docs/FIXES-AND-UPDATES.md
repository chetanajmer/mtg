# More Than Giftz — Fixes, Changes and Updates (master list)

| | |
|---|---|
| Purpose | One list of everything that still needs fixing, changing or updating. Work is picked from here. |
| Companions | `docs/PRD.md` (what the product must do) · `docs/PROJECT-HANDOFF.md` (how work is done) |
| Created | 1 Nov 2026 (updated 2 Nov 2026) |
| Status | FX-01, FX-02 and FX-03 done (2 Nov); other FX-01 to FX-32 not started. FX-33 to FX-45 are follow-ups added after finished work (customer approvals 1 Nov, negative tier prices and dashboard cards 2 Nov); those fixes themselves are done, the follow-ups are open. Reports (customer + admin) done 2 Nov; FX-42, FX-43 found then. |

**How to read it**
- **ID** — `FX-nn`, used in the audit log REF column when the item is worked on. "PRD" shows the matching PRD id, if any.
- **Priority** — 🔴 Critical (data leak / broken core flow) · 🟠 High · 🟡 Medium · ⚪ Low.
- **Who** — `Code` = can be done in the codebase · `Owner` = needs a decision or information from the owner · `Server` = must be done on cPanel / hosting.
- **NEW** = found on 1 Nov 2026 during the project review; not in the PRD or handoff before.
- When an item is finished: mark it ✅ here, log CHANGE + TEST in the audit log, and update the PRD status.

---

## 1. Critical — data going to the wrong place

| ID | PRD | Priority | Who | Item | Where | Why it matters |
|---|---|---|---|---|---|---|
| ✅ FX-01 NEW | S20 | 🔴 | Code + Owner | **Contact form sends customer messages to the old agency.** The staff copy goes to `richa@silverpixelz.com`, the "from" address is that same agency address, and the sender name is "Meem Industrial". | `app/Http/Controllers/Frontend/HomepageController.php` `contactform()` (lines ~665–699), templates `mail/usercontactmail`, `mail/admincontactmail` | Customer names, phone numbers and messages from `/contactus` reach a third party, and MTG staff never see them. Fix: send to the staff address (see FX-03), use `MAIL_FROM_ADDRESS`, say "More Than Giftz". | **Done 2 Nov:** found the form actually crashed (old templates used variables the code never passed), so messages were lost rather than sent. Now: validated form, staff copy to `staff_email()` with Reply-To the customer, short MTG confirmation to the customer (does not repeat their text), clear error if sending fails; old SilverGiftz templates deleted. |
| ✅ FX-02 NEW | — | 🔴 | Code + Owner | **Old Angular API enquiry/order endpoints email outside people.** `ApiController::enquiry()` emails every enquiry to `Murtaza.silverpixelz@gmail.com` and signs as "SilverGiftz"; `search_or_update()` sends order emails to `richa@silverpixelz.com`. | `app/Http/Controllers/Backend/ApiController.php` lines ~1066–1073 and ~1543–1562; routes `enquiry`, `search_or_update` in `routes/web.php` | Customer data goes to a personal Gmail account. These routes are still public. They also create `cartorders` with `rand(1111,9999)` order numbers and **no `orders` row**, so the enquiries never appear in the 10-step workflow. Decide: remove the routes (the Angular storefront is out of scope) or point them at the new flow. | **Done 2 Nov:** routes `enquiry`, `search_or_update`, `contact` removed (no caller in this codebase), with their CSRF exceptions, methods, the `sendmail_to_admin/customer` helpers and the two SilverGiftz enquiry templates. |
| ✅ FX-03 NEW | S9 | 🔴 | Code | **The staff quote email address is lost after deploy.** `env('QUOTE_REQUEST_EMAIL')` is read directly in code. The deploy runs `php artisan config:cache`, and after that `env()` returns nothing. | `CartController.php` line 245, `app/Services/OrderWorkflow.php` line 385 | Staff quote emails and customer-response emails fall back to the header email or go to nobody. That breaks the PRD success measure "100% of quotes reach staff". Fix: add the setting to a config file (e.g. `config/mail.php` → `quote_request_email`) and read it with `config()`. | **Done 2 Nov:** `config/mail.php` `staff_address` + helper `staff_email()` (falls back to Header Settings email), used by quotes, order responses and the contact form. |

## 2. Security and platform

| ID | PRD | Priority | Who | Item | Where / notes |
|---|---|---|---|---|---|
| FX-04 | P1 | 🔴 | Server/Owner | Rotate the PayTabs and MidOcean keys (the old ones are in git history). | New keys go in the server `.env` only. |
| FX-05 | P2 | 🔴 | Server | `APP_DEBUG=false`, `APP_ENV=production` on the live site. | Live currently shows full error pages. |
| FX-06 | P6/P7 | 🔴 | Server | Deploy the current code and run the migrations on live (live is missing `cart_items` etc.). | Follow `mtg-updated-files/WHERE-FILES-GO.txt`. Do FX-01–FX-03 first so the deploy doesn't ship them. |
| FX-07 | P8 | 🟠 | Server | Real SMTP on the server; set `MAIL_FROM_ADDRESS` (currently `null` in `.env.example`); optional queue cron. | Local mail goes to `mailhog`, which doesn't exist; that's expected. |
| FX-08 | A12 | 🟠 | Code | Change admin GET "action" links (delete, toggle, import, send mail) to POST forms. | ~304 `Route::any` routes in `routes/web.php`. Do it module by module with crawl tests; `AdminSameOriginActions` stays as a safety net. |
| FX-09 NEW | — | 🟡 | Code | Old Angular JSON POST endpoints are still public and unused by the new storefront (`addtocart`, `cartdata`, `abandancart`, `getproductdetails`, `searchproduct`, `search_or_update`, `enquiry`, `contact`, `stocknotify`, `b2benquiry`, `paytab_returnurl`). | Confirm nothing uses them (grep `public/` JS), then remove them or lock them down. Linked to FX-02. | Update 2 Nov: `enquiry`, `search_or_update`, `contact` already removed (FX-02). Note: `addtocart` (used by the old theme) reserves a new order number in `orderids` before validating its input. |
| FX-10 NEW | S16 | 🟡 | Code | `/corporate-portal/wishlist` is a plain `Route::view` with **no login check**; anyone can open it. | `routes/web.php` line 173. Will be fixed by FX-20. |
| FX-11 | P9 | ⚪ | Code | Upgrade PHP 7.4 → 8.x and Laravel 8 → current (69 package security advisories). | A separate project; needs its own backup and plan. |
| FX-12 | P11 | ⚪ | Owner → Code | Remove unused packages (`intervention/image`, `spatie/laravel-analytics`, 2nd PayTabs package) and leftover tables (`products1`, `newproducts`, `old_portfolios`, `frontend_settings`). | Needs owner sign-off before dropping anything. |

## 3. Order workflow

| ID | PRD | Priority | Who | Item | Where / notes |
|---|---|---|---|---|---|
| FX-13 | W9 | 🟠 | Code | Step due dates / SLA: target days per step, "overdue" badge on the staff order page and the list. | Add `days` per step in `config/order_steps.php`; use `orders.step_started_at`. |
| FX-14 | W10 | 🟠 | Code | Store Enquiries filters (waiting for customer, payment pending, overdue, completed) and search (order #, customer, company, email). | `Backend/OrderController::index`. NEW detail: it currently loads **every** `cartorders` row and splits it into pages in PHP; rebuild it on the `orders` table with real SQL pagination. |
| FX-15 | W11 | 🟡 | Owner → Code | Remove the legacy 5-step "Order Processing", the old `/order-tracking` pages and the `order_workflow_steps` / `order_processing_workflows` tables. | Owner OK first; drop-table migration last. Grep views for route names before removing (see handoff §8). |

## 4. Storefront (customer-facing)

| ID | PRD | Priority | Who | Item | Where / notes |
|---|---|---|---|---|---|
| FX-16 NEW | S20 | 🟠 | Code + Owner | **The new storefront has no footer.** The whole footer is inside an HTML comment, and the links inside it are all `href="#"` with "© 2024". | `resources/views/web_frontend/partials/footer.blade.php`. Build a real footer (links, contact, policies, social accounts once the owner provides them). |
| FX-17 NEW | — | 🟠 | Code | **The header search box does nothing.** It's a bare `<input>` with no form and no JS. | `web_frontend/partials/header.blade.php` line 25. Connect it to the existing search (`searchbar` / `search_bar/{q}` routes) or a new search page. |
| FX-18 | S2 | 🟡 | Owner → Code | Header links **Best Sellers**, **Sustainable** and **Branding** go to `#`. | `partials/header.blade.php` lines 12, 16, 51. Owner decides the targets or removes them. |
| FX-19 | S19 | 🟡 | Code | Redesign the old-theme pages in the new look: category, subcategory, brand, list views, about, contact, privacy/return/shipping policies, search, old login/register/myaccount/wishlist, thank-you. | `resources/views/frontend/*` + `layouts/frontapp.blade.php`. Keep routes and controllers. The old layout also says "Design & Develop by SilverPixelz" (line 316). | Update 3 Nov: **About Us rebuilt** in the new design (`web_frontend/about.blade.php`); the rest of the list remains.
| FX-20 | S16 | 🟡 | Code | Corporate wishlist page is a static mock-up ("ABC Technologies", "Diwali 2025", fake counts). | `web_frontend/corporate-wishlist.blade.php`. Can reuse `collections`; add the login check (FX-10). |
| FX-21 | S17 | 🟡 | Code + Owner | The annual corporate membership page has no form, so it captures no leads. | `web_frontend/annual-corporate-membership.blade.php`. Add an enquiry form → store + email staff. Owner: what fields/plans. |
| FX-22 NEW | — | 🟡 | Code | Other dead `href="#"` links in the new pages. | `myaccount.blade.php` line 34, `enquiry-success.blade.php` line 55, `collections-list.blade.php` lines 74 and 77, `corporate-wishlist.blade.php` lines 301 and 352. The corporate sidebar also links to sections that don't exist (`#approvals` with a fake "3", `#employees`, `#reports`, `#branding`). |
| FX-23 NEW | — | ⚪ | Owner → Code | The phone number `+971 55 786 3450` is hard-coded in the header (and the old footer); the email `info@morethangiftz.com` is hard-coded. | Confirm they're correct; ideally read them from admin Header Settings. |
| FX-24 | S11 | ⏸ | Owner | Google login: the owner of the Google Cloud project must add the redirect URIs and share the keys. | Code works. Dead duplicate code in `GeneralSettings::redirectToGoogle/GoogleCallback` (not routed, uses old agency email and "SevenWonders") can be deleted. |

## 5. Branding leftovers (old agencies)

Customer-facing copy must say **More Than Giftz**. Found in 13 files (NEW):

| ID | Priority | Who | Where | What |
|---|---|---|---|---|
| FX-25 NEW | 🟠 | Code | `resources/views/mail/enquiry_user_email`, `enquiry_admin_email`, `usercontactmail`, `admincontactmail`, `password`, `abandoned_cart_mail` | "SilverGiftz"/"Meem" names and **SilverPixelz Facebook/Instagram/LinkedIn links** in emails. `abandoned_cart_mail` is still used by `App\Mail\Abandonedcartmail` and `Cartemail`. |
| FX-26 NEW | 🟡 | Code | `resources/views/layouts/frontapp.blade.php` (lines 33, 60, 72, 316, 323, 831, 834), `frontend/contactus.blade.php` (32, 47) | Old brand names/links on old-theme pages (goes away with FX-19 if those pages are rebuilt). |
| FX-27 NEW | ⚪ | Code | `Backend/ProductController.php` (bulk image names `-silvergiftz-silverpixelz-`), `backend/dashboard/dashboard.blade.php` line 206, `backend/b2benquiry/b2benquiry.blade.php` line 60, `backend/product/products.blade.php` (checks for `appliances@gmail.com` / `accessories@gmail.com`) | Admin-side leftovers; low risk but confusing. |

## 6. Admin panel

| ID | PRD | Priority | Who | Item |
|---|---|---|---|---|
| FX-28 | A10 | ⏸ | Owner | Bulk image upload by model number was removed (its table never existed). Rebuild only if wanted. |
| FX-29 | A11 | ⏸ | Owner | Reward/referral section was removed (tables never existed). Rebuild only if wanted. |
| FX-30 | — | ⚪ | Owner → Code | Clean up test-looking catalogue products (e.g. "Vishakha Upadhyay", "A5 Notebook … aaasdf"). Owner confirms which. |

## 7. Quality and testing

| ID | PRD | Priority | Who | Item |
|---|---|---|---|---|
| FX-31 | P10 | 🟡 | Code | No automated tests: there's no `tests/` folder even though `phpunit.xml` and `composer.json` expect one (NEW). Port the scripted end-to-end checks (quote flow, 10-step workflow, customer links, permissions, crawl) to PHPUnit feature tests on a separate test database. |
| FX-32 | — | ⚪ | Code | 20 broken links/assets from the last crawl (old-theme images missing on the server, 6 missing product images, 2 JS template strings). Recheck after FX-19. |

## 8. Follow-ups from finished work

| ID | Priority | Who | Item | Notes |
|---|---|---|---|---|
| FX-33 | 🟡 | Server | Customer approval uploads (1 Nov): PHP on the server needs `upload_max_filesize ≥ 10M` and `post_max_size ≥ 55M`. Otherwise big uploads fail with a plain "too large" error page. | Listed in `WHERE-FILES-GO.txt` step 4. |
| FX-34 | ⚪ | Code | Customer files are stored in the public `storage/order-files/{order}/customer/` folder with random 40-character names, the same as staff files. Better: a private folder served through a login/signature check. | Same applies to existing staff files; do both together. |
| FX-35 | ⚪ | Code | A "too large" upload (over `post_max_size`) shows Laravel's default 413 page instead of a friendly message on the order page. | Handle `PostTooLargeException` in `app/Exceptions/Handler.php`. |
| FX-36 | 🟠 | Owner | **Real tier prices needed** for the 6-Panel Recycled Cotton Cap (id 4) and the Anti-Spill Mug (id 5). Every tier was stored as AED -100.00 (entered 23 Oct). Fixed 2 Nov: tier pricing switched **off** locally, so they show the normal AED 100; the code now ignores tiers ≤ 0 and the admin form refuses them. The live database has the same bad rows. | Enter the real prices in the admin (Edit product → Tier pricing), locally and on live. |
| FX-37 | ⚪ | Owner → Code | The `products` table is **MyISAM**, so changes to products can't be rolled back inside a transaction (all other order tables are InnoDB). | Convert it to InnoDB in a migration after a backup (owner OK). |
| FX-38 | 🟠 | Code (+ Owner) | **Customer dashboard, rest of the page.** The five cards and Recent Orders were rebuilt on 2 Nov (real order data; Recent Orders = one row per order with order number, product image, current step, Track/Respond and View All links). Quick Actions were made real the same day (6 working links; Recipients / Branding Assets / Invite Employees tiles replaced by Track My Orders / View Quote Cart / Account Settings because no feature exists behind them). Request a Quote (-> quote cart) and Contact Us (-> email info@morethangiftz.com + call link; not the /contactus form because of FX-01) were wired the same day. Download Reports and the sidebar Reports link were made real with the Reports pages (2 Nov). Still demo/static: Switch Company is a toast; the Branding Assets panel shows fixed "ABC" files; sidebar Branding Assets goes nowhere (Employees was built 3 Nov). | `web_frontend/myaccount.blade.php`, `UsersController::corporatePortal`. Owner: are recipients/employees, branding assets and reports wanted? |
| FX-39 | 🟡 | Code | The staff "confirmed total" on step 1 is stored only in the step data; `orders.total` (shown as "Order value", used for the 50% payment amounts and the dashboard spend) keeps the quote total. | `OrderWorkflow` step 1 completion: decide whether the confirmed total should update `orders.total`. |
| FX-40 | ⚪ | Code | The storefront header is about 17px wider than a 1440px screen (the phone button pokes out), so wide desktop pages get a sideways scrollbar. Found 2 Nov on the Saved Collections page. | `web_frontend/partials/header.blade.php` / header CSS. |
| ✅ FX-41 | 🟡 | Code | Collection page (`/collections/{id}`): "Add Products" only offers the first 50 online products (`viewCollection` uses `limit(50)`), there is no search across the catalogue, and edits/removals use `alert()` pop-ups. ("Add all to quote cart" was fixed 2 Nov.) | `UsersController::viewCollection`, `web_frontend/collection-detail.blade.php`. | **Done 2 Nov:** picker searches the whole catalogue (`GET /collection-products/search`, 20 per page), real photos, MOQ-aware prices and warnings, unavailable products shown, no pop-ups, edit errors in the window. |
| FX-42 | 🟡 | Code (+ Owner) | Customer name of step 1 is **"Order confirmed"**, so an order that staff have **not** confirmed yet shows "Step 1 · Order confirmed · In progress" (Reports status column, Recent Orders, order page). Found 2 Nov while testing Reports. | `config/order_steps.php` step 1 `customer` → e.g. "Order review"; one change, shown everywhere. Owner to approve the wording. |
| FX-43 | ⚪ | Code | Reports load every matching order into memory and filter/paginate in PHP. Fine for today's numbers (tens of orders); move filtering and pagination into SQL if orders reach the thousands. | `App\Services\OrderReports::orders()`. |
| FX-44 | 🟡 | Code (+ Owner) | The app runs on UTC (`config/app.php` timezone), the business is in the UAE (UTC+4): times on staff/customer pages are 4 hours early, and an invoice issued after 8 pm UAE time gets the next day's date. Found 2 Nov with the advance invoice. | Set `Asia/Dubai` in `config/app.php` after checking stored timestamps and reports; owner OK. |
| FX-45 | 🟠 | Owner | Invoices print the company name, email, phone and website; the **address and VAT TRN are empty** until the owner gives them (`COMPANY_ADDRESS`, `COMPANY_TRN` in `.env`). A UAE tax invoice needs the TRN. | `config/company.php`. Issued invoices keep the details they were issued with. |

## 9. Owner decisions needed (blocking the items shown)

| # | Question | Blocks |
|---|---|---|
| D1 | Who receives quote, contact and customer-response emails? (`QUOTE_REQUEST_EMAIL`; today it falls back to `enquiry@silvergiftz.com` in the header settings) | FX-01, FX-02, FX-03 |
| D2 | Can the old Angular API endpoints be removed? | FX-02, FX-09 |
| D3 | Targets for Best Sellers / Sustainable / Branding (or remove them) | FX-18 |
| D4 | Real social accounts, phone, email, address for the footer and emails | FX-16, FX-23, FX-25 |
| D5 | Membership plans and the fields the lead form should collect | FX-21 |
| D6 | OK to drop the legacy workflow tables/pages and leftover tables/packages | FX-12, FX-15 |
| D7 | Rebuild bulk image upload and/or rewards? | FX-28, FX-29 |
| D8 | Which catalogue products are test data | FX-30 |
| D9 | Google Cloud access | FX-24 |
