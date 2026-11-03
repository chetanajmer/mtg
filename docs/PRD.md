# More Than Giftz — Product Requirements Document (PRD)

| | |
|---|---|
| Product | More Than Giftz (MTG) B2B corporate-gifting website + admin panel |
| Live (beta) | https://beta.morethangiftz.com (cPanel, still running the OLD code) |
| Code | `C:\Users\2473d\mgdinfotech\mtg\mtg` (Laravel 8.42, PHP 7.4, MySQL) |
| Document date | 1 Nov 2026 |
| Status legend | ✅ done and tested locally · 🟡 partly done · ❌ not started · ⏸ on hold (needs a person/server) |

Companion documents: `docs/PROJECT-HANDOFF.md` (how we work, setup, history, gotchas) · `docs/FIXES-AND-UPDATES.md` (the master list of open fixes FX-01…FX-37 and owner decisions; matched to the requirement IDs below).

---

## 1. What the product is

A UAE corporate-gifting store. Companies browse branded merchandise (drinkware, tech, bags, apparel, awards, stationery…), add products to a **quote cart**, and **send an enquiry** instead of paying online. Staff turn each enquiry into an order and run it through a fixed **10-step production workflow** (mockup approval → advance payment → sample → mass production → QC → balance payment → delivery → completion → rating). Customers follow progress online and by email.

Most of the catalogue comes from **MidOcean** (European promo-products wholesaler) via a daily API import; the rest is entered by staff.

### Goals
1. Turn website visitors into quote requests that staff actually receive and act on.
2. Run every order through one clear, auditable workflow that staff and customers both understand.
3. Show correct customer prices (tiers, base price, MOQ) and never expose cost prices.
4. Keep the admin secure (logins, permissions, no public data or debug leaks).

### Users
| User | Needs |
|---|---|
| Corporate buyer (customer) | Find products, see real prices for their quantity, build a quote, send it, approve mockups/samples, follow the order, rate it. |
| Staff / admin | Manage catalogue and content, receive quotes, run the 10-step workflow, communicate with the customer, see reports. |
| Superadmin | Everything above + manage admin users and their permissions, override the step order when needed. |

---

## 2. Scope and requirements

### 2.1 Storefront (customer-facing)

| ID | Requirement | Status |
|---|---|---|
| S1 | Homepage in the new design: intro, category grid with images, latest products with "From AED x · MOQ y", quote call-to-action | ✅ interim version (`web_frontend/home.blade.php`); a designed homepage can replace it |
| S2 | Header navigation links to real pages | 🟡 11 of 14 done; **Best Sellers, Sustainable, Branding** have no target page yet (owner decision) |
| S3 | Category, subcategory, brand pages list the right products; unknown slugs return 404 | ✅ (still in the OLD theme design) |
| S4 | Product page: gallery, description, specs, branding options | ✅ existing |
| S5 | Product page pricing: Base Price when tier pricing is off; tier table only when "Enable tier pricing" is on; tiers priced ≤ 0 are ignored and refused by the admin form; never show Cost Price | ✅ (real tiers for products 4/5 still needed: FX-36) |
| S6 | Quantity selector: only + / − buttons, starts at and never goes below the product's MOQ; live total (unit price × qty, tier-aware) | ✅ |
| S7 | "Add to Quote" adds the selected quantity to the cart and updates the badge | ✅ |
| S8 | Cart: tier-aware unit prices recalculated on view, MOQ enforced server-side, 5% VAT (`CART_TAX_RATE`) | ✅ |
| S9 | Send Enquiry (logged-in): creates order number, Store Enquiries rows, tracked order (10 steps), emails staff + customer, private confirmation page | ✅ |
| S10 | Corporate portal: register/login (bcrypt), forgot password via 60-minute reset link, account settings, saved addresses, collections with customer prices | ✅ |
| S10a | Saved Collections page `/collections-all`: create / rename / delete on the page, search all collections, cards with product photos, pieces and estimated value, "Add to quote cart" for a whole collection (MOQ + tier prices) | ✅ (2 Nov); collection page: picker searches the whole catalogue, MOQ-aware totals (FX-41 done) |
| S11 | Google login | ⏸ code works; Google Cloud project owner must add redirect URI `https://beta.morethangiftz.com/corporate/auth/google/callback` (+ local `http://localhost:8000/...`) and share keys |
| S12 | Customer order page `/orders/{orderid}`: 10-step progress (customer step names), customer-visible timeline + files, courier/tracking after dispatch | ✅ (phase 4) |
| S13 | Customer approves / requests changes / rejects (reason required) mockups (step 2) and samples (step 4) on the order page or via a signed email link (30 days, no login) | ✅ (phase 4; Reject added 1 Nov) |
| S13a | Customer attaches their own design files to an answer, or shares a design / idea any time step 2 or 4 is open (JPG/PNG/WEBP/PDF/SVG/AI/EPS/ZIP, 5 × 10 MB; SVGs with scripts refused); staff see the files, a "rejected" banner and get an email | ✅ (1 Nov) |
| S14 | Customer rating at step 10 | ✅ (phase 4) |
| S15 | Public `/track-order`: order number + email → read-only progress (rate-limited) | ✅ (phase 4) |
| S15b | Customer Reports `/corporate-reports` (sidebar on every portal page): this year / last year / all time / custom dates (by order date, end date inclusive); cards (orders, confirmed spend, pieces delivered, average order); spending by month; most ordered products; the orders with links; CSV downloads: order history (product lines), spending summary (per month + total), approval history (design/sample decisions); only the customer's own orders; dashboard "Download Reports" panel uses the same files | ✅ (2 Nov) |
| S15a | Customer dashboard `/corporate-portal` cards from real orders: Total Orders (+ this year), Items Delivered (delivery step done), Pending Approvals (mockup/sample waiting, links to the order), Active Orders (+ completed), Total Spend this year (staff-confirmed orders, + amount awaiting confirmation); no invented trends | ✅ cards + Recent Orders + Quick Actions + Request a Quote / Contact Us (2 Nov: one row per order, image, step, Track/Respond link); rest of the dashboard is still demo: FX-38 |
| S15b | Employees page `/corporate-employees`: gift recipients (add/edit/delete, search, department filter, CSV import/export + template, birthdays and work anniversaries in the next 30 days) and team logins (owner/admin invite by email as Admin / Buyer / Viewer, 7-day link, invitee sets own password, shared company account, roles enforced on the server, removal ends access on the next click) | ✅ (3 Nov) |
| S16 | Corporate wishlist page uses real data | ❌ static mock-up |
| S17 | Annual corporate membership page captures leads | ❌ static page, no form |
| S18 | Budget planner works | ✅ script fixed (static recommendations) |
| S19 | Old-theme pages (category, brand, about, contact, policies, search, old login/register) redesigned in the new look | ❌ |
| S20 | Footer/social links point to MTG's own accounts | ❌ need real accounts from owner |

### 2.2 Order workflow (the core)

The 10 steps are fixed (defined once in `config/order_steps.php`):

| # | Step (staff name) | Customer name | Type | Required to complete |
|---|---|---|---|---|
| 1 | Enquiry / Order | Order confirmed | review | confirmed total |
| 2 | Mockup Design & Approval | Design approval | approval | ≥1 file sent, then customer approval (changes or reject need a comment); versions kept; customer may add own design files |
| 3 | Advance Payment (50%) | Advance payment | payment | amount, method, reference, date (receipt optional) |
| 4 | Sample Production & Approval | Sample approval | approval | as step 2 |
| 5 | Mass Production | In production | production | start + expected finish, then finished date |
| 6 | Quality Check & Packing | Quality check & packing | checklist | all 4 checks + packed quantity |
| 7 | Balance Payment (50%) | Balance payment | payment | as step 3 |
| 8 | Delivery | Delivery | shipment | dispatch (courier, tracking no., date) then delivered (date, receiver, proof file) |
| 9 | Project Completed | Completed | closure | — |
| 10 | Customer Rating | Your feedback | feedback | rating 1–5 (customer or staff) |

| ID | Requirement | Status |
|---|---|---|
| W1 | One `orders` row per order number; products are `cartorders` rows linked by `order_id` | ✅ |
| W2 | Each step has exactly one state: not_started, in_progress, waiting_customer, changes_requested, done | ✅ |
| W3 | Steps must be done in order; superadmin may override (logged) | ✅ |
| W4 | Every action and note is an append-only timeline entry (who, when, message, files, staff-only vs customer-visible) | ✅ |
| W5 | Customer-visible events email the customer (queued mail class; queue currently sync) | ✅ |
| W6 | Order status shown everywhere = current step + state, synced to `cartorders.orderstatus` | ✅ |
| W7 | Staff order page `/admin/orders/{id}`: header, progress strip, step list, step-specific screen, notes, timeline, products, print invoice | ✅ |
| W8 | Existing orders converted (11 orders; legacy statuses mapped) | ✅ |
| W8a | Advance payment invoice: after step 3 is recorded, staff click "Generate invoice"; numbered MTG-ADV-<year>-<0001>, frozen copy (lines, VAT, total, advance received, balance), timeline entry + customer email; customer opens it from the advance payment step (print / save as PDF) | ✅ (2 Nov); company address + TRN from owner (FX-45) |
| W8b | Balance payment invoice after step 7, same way: MTG-BAL-<year>-<0001>, shows the advance (step 3) and the balance received, 'PAID IN FULL' when nothing is outstanding, otherwise the amount still outstanding | ✅ (2 Nov) |
| W9 | Due dates / SLA per step, overdue filter on Store Enquiries | ❌ |
| W10 | Store Enquiries filters ("waiting for customer", "payment pending", "overdue") and search | ❌ |
| W11 | Remove legacy 5-step "Order Processing" and old `order_workflow_steps` tables/pages after sign-off | ❌ (hidden, kept) |

### 2.3 Admin panel

| ID | Requirement | Status |
|---|---|---|
| A1 | All admin routes require admin login (`admin.auth`) | ✅ |
| A2 | Per-admin permission flags enforced on server and menu (`admin.permission`); Users + Super Admin settings superadmin-only | ✅ |
| A3 | GET-triggered actions only from the admin itself (`admin.same-origin`) | ✅ (long-term: convert to POST forms) |
| A4 | Admin passwords hashed | ✅ |
| A5 | Store Enquiries list: order #, customer, product count, status, paginated, link to order page | ✅ |
| A6 | Order details, print invoice, abandoned carts (+ reminder email to the customer) | ✅ |
| A7 | Product editor: tier pricing rows kept when toggle is off; base price / cost price / MOQ fields | ✅ |
| A8 | Dashboard works without Google Analytics credentials | ✅ |
| A9 | Catalogue CRUD (brands, categories, products, colours, branding options, occasions, preferences, events, tier pricing) | ✅ existing |
| A10 | Bulk image upload by model number | ❌ removed (needed a table that never existed); rebuild only if wanted |
| A11 | Reward/referral section | ❌ removed (tables never existed) |
| A12 | Admin actions as POST forms instead of GET links | ❌ |
| A13 | Admin Reports `/admin/reports` (Orders permission): one row per order by order date; filters date / customer / product / stage (steps 1–9, waiting for customer, completed); totals (orders, value, confirmed value, pieces, delivered); by month, where open orders are, top customers and products; CSV export of orders or product lines; replaces the old Date / User / Product reports (their URLs redirect) | ✅ (2 Nov) |

### 2.4 Platform, security, operations

| ID | Requirement | Status |
|---|---|---|
| P1 | Secrets only in `.env` (PayTabs, MidOcean, Google) — old keys must be **rotated** (they are in git history) | 🟡 code done; rotation ⏸ |
| P2 | `APP_DEBUG=false` on live | ⏸ server |
| P3 | Upload filter (images/PDF/video/Excel only) + no PHP execution in upload folders | ✅ |
| P4 | PayTabs: callback signature check, return verified via query API, JSON-built requests | ✅ |
| P5 | Database indexes on lookup columns | ✅ |
| P6 | Deploy process runs `composer dump-autoload`, `migrate`, `storage:link`, caches | ⏸ documented in `mtg-updated-files/WHERE-FILES-GO.txt` |
| P7 | Live database migrated (live is missing `cart_items` etc.) | ⏸ server |
| P8 | Real SMTP on server + queue worker for emails | ⏸ server |
| P9 | Upgrade PHP 7.4 → 8.x and Laravel 8 → current (69 package advisories) | ❌ separate project |
| P10 | Automated tests | ❌ (only scripted end-to-end checks so far) |
| P11 | Remove unused packages (intervention/image, spatie analytics, 2nd PayTabs) and leftover tables (products1, newproducts, old_portfolios, frontend_settings) | ❌ needs owner sign-off |

---

## 3. Out of scope (for now)
- Online card payment checkout (the business works on quotes; PayTabs code exists only for the old Angular API).
- The old Angular storefront and its JSON API (catalogue read endpoints remain public; account endpoints were removed).
- Multi-language.

## 4. Open decisions for the owner
1. Targets for header items **Best Sellers, Sustainable, Branding** (or remove them).
2. Real social accounts and contact details for the footer and old-theme pages.
3. Who receives quote, Contact Us and order-response emails (`QUOTE_REQUEST_EMAIL`, read via `staff_email()`) — currently falls back to the Header Settings email `enquiry@silvergiftz.com`.
4. Whether to drop leftover tables / legacy workflow data.
5. Google Cloud access for Google login.
6. Whether "Bulk image upload" and "Rewards" should be rebuilt.
7. Clean-up of test-looking catalogue products (e.g. "Vishakha Upadhyay", "A5 Notebook … aaasdf").

## 5. Release plan
1. **Deploy what exists** (server): back up, copy `mtg-updated-files`, delete `FILES-TO-DELETE.txt` items, set `.env`, run commands, smoke-test (see WHERE-FILES-GO.txt).
2. **Storefront polish:** S2, S16, S17, S19, S20.
3. **Workflow v1.1:** W9, W10, W11.
4. **Admin hardening:** A12, P10.
5. **Platform upgrade:** P9, P11.

## 6. Success measures
- 100% of quote requests appear in Store Enquiries with an email to staff.
- Every active order shows a correct current step; no order "stuck" without a timeline entry for > N days (after W9).
- Zero admin pages reachable without login; zero 500 errors in a full crawl (current local result: 0 of 165 pages).
