# More Than Giftz — Project Handoff (for the next developer or AI)

Read this whole file before changing anything. It explains where the project stands, what is left, and **exactly how work has been done so far**, so you can continue in the same way. The product requirements are in `docs/PRD.md`. **The list of work still to do is `docs/FIXES-AND-UPDATES.md` (FX-01…FX-37 + owner decisions D1–D9); pick work from there and use the FX number in the audit log.**

Last updated: 2 Nov 2026.

---

## 1. Snapshot

| Item | Value |
|---|---|
| Project folder | `C:\Users\2473d\mgdinfotech\mtg\mtg` (git repo, origin `chetanajmer/mtg`) |
| Branch | `dev-ekansh` (work continues here; it is on GitHub as `origin/dev-ekansh`, pushed by the user). Commits on top of `main` (a139b85): `4943540` security lock-down, `ffb8bba` dashboard/order tracking/collections and other fixes, `f53db96` Reports. `security-fixes` points at the same commit. |
| Uncommitted | Nothing as of 3 Nov: all work is in commit `a6014e6`, pushed to `origin/dev-ekansh` at the user's request. **Do not commit unless the user asks.** |
| Pushed? | The user pushes `dev-ekansh` themselves. **Claude never pushes and never opens PRs.** Pushing `main` auto-deploys to the live beta site via cPanel. |
| Live site | https://beta.morethangiftz.com — still runs the OLD code, open admin, debug pages, broken cart. Nothing from this work is deployed. |
| Backup (before any change) | `C:\Users\2473d\mgdinfotech\mtg\mtg-backup-2026-10-31_1547` (full copy + `database-mtg-backup.sql`) |
| Audit log | `C:\Users\2473d\mgdinfotech\mtg\mtg-audit-log\` — `audit-log.md`, `audit-log.jsonl`, 169 entries on 2 Nov, hash-chained |
| Other backups | `mtg-backup-2026-11-01_customer-approvals` (7 files), `mtg-backup-2026-11-02_negative-prices` (4 files + `rows-before.sql`) |
| Deploy package | `C:\Users\2473d\mgdinfotech\mtg\mtg-updated-files\` — every changed/new file + `WHERE-FILES-GO.txt` + `FILES-TO-DELETE.txt` |
| Architecture page | https://claude.ai/artifact/BZUynFZeS84WYqWB5g8UKF (order tracking runtime; describes the pre-rebuild flow on 31 Oct — the rebuild in §5.4 supersedes parts of it) |

## 2. The user and how they want to work

- The user (Windows, PowerShell + Git Bash, Laragon) **cloned** this repo; they did not build it. They report to a manager. Explain things plainly; avoid jargon.
- **Never push or open PRs.** Commit only when explicitly asked.
- **Back up before big changes** and **log every decision and change** in the audit log (see §4).
- When asked to analyse: report findings first, implement only when told.
- When asked to implement: do the work, test it for real, then report what changed, what was tested and what is left. Be honest about mistakes (the log records several).
- They test locally at http://127.0.0.1:8000 with their own `php artisan serve`.
- They asked to stop work if the 5-hour usage window can't fit a task — check usage (`get_usage`) before large jobs when asked.
- They like: tables of what changed, explicit "not done" lists, file paths, and short commands they can copy.
- Customer-facing copy says **More Than Giftz** (not SilverGiftz / Meem Industrial / Seven Wonders / HashAppz — those are leftovers from earlier agencies).

## 3. Local environment (exact)

| Thing | Detail |
|---|---|
| PHP | **7.4** at `C:\laragon\bin\php\php-7.4.33-Win32-vc15-x64\php.exe`. PHP 8.3 is also installed and is first on old terminals' PATH → deprecation walls and a server that won't start. Use the full path or refresh PATH: `$env:Path = [Environment]::GetEnvironmentVariable('Path','Machine') + ';' + [Environment]::GetEnvironmentVariable('Path','User')` |
| Composer | `composer install` only. **Never `composer update`** (old lock; breaks on PHP 8.3). `composer dump-autoload` once hung on this machine (idle CPU) — it is not required for new classes (PSR-4 finds them). |
| MySQL | Laragon MySQL 8.4, database `mtg`, user `root`, **no password**. A separate Windows service `MySQL80` (with a password) used to hold port 3306; it is stopped and set to Manual — don't restart it. CLI: `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root mtg` |
| Server | `php artisan serve` → http://127.0.0.1:8000 (user's). The AI's preview server uses `.claude/launch.json` → falls back to port 8001/8014 when 8000 is busy. The app stops the preview server between turns; restart it with the preview tool when needed. |
| Mail | Local `.env` points at `mailhog` which doesn't exist → every email fails and is **logged** (by design nothing breaks). Tests use `config(['mail.default' => 'array'])` or read `storage/logs/laravel.log`. |
| Uploads | `public/upload` (images from the live server zip, ~1 GB, git-ignored). `public/storage` symlink created (`php artisan storage:link`). |
| Google Analytics | No credentials locally; the dashboard tolerates it (`ga_safe()`). |

## 4. How work is done here (follow this)

### 4.1 Audit log — mandatory for every decision and change
```
cd C:\Users\2473d\mgdinfotech\mtg\mtg-audit-log
python audit_append.py TYPE REF "One-line summary" "file1;file2" "details"
python audit_verify.py        # must print OK: N entries, chain intact
```
TYPE ∈ BACKUP, DECISION, CHANGE, DELETE, TEST, DEFERRED, NOTE. REF = audit finding id (C1, H4, M6, L2…) or `-`. Files are read-only between appends; never edit them by hand. Log a DECISION before doing something non-obvious, a CHANGE after, a TEST with real results, DEFERRED for anything left with the reason, and NOTE for corrections of earlier claims.

### 4.2 Changing code
- Match the surrounding code (this codebase mixes styles; keep each file's own style and comment density).
- Many files have **CRLF** line endings; preserve them. Prefer the Edit tool. If scripting edits, read with `newline=''`, normalise, write back with the original endings.
- **Backslashes in inline Python/heredocs get mangled in this shell** (PHP namespaces broke several times). Put such scripts in a file (scratchpad) and run them.
- In Blade, don't `use` classes inside `@php` (compile error) — write `\App\Services\OrderWorkflow::...`.
- Blade evaluates `route('x')` **even inside HTML comments**: removing a route can crash any page that mentions it. After removing routes, grep views for the name.
- Removing a route? Grep `resources/views`, `public/*/assets/js` and `app` first. The admin layout `resources/views/layouts/app.blade.php` is included on every admin page.
- New admin routes go inside the `Route::middleware(['admin.auth','admin.permission','admin.same-origin'])` group in `routes/web.php`, and their paths must be added to `App\Http\Middleware\AdminPermission::MAP` under the right flag.
- GET admin "actions" are blocked unless `Sec-Fetch-Site: same-origin`/`none` or same-host Referer (`AdminSameOriginActions`). New view-only admin methods must match its `VIEW_METHODS` list; better: make actions POST forms.
- Schema changes = new migration (dated `2026_11_…`). Never drop tables or data without the owner's OK.
- When appending to `.gitignore`, make sure the file ends with a newline (one merge bug happened).

### 4.3 Testing (always real, always cleaned up)
- **Lint:** `php -l` every changed PHP file.
- **Logged-in HTTP tests:** generate an admin session cookie with a small script that boots Laravel, starts a session, puts `admin_id`, `admin_email`, `role` and prints the encrypted cookie (pattern used: `cookie_admin.php <project> <adminId>`). Send `Sec-Fetch-Site: same-origin` on requests.
- **End-to-end flows:** create temporary data through the real code path (e.g. a temporary customer whose CartController adds items and sends an enquiry), run the workflow over HTTP, assert, then **delete everything** created (rows, orderids, uploaded files, temp admins, test sessions). Use emails ending `@localhost.test` so leftovers are easy to find.
- **Crawl:** load every view-only GET page as admin and as visitor (filter by controller method names, never call delete/update/import routes); expect 0 × 500.
- **Links/assets:** strip HTML comments before collecting links.
- **Browser:** check desktop (~1400px) and phone (375px); no sideways scroll.
- Report test results honestly, including what could not be tested (e.g. email delivery, PayTabs, Google login).

### 4.4 Deliverables after each batch
1. Audit log entries + `audit_verify.py`.
2. Rebuild `mtg-updated-files` (copy every file added/changed vs `a139b85`; list deletions in `FILES-TO-DELETE.txt` **only as whole folders when the entire folder is gone**, else exact files; never list `bootstrap/cache`). Keep `WHERE-FILES-GO.txt` current.
3. Plain-language summary to the user: what changed, tested, not done.

## 5. Progress so far (chronological)

### 5.1 Security + critical (30–31 Oct)
- Admin login required everywhere; separate admin session keys; bcrypt for admins and customers (+ migrations hashing existing passwords).
- Upload filter middleware + `.htaccess` rule blocking scripts in upload folders.
- Removed `.env` editor, debug/test routes, PayTabs test page, risky legacy API account endpoints, CORS `*` headers.
- Secrets moved to `.env`/config (PayTabs, MidOcean). **Keys still need rotation.**
- PayTabs callback signature + return verification; JSON-built requests; fixed "always Completed".
- `/orders` no longer leaks all orders; password reset now a one-time link.
- Admin permission flags enforced; GET-action protection; rate limits on logins/forms.

### 5.2 Business fixes
- Quote cart → Store Enquiries + emails (`mail.quote_request_*`); 7 unseen enquiries back-filled.
- Pricing: `Product::priceForQuantity()`, `displayPrice()` (Base Price, else sale, else price), `minOrderQuantity()`, `imageUrl()`. Tier pricing respected only when enabled; tier rows survive toggle-off.
- Product page: tier table hidden when off, base price, MOQ-locked +/− quantity, live total, working Add to Quote.
- Collections show customer prices. Category pages fixed (no brand crash, list category products, 404s).
- New homepage, header links, budget planner, missing assets removed, branding names fixed.
- Missing pages built: order details, invoice, abandoned carts (+ customer reminder email).
- Indexes, dead code removed (~60 views/controllers/models, 40 MB `public/backend`).

### 5.3 Audit results (31 Oct, final crawl)
- 165 view-only pages: **0 crashes** (was 25). 96 admin pages redirect when logged out.
- Links/assets: 20 broken left, none caused by code (old-theme images missing on server, 6 product images missing, 2 JS template strings).

### 5.4 Order tracking rebuild (1 Nov) — same 10 steps
- **Data:** `orders` (one per order number), `order_step_states`, `order_events` (append-only), `cartorders.order_id`; `jobs` table for queued mail. Migration `2026_11_01_000001` converted the 11 existing orders; legacy rows got order numbers 10161–10163.
- **Rules/logic:** `app/Services/OrderWorkflow.php` — `ACTIONS` per step type, validation, step-order rule (+ superadmin override), `record()` timeline, `advance()` (next step auto-starts, status synced to `cartorders.orderstatus`), customer emails, `createForCartorders()` (called from `CartController::sendEnquiry`), `runAsCustomer()` (customer decisions/rating), `LINK_DAYS = 30`.
- **Staff UI:** `/admin/orders/{id}` → `Backend\OrderWorkflowController` + `resources/views/backend/orders/workflow.blade.php` (scoped `.ow-*` CSS). Store Enquiries and old `/order-tracking/{cartorderId}` links point here.
- **Customer UI (phase 4, separate session):** `/orders/{orderid}`, signed `/order-response/{orderid}/{step}/{round}`, `/track-order` lookup (throttled) → `Frontend\CustomerOrderController` + `web_frontend/order-progress.blade.php`; mail class `App\Mail\OrderUpdateMail`; template `mail/order_tracking_update.blade.php`. Reported 58/58 checks passing.
- Old `order_workflow_steps` / `order_processing_workflows` tables and the old tracking page code remain but are unused.

### 5.5 After the handoff (1–2 Nov)
- **Master list** `docs/FIXES-AND-UPDATES.md` written (audit #103).
- **Customer approvals (steps 2 and 4):** Approve / Request changes / Reject (reason required for the last two). Reject keeps the 5 step states (`changes_requested`) but stores `last_decision = rejected`; staff see a banner and get a "REJECTED" email. "Share a design or idea" (action `share`, timeline type `idea`, no state change) while the step is open. Customer files: 5 × 10 MB, `OrderWorkflow::CUSTOMER_FILE_TYPES`, stored in `order-files/{orderid}/customer/` with random names; SVGs with script refused; upload filter allows ai/eps/zip. `CUSTOMER_ACTIONS` now lists allowed *states* per action. 54/54 checks (audit #104–109).
- **Negative tier prices:** products 4 and 5 had every tier at AED −100. Tier prices ≤ 0 are now ignored (`Product::priceForQuantity`, product page tier table) and refused by the admin product form (`ProductController::invalidTierPrices`); the edit form shows the stored value with a warning. Products 4/5 tier pricing switched off; test order #10165 corrected to AED 10,500. 24/24 checks (audit #110–117). Follow-ups FX-36, FX-37.
- **Customer dashboard cards (2 Nov):** `UsersController::dashboardStats()` reads `orders` (by `storeuser_id`) + step states, so each order counts once (the old code counted `cartorders` product lines and old status words). Cards: Total Orders, Items Delivered, Pending Approvals (links to `/orders/{orderid}#respond`), Active Orders (replaced "Total Recipients" — no such feature), Total Spend this year (step 1 done, `orders.total`). Fake percentages removed. 22/22 checks + desktop/375px screenshots. **Recent Orders** (same day): latest 5 `orders` (loaded once in `corporatePortal` and shared with `dashboardStats($orders)`), one row per order: number + date, first product + "N more", pcs + value, `Cartorder::productImageUrl()` image (now also accepts full http URLs), customer step name + state pill (`.recent-status`, not `.pill`, which style.css hides under 1350px), Track / Respond links. Test grew to 32/32. **Quick Actions** (same day): 6 `<a>` tiles (homepage, /orders with "N need your decision", /corporate-addresses, /cart with item count, /collections-all, /corporate-account-settings); Recipients / Branding / Invite Employees tiles replaced (no feature). Test 43/43. **Request a Quote** -> `cart.index` with "(N items)"; **Contact Us** -> `mailto:info@morethangiftz.com` + `tel:` link (not `/contactus`, FX-01). Note: Blade ignores `@if` glued to a word ("Quote@if") — use `{{ }}` expressions there. Test 47/47. Rest of the dashboard: FX-38.
- **Contact Us form fixed (FX-01, FX-03; 2 Nov):** `HomepageController::contactform()` validates, emails staff via `staff_email()` (Reply-To customer) using `mail/contact_staff`, sends `mail/contact_customer` (no echo of the message), shows an honest error if sending fails. Old `usercontactmail`/`admincontactmail` deleted (they crashed the form). `staff_email()` lives in `app/helpers.php`. Tests: `mtg-scratch/test_contact_form.php` 21/21; approvals regression 54/54. **Testing emails:** run a second server with `MAIL_MAILER=log php -S 127.0.0.1:8001 -t public server.php` and read `storage/logs/laravel.log`; the user's :8000 server sends to `mailhog`, which does not exist. Python could not write `HomepageController.php`/`helpers.php` here (Errno 22); use the Edit tool for those.
- **Old Angular enquiry endpoints removed (FX-02; 2 Nov):** `enquiry`, `search_or_update`, `contact` routes, CSRF exceptions, methods, `sendmail_to_*` helpers and `mail/enquiry_*_email` templates. Test `mtg-scratch/test_old_api_removed.php` 22/22. Never POST test data to `addtocart`: it reserves an order number in `orderids` first.
- **Saved Collections page (2 Nov):** create/edit modal on `/collections-all` (create opens the new collection), server + instant search, cards with up to 4 photos, pcs and estimated value (MOQ/tier-aware), "Add to quote cart" via `POST /collections/{id}/to-cart` → `UsersController::collectionToCart` → shared `CartController::addProductToCart()` (also used by `addItem`); `CollectionItem::$touches` keeps the date right; collection page "Proceed to Enquiry" placeholder replaced. Test `mtg-scratch/test_collections_page.php` 34/34 (`cleanup_collections_page.php` for kept data). Cart badge = number of cart rows (header composer), not pieces. Collection page (FX-41, 2 Nov): `viewCollection` builds `$rows`/`$totals` (MOQ-aware, `imageUrl()`, unavailable products kept as rows); picker = `collectionProductSearch` (`GET /collection-products/search?q=&page=&collection=`, login required, LIKE wildcards escaped); `addItemToCollection` refuses offline products and returns the new quantity. Test `mtg-scratch/test_collection_detail.php` 32/32. Advance payment invoice (2 Nov): table `order_invoices` (migration 2026_11_02_000001; `data` = frozen snapshot, unique per order+type), model `OrderInvoice`, service `App\Services\OrderInvoices::issue($order, $type, $actor)` with `TYPES` advance (step 3, MTG-ADV-) and balance (step 7, MTG-BAL-; snapshot adds the step 3 advance and `outstanding`), the step's payment must be recorded first; numbers locked per year and type; staff `POST admin/orders/{id}/invoices/{advance|balance}` + `GET admin/orders/{id}/invoices/{invoiceId}`, customer `GET orders/{orderid}/invoices/{number}` (own orders only; not on /track-order), shared print view `resources/views/invoices/show.blade.php` (both types), company details `config/company.php`. Test `mtg-scratch/test_advance_invoice.php` 69/69 (advance + balance) (run on a MAIL_MAILER=log server on :8001; cleanup script `cleanup_advance_invoice.php`). Corporate login allows 5 attempts/minute, so back-to-back test runs can be refused: wait a minute. Employees page (3 Nov): tables `company_employees` and `company_team_members` (migration 2026_11_03_000001), `CorporateEmployeeController`, views `web_frontend/employees.blade.php` + `team-accept.blade.php`, `mail/team_invite`. **Team logins work through `App\Http\Middleware\CorporateTeamSession` (web group):** when a member logs in, it sets session `userid` = the company owner's id (so every existing customer query is shared) and keeps `member_id`, `member_role`, `member_account`, `member_company`; it re-checks the membership on every request and blocks writes by role (viewer: all writes; buyer: team + account settings; admin: account settings). Any new customer write route is automatically covered for viewers; add role rules there if a page must be owner/admin-only. Test `mtg-scratch/test_employees.php` 49/49 (`cleanup_employees.php`). About Us page (3 Nov): `web_frontend/about.blade.php`; wording = `About::pageContent()` = `abouts.content` (JSON, migration 2026_11_03_000002) over defaults in `config/about_page.php`; admin editor `/storeaboutus` (`Backend\CommonController`, view `backend/pages/about.blade.php`, menu item behind the `config` permission). Test `mtg-scratch/test_about_editor.php` 21/21 (restores the content). Python file writes fail with Errno 22 on some project files here; use the Edit tool or `sed -i` (e.g. `sed -i 's/$/\r/'` to restore CRLF).
- **Reports, both sides (2 Nov; started in one session, finished in the next):** shared `App\Services\OrderReports` (`orders($filters)` one entry per order from `orders` + items + step states; `summary`, `byMonth`, `byProduct`, `byCustomer`, `byStep`, `openSteps()` = steps 1–9 because at step 10 the project is already completed; `status()`; `csv()` streamed with UTF-8 BOM and formula protection). Customer: `Frontend\CustomerReportController` (`/corporate-reports`, `/corporate-reports/download/{orders|spending|approvals}`, `corporate.auth`, filters always include `storeuser_id`, 403 without it), `web_frontend/reports.blade.php`, `css/reports.css`; sidebar "Reports" on the 7 portal pages; dashboard Download Reports panel = select of the 3 download URLs + link (the old demo handler in `script.js` is no longer bound). Admin: `Backend\ReportController` (`admin/reports`, `admin/reports/export?rows=lines`), `backend/report/index.blade.php` (quick periods use their own button style: the theme forces every `btn-default` blue with `!important`); old `date_report`/`user_report`/`product_report` redirect to `#by-month`/`#by-customer`/`#by-product`, their 3 views deleted. Test `mtg-scratch/test_reports.php` 57/57 (`SNAPSHOT_DIR=… ` saves logged-in pages for screenshots). Note: `products` is MyISAM, so `DB::beginTransaction()` does not undo product changes in tests.
- **Branch mishap (2 Nov):** all work is committed on `security-fixes` (`ffb8bba`). Switching to `dev-ekansh`/`main` shows the old code, and switching back removes the empty `bootstrap/cache` folder (the original repo tracked its two generated files); `bootstrap/cache/.gitignore` now keeps the folder — commit it.

## 6. What is left (prioritised)

### 6.1 Needs a person / the server (can't be done in code)
1. **Deploy** following `mtg-updated-files/WHERE-FILES-GO.txt` (backup server, copy files, delete list, `.env`, `composer dump-autoload`, `php artisan migrate --force`, `storage:link`, caches). Live is broken/insecure until then.
2. **Rotate** PayTabs + MidOcean keys; set `APP_DEBUG=false`; real SMTP; `QUOTE_REQUEST_EMAIL` (who receives quotes, Contact Us messages and customer answers; read through `staff_email()` / `config('mail.staff_address')`, never `env()`); queue worker (cron `php artisan queue:work --stop-when-empty` every minute) if `QUEUE_CONNECTION=database`.
3. **Google login:** owner of Google Cloud client `832816…` adds redirect URIs and shares keys (see memory/notes: on hold).
4. Owner decisions listed in PRD §4.

### 6.2 Code tasks (next)
The full, current list is `docs/FIXES-AND-UPDATES.md`; start with its critical items FX-01–FX-03 (customer data emailed to old agency addresses, quote email lost after `config:cache`). The table below is the original summary.

| Priority | Task | Notes |
|---|---|---|
| High | Store Enquiries filters + search (waiting for customer, payment pending, overdue) | Read from `orders` (current_step, step_state, step_started_at). |
| High | Step due dates / overdue (W9) | Add per-step target days in `config/order_steps.php`; show "overdue" on staff page and list. |
| High | Convert admin GET actions to POST forms (A12) | Then the same-origin middleware becomes a safety net. Large but mechanical; do module by module with crawl tests. |
| Medium | Redesign old-theme pages (S19) in `web_frontend` layout | category/brand/about/contact/policies/search/login; keep routes and controllers. |
| Medium | Real corporate wishlist (S16) and membership enquiry form (S17) | Wishlist can reuse `collections`. |
| Medium | Header targets (S2) once owner decides | `resources/views/web_frontend/partials/header.blade.php`. |
| Medium | Remove legacy Order Processing + old tracking code/tables (W11) | After owner OK; migration to drop tables last. |
| Low | Automated tests (P10) | Port the scripted checks to PHPUnit feature tests. |
| Low | Package/table clean-up (P11), PHP/Laravel upgrade (P9) | Separate project. |

## 7. Key files map
| Area | Files |
|---|---|
| Routes | `routes/web.php` (public first; admin group at bottom), `routes/api.php` (empty) |
| Middleware | `app/Http/Middleware/AdminAuth.php`, `AdminPermission.php` (MAP), `AdminSameOriginActions.php`, `RejectDangerousUploads.php`; registered in `app/Http/Kernel.php` |
| Blade helper | `@adminCan('path')` defined in `app/Providers/AppServiceProvider.php` |
| Helpers | `app/helpers.php` (`paytabs()`, `paytabs_query()`, `paytabs_valid_callback()`, `ga_safe()`) |
| Pricing | `app/Models/Product.php` |
| Cart / quotes | `app/Http/Controllers/Frontend/CartController.php`, `resources/views/web_frontend/cart.blade.php`, `mail/quote_request_*` |
| Order workflow | `config/order_steps.php`, `app/Services/OrderWorkflow.php`, `app/Models/Order*.php`, `Backend/OrderWorkflowController.php`, `Frontend/CustomerOrderController.php`, `backend/orders/workflow.blade.php`, `web_frontend/order-progress.blade.php` |
| Store Enquiries | `Backend/OrderController.php`, `backend/orders/list.blade.php`, `orderdetail`, `invoice`, `abandoned_*` |
| Storefront layout | `resources/views/web_frontend/layouts/app.blade.php`, `partials/head|header|footer|scripts.blade.php`; old theme `layouts/frontapp.blade.php` |
| Admin layout | `resources/views/layouts/app.blade.php` (sidebar items wrapped in `@adminCan`) |
| Deploy notes | `.cpanel.yml` (unchanged; rsync only), `mtg-updated-files/WHERE-FILES-GO.txt` |

## 8. Gotchas we hit (don't repeat them)
- PHP 8.3 on PATH → use 7.4 explicitly.
- `MySQL80` service blocking port 3306 → keep it stopped.
- `composer update` fails; `composer dump-autoload` may hang.
- The preview/dev server stops between turns → restart before HTTP tests.
- Windows CRLF in generated lists breaks `curl` URLs → strip `\r`.
- Backslash mangling in inline scripts → use files.
- `route()` in HTML comments still executes.
- Same-origin middleware blocks scripted GETs without `Sec-Fetch-Site`.
- `order_workflow_steps` JSON paths are stored with escaped slashes (`\/`) — match accordingly.
- `.gitignore` without trailing newline merges appended lines.
- Grouping deletions by folder can wrongly tell the server to delete live folders — only list fully removed folders.
- Customer `/orders` and admin `/admin/orders` are different pages; admin order page uses the `orders.id`, customer page uses the order **number**.

## 9. Data facts (local DB)
- 11 orders (#8618, #10154–#10163); 9 active products; many products reference images missing from uploads.
- Admins: ids 1 (superadmin), 2, 14 (staff), 15–17 (admins with limited flags — #15 has only sliders + banners, useful for permission tests).
- Customer test accounts exist from the original data (e.g. user 60/61); test emails used by the AI end with `@localhost.test` and are always removed.
