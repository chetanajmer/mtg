# More Than Giftz: Work Completed Since Handover

| | |
|---|---|
| Project | More Than Giftz (MTG) corporate-gifting website and admin panel (Laravel 8, PHP 7.4, MySQL) |
| Period | 30 Oct to 3 Nov 2026 |
| Code | Branch `dev-ekansh` (commits `4943540`, `ffb8bba`, `f53db96`), plus the invoice work that is not committed yet |
| Live site | https://beta.morethangiftz.com still runs the old code. Nothing here is deployed yet. |
| Full detail | Every decision, change and test is in the audit log: `C:\Users\2473d\mgdinfotech\mtg\mtg-audit-log\audit-log.md` (190 entries) |

This document lists everything that was added, rebuilt or fixed, grouped by area. The other project documents are `docs/PRD.md` (what the product must do), `docs/FIXES-AND-UPDATES.md` (what is still open) and `docs/PROJECT-HANDOFF.md` (how the work is done).

---

## At a glance

- **Security:** the admin panel was open to anyone; it now needs a login, and each staff member only sees what their permissions allow. Passwords are hashed, payments are verified, dangerous uploads are blocked, and secret keys left the code.
- **Quotes reach the business:** "Send Enquiry" used to save nothing staff could see. Every quote now becomes an order in Store Enquiries and emails staff and the customer.
- **A complete order workflow:** a 10-step process from enquiry to rating, with a page for staff and a page for the customer, approvals by the customer (including from an email link), automatic emails, and invoices for both 50% payments.
- **A working customer portal:** dashboard, orders, saved collections and reports all use real data. Before, most of it was static demo content.
- **Staff tools:** new order page, rebuilt reports, order details, invoice printing and abandoned-cart pages.
- **Correct prices:** tier prices, base price and minimum order quantities now apply consistently on product pages, the cart, collections and invoices.
- **Stability:** 0 of 165 pages crash (25 did before), and broken links went from 384 to 20 (none caused by code).

---

## 1. Security (30 to 31 Oct)

| What was wrong | What it does now |
|---|---|
| Admin pages could be opened without logging in | Every admin page needs an admin login; admins have their own session, separate from customers |
| Staff permission settings were ignored | Each admin's permissions are enforced on the server and in the menu; Users and Super Admin settings are superadmin-only |
| A link on another website could trigger admin actions (delete, toggle…) | Those actions only run when started from inside the admin |
| Admin and customer passwords stored in plain text | All passwords are hashed (existing ones converted) |
| "Forgot password" reset the password instantly | Sends a one-time link valid for 60 minutes |
| Login and contact forms could be hammered | Rate-limited |
| Any file type could be uploaded, including scripts | Only images, PDF, video, Excel and design files; scripts cannot run from upload folders |
| A built-in `.env` editor and debug/test pages were reachable | Removed |
| Old API endpoints let anyone read or change any customer account (including passwords) by user id | Removed |
| PayTabs and MidOcean keys were written in the code | Moved to the server settings (`.env`). The old keys still need replacing, since they are in git history. |
| PayTabs marked every payment "Completed" | Callbacks are verified by signature and returns are checked with PayTabs |
| `/orders` showed all orders to anyone | Customers see only their own; the staff list moved to `/admin/orders` |
| Login could redirect to other websites | Blocked |
| Customer enquiries and contact messages were emailed to the previous agency and a personal Gmail | Fixed (see section 7) |

## 2. Quotes and the cart

- **Send Enquiry now creates real orders:** each quote gets an order number, appears in Store Enquiries, starts the 10-step workflow, and emails staff and the customer (MTG-branded emails). The 7 quotes that had been lost were recovered into Store Enquiries.
- **Private confirmation page:** only the customer who sent the quote can open it.
- **Cart pricing:**
  - Charges the tier price for the quantity, the same price the product page shows.
  - Never goes below the minimum order quantity.
  - Adds 5% VAT (adjustable in settings).
- Old `/checkout` and `/guestcheckout` addresses go to the cart.

## 3. Products and pricing

- **Product page:**
  - The tier pricing table only shows when tier pricing is switched on.
  - Shows the Base Price when tiers are off, and never shows the cost price.
  - Quantity uses + / − buttons only, starts at the minimum order quantity and can't go below it.
  - A live total updates with the quantity.
  - "Add to Quote" adds the selected quantity.
- **Admin product editor:**
  - Tier price rows are kept when the toggle is off.
  - Tier prices of 0 or less are refused, where before they were silently saved as free.
  - The real stored value is shown with a warning.
- **Negative prices fixed:** two products had every tier stored at AED −100. Tiers of 0 or less are now ignored everywhere, and those two products use their normal price until real tiers are entered.
- **Product photos:** one helper finds a product's photo for every page. It handles MidOcean photo links and missing files.

## 4. Order workflow (the core of the business)

A single 10-step process replaces the old, half-working order tracking. The steps are defined once and used everywhere:

| # | Staff name | Customer sees | What staff record |
|---|---|---|---|
| 1 | Enquiry / Order | Order confirmed | Confirmed total |
| 2 | Mockup Design & Approval | Design approval | Mockup files sent; the customer's decision |
| 3 | Advance Payment (50%) | Advance payment | Amount, method, reference, date; **invoice** |
| 4 | Sample Production & Approval | Sample approval | Sample photos sent; the customer's decision |
| 5 | Mass Production | In production | Start, expected finish, finished date |
| 6 | Quality Check & Packing | Quality check & packing | 4 checks + packed quantity |
| 7 | Balance Payment (50%) | Balance payment | Amount, method, reference, date; **invoice** |
| 8 | Delivery | Delivery | Courier + tracking number, then delivered with proof |
| 9 | Project Completed | Completed | Close |
| 10 | Customer Rating | Your feedback | Rating 1–5 |

### How the workflow behaves
- **Structured data:** each order has one record with a state for every step and a permanent timeline. That timeline records who did what and when, which entries the customer can see, and any attached files.
- **Order rules:** steps follow in order, and a superadmin can override, which is logged.
- **Always-current status:** the order's status is the current step and its state, shown the same everywhere.
- **Customer emails:** every customer-visible update emails the customer, and the email can be queued.
- **Old orders converted:** all 11 existing orders were moved into the new system.

### Staff order page (`/admin/orders/{id}`)
- Header with customer and order value, a progress strip, and the list of steps.
- A screen for each step type with the right form: mockup upload and versions, payment, production dates, QC checklist, courier details, delivery proof, rating.
- Notes (staff-only or customer-visible), files, the full timeline, products, and print invoice.
- Store Enquiries has an "Order Tracking" button for each order. Old tracking links lead to this page.

### Customer order page (`/orders/{orderid}`)
- The 10 steps in the customer's own words, the current state, a timeline of updates with files, a delivery card with courier and tracking number, and the products.
- **Design and sample approval (steps 2 and 4):**
  - The customer can Approve, Request changes, or Reject. Changes and Reject need a reason.
  - They can attach their own design files (JPG, PNG, WEBP, PDF, SVG, AI, EPS, ZIP; 5 × 10 MB). Unsafe SVGs are refused.
  - They can **share a design or idea** at any time while the step is open.
  - Staff get an email and a "rejected" banner.
- **Approve without logging in:** a secure link in the email, valid for 30 days.
- **Rating** at the end.
- **My Orders:** one row per order, with a highlight when the customer needs to respond.
- **Public `/track-order`:** order number + email gives a read-only view. It is rate-limited.

### Payment invoices (steps 3 and 7)
- **Generate invoice:** once a payment is recorded, staff click "Generate invoice".
  - Numbering: **MTG-ADV-2026-0001** (advance) and **MTG-BAL-2026-0001** (balance), each in its own sequence.
- **Contents:**
  - Company and customer details, the products, VAT and order total.
  - The payment received (date, method, reference) and the balance still due.
  - The balance invoice includes the advance too and is stamped **PAID IN FULL** when nothing is outstanding.
- **Frozen once issued:** later changes to the order never alter an issued invoice.
- **What the customer gets:** a timeline entry, an email with a "View invoice" button, and a "View invoice" link on that step of their order page.
- **The invoice page:** print-ready with Print / Save as PDF, and works on a phone. Only the order's customer and staff can open it.

## 5. Customer portal

| Page | Before | Now |
|---|---|---|
| **Dashboard** (`/corporate-portal`) | Numbers counted product lines; percentages and arrows were invented; most buttons only showed a pop-up | Cards from real orders: Total Orders, Items Delivered, Pending Approvals (links to the order waiting), Active Orders, Total Spend this year. Recent Orders shows one row per order with photo, current step and Track / Respond. All 6 Quick Actions open real pages. Request a Quote opens the cart; Contact Us emails or calls the team. |
| **Saved Collections** (`/collections-all`) | "Create New Collection" sent customers to the dashboard; search only covered one page; Edit didn't edit | Create, rename and delete on the page. Search covers all collections. Cards show product photos, pieces and an estimated value. **"Add to quote cart"** sends a whole collection to the cart, applying minimum order quantities and tier prices. |
| **Collection page** (`/collections/{id}`) | "Add Products" only offered the first 50 products; "Proceed to Enquiry" did nothing | The picker searches the whole catalogue (20 at a time, with photos, prices and minimum order quantities). Quantities below the minimum are flagged and priced at the minimum. Removed products are shown. "Add all to quote cart" works. |
| **Reports** (`/corporate-reports`) | Didn't exist (the sidebar link went nowhere; downloads were a demo file) | Period filter, cards (orders, confirmed spend, pieces delivered, average order), spend by month, most ordered products, and the order list. Three **CSV downloads**: Order history, Spending summary, Approval history. The dashboard's download panel uses them. |
| **Employees** (`/corporate-employees`) | Didn't exist (sidebar link went nowhere) | **Gift recipients:** add/edit/remove, search, department filter, CSV import (template, row errors, duplicates skipped) and export, birthdays and work anniversaries coming up in 30 days. **Team logins:** invite colleagues as Admin, Buyer or Viewer; they set their own password and share the company's orders, collections, cart and reports; roles enforced on the server; removing someone ends their access immediately. |
| **Collections pricing** | Showed cost or wrong prices | Customer prices (tiers / base price) |
| **Account, password, addresses** | Plain-text passwords, instant reset | Hashed passwords, emailed reset link |

## 6. Admin panel

- **Reports (`/admin/reports`):** replaces the three old reports, which counted product lines, had no totals, lost their filters on page 2 and included a one-click delete link.
  - **Filters:** dates, customer, product and step.
  - **Totals:** orders, order value, confirmed value, pieces, delivered.
  - **Breakdowns:** by month, by step, top customers and top products.
  - **Order list:** one row per order, linking to the order page.
  - **Exports:** CSV of orders or of product lines.
- **Store Enquiries:** order number, real customer and company, product count, status, 25 per page, and a link to the order page.
- **New pages that were missing:**
  - Order details.
  - Print invoice.
  - Abandoned carts list and details, with a reminder email to the customer. That email used to go to the old agency.
- **Store Users page:** restored, admin-only.
- **Dashboard:** works without Google Analytics keys. Before, it showed a blank page.
- **Order Tracking menu:** the old "Order Tracking List" was removed from the menu (orders are opened from Store Enquiries). The tracking page shows each product's photo.

## 7. Emails and enquiries

- **Contact Us form:** it used to crash, and the code was set to send to the old agency. It now sends to MTG's staff inbox (reply goes straight to the customer). The customer gets a short "We received your message" email. If sending fails, the customer is told and shown the phone number and email address to use instead.
- **Old Angular API endpoints removed:** `/enquiry`, `/search_or_update` and `/contact` emailed enquiries to a personal Gmail and the old agency.
- **One staff inbox setting:** quotes, contact messages and customer responses all go to the inbox set in `QUOTE_REQUEST_EMAIL`. The setting now survives the server's config cache.
- **MTG branding:** new email templates for quote requests, order updates, invoices, contact and abandoned carts. The old SilverGiftz / SilverPixelz templates were removed.

## 8. Storefront pages

- **New homepage:** introduction, 14 category tiles with images, the latest products with "From AED x · MOQ y", and a quote call-to-action.
- **Header:** 11 of the 14 menu links now go to real pages. Best Sellers, Sustainable and Branding wait for the owner.
- **Category, subcategory and brand pages:** list the right products, unknown addresses give a proper "not found", and the crashes are fixed.
- **About Us page:** rebuilt in the new design (3 Nov). The old page showed another company's text from the admin About editor; the new one explains what MTG does, how an order works (the real steps), the live product categories, and how to get in touch. Staff can edit all its wording and add a hero image in the admin (**About Us page** in the menu); categories, step names and contact details fill in automatically.
- **Budget planner:** works again (its script had the wrong file name).
- **Clean-up:** removed 9 missing style and script files from every page, and fixed old company names (Meem Industrial, HashAppz, SilverGiftz → More Than Giftz).

## 9. Stability and clean-up

- **Fewer crashes:** 0 of 165 pages crash, down from 25. Fixed pages include order processing, portfolio, brand pages, coupons and old-theme filters.
- **Fewer broken links:** broken links and assets went from 384 to 20. The remaining 20 are images missing on the server, not code.
- **Dead routes removed:** 26 broken or unsafe routes and the unbuilt Rewards section.
- **Dead code removed:** about 60 unused views, controllers and models, plus a 40 MB duplicate admin theme copy.
- **Faster lookups:** 14 database indexes added.
- **Git hygiene:** generated cache files are no longer tracked.

## 10. Database changes (all by migration)

| Migration | What it does |
|---|---|
| Password hashing (admins, customers), 2 migrations | Converts existing plain passwords |
| Enquiries order number + copy of unseen enquiries | Recovers the 7 lost quotes |
| 14 lookup indexes | Speed |
| `orders`, `order_step_states`, `order_events` | The 10-step workflow; converts the 11 existing orders |
| `jobs` | Queued emails |
| `order_invoices` | Advance and balance invoices |
| `company_employees`, `company_team_members` | Employees page: gift recipients and team logins |

9 new migrations in total. The live database is also missing some of the project's older tables (for example `cart_items`); running the migrations on the server adds them.

## 11. How the work was done

- **Backups:** a full backup of the project and database came before any change, and every later batch backed up the files it touched (`mtg-backup-…` folders).
- **Audit log:** every decision, change, test and mistake is recorded in a tamper-evident, append-only log (190 entries).
- **Testing:** every feature was tested end to end on temporary accounts and orders, which were deleted afterwards. Pages were checked on desktop and phone.
- **Deploy package:** `mtg-updated-files` holds every changed file (126), the list of files to delete, and step-by-step server instructions (`WHERE-FILES-GO.txt`).
- **Project documents:** PRD, Handoff guide and the Fixes & Updates list, all kept up to date.

## 12. Still open (details in `docs/FIXES-AND-UPDATES.md`)

**Needs the owner or the server:**
- Deploy to the live site and run the migrations.
- Replace the PayTabs and MidOcean keys.
- Turn debug pages off on the live site.
- Set up real email sending (SMTP).
- Decide who receives staff emails.
- Google login (needs the Google Cloud project owner).
- Company address and VAT TRN for invoices (FX-45).
- Real tier prices for the cap and the mug (FX-36).
- Targets for the Best Sellers, Sustainable and Branding links.
- Social media accounts and contact details.

**Code still to do:**
- Due dates and overdue alerts per step (FX-13).
- Store Enquiries filters and search (FX-14).
- Storefront footer (FX-16) and header search (FX-17).
- Redesign of the old-theme pages (FX-19).
- Wishlist and membership pages (FX-20, FX-21).
- Old brand names left in some emails (FX-25).
- Admin action links as secure forms (FX-08).
- Dashboard Branding Assets, depending on the owner's decision (FX-38).
- App timezone to UAE time (FX-44).
- Automated tests (FX-31).
- PHP / Laravel upgrade (FX-11).
