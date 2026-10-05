# BK Secret Shop — Navigation & Menu Structure (v1.0)

Companion to `FEATURES.md`. This document specifies the **two separate navigation systems** in the
app — the admin's DB-driven sidebar menu (with categories) and the storefront's navigation bar
(top nav + product category tabs) — so Claude Code can build both without conflating them.

---

## 0. Two systems — do not conflate

| | Admin sidebar | Storefront navbar |
|---|---|---|
| Where | `/admin/*`, guard `web` | public site, guard `customer` |
| Source of truth | **`nav_menus` table** — DB-driven, admin-editable | mostly fixed/config, except category tabs which read the live `categories` table |
| Grouping concept | **menu categories** — free-text labels used to group sidebar links (e.g. "Catalog", "Sales") | **product categories** — real catalog taxonomy (e.g. "Model Kits") used to filter the shop grid |
| Managed via | a "Menus" admin screen (this doc, §2) | product/category admin CRUD (already covered by `FEATURES.md` §1–2) |

These are **unrelated concepts that happen to both be called "category."** `nav_menus.category` is a
grouping label for sidebar sections; `categories` (the product taxonomy table) is what customers
shop by. Keep them as two separate tables — never merge them.

---

## 1. Admin sidebar — `nav_menus` (DB-driven, category-grouped)

This is the "DB-driven sidebar nav" already called out as part of the LaravelAdminV3 base template
(`hobby-shop-project-plan.md` §Confirmed Stack). The template ships this mechanism already — **use
it**, don't rebuild it from scratch. The behavior to preserve, demonstrated in the earlier
`laraveladminv4.0.1` reference mockup (`menu-data.js` + `nav.js` + `menus.html` — logic reference
only, **not** the visual design; the actual UI must be Design 2):

- The sidebar renders itself entirely from the `nav_menus` rows, **grouped by `category`**, each
  group sorted by `order` within itself, groups themselves ordered by first-appearance /
  an explicit group order.
- A menu item with an empty/null `category` renders with no group label above it (this is how
  "Dashboard" sits ungrouped at the top).
- Add/edit/delete a row → every admin page's sidebar reflects it immediately (no separate
  "categories" table needed — the admin's "Category" field on the add/edit form is a **combobox**
  that lists the distinct `category` values already in use, so staff can reuse an existing group or
  type a new one to create it on the fly).
- Active-state highlighting matches on the route/URL (including a `#hash` sub-page such as
  `products.html#preorder` for Preorder Items living under Products).

### 1.1 `nav_menus` schema

| Column | Notes |
|---|---|
| `id` | PK |
| `title` | display label, e.g. "Orders & Payments" |
| `icon` | icon key/slug (map to an inline SVG set — see `icon` values in §1.2) |
| `category` | free-text group label; `null`/`""` = ungrouped |
| `route` / `url` | named route (preferred over a raw path so URL changes don't orphan menu rows) |
| `order` | sort weight within its category, lower = higher |
| `group_order` | (optional) explicit sort weight for the category groups themselves, so group order doesn't depend on insertion order |
| `permission` | nullable — gate visibility by the existing roles/permissions system already in LaravelAdminV3; a menu item with no permission set is visible to any authenticated admin |
| `is_active` | soft-disable a menu item without deleting it |
| `parent_id` | nullable self-reference — reserved for a future flyout/sub-item if a category ever needs nesting beyond one level; not required for the v1 seed data below, which is flat within each category |

### 1.2 Seed data — BK Secret Shop admin sidebar

This is the confirmed structure (matches the locked Design 2 admin build) to seed on migration.
Icons are inline SVGs already defined in the Design 2 admin asset bundle — reuse those paths, keyed
as below.

```
category: ""            (ungrouped)
  1. Dashboard                    icon: dashboard        route: admin.dashboard

category: "Catalog"
  1. Products & Variants          icon: catalog           route: admin.products.index
  2. Preorder Items               icon: preorder          route: admin.products.index (#preorder tab)

category: "Tournaments"
  1. Tournaments                  icon: tournaments        route: admin.tournaments.index

category: "Sales"
  1. Orders & Payments            icon: orders            route: admin.orders.index
  2. Vouchers                     icon: vouchers          route: admin.vouchers.index
  3. Store Credit                 icon: store-credit      route: admin.store-credit.index
  4. QR Scanner                   icon: qr-scanner        route: admin.qr-scanner.index

category: "People & Content"
  1. Customers                    icon: customers         route: admin.customers.index
  2. News & Announcements         icon: news              route: admin.news.index

category: "Configuration"
  1. Settings                     icon: settings          route: admin.settings.index
  2. Menus                        icon: menus             route: admin.menus.index   (this screen, §2)
```

Note vs. the original mockup's admin footer label ("LaravelAdminV4 base") — since the repo actually
cloned is **LaravelAdminV3**, correct that label in the real build (see `FEATURES.md` §0.2).

### 1.3 Reconcile with the template's existing admin resources

LaravelAdminV3 ships its own built-in admin resources (Users, Roles/Permissions, and likely its own
existing "Menus" management screen, mail/SMTP settings, etc. — confirm exact list once the repo is
explored in Claude Code). Do **not** duplicate what already exists:

- If the template already has a working nav-menu management screen wired to a `nav_menus`-equivalent
  table, extend that table/screen with the "Category" combobox behavior in §1 (if not already
  present) and seed it with §1.2's rows, rather than building a second, competing menu system.
- Add "Users", "Roles & Permissions" (or whatever the template's existing auth-admin screens are
  named) as their own category — e.g. `category: "Admin Access"` — alongside the groups in §1.2,
  positioned wherever the template convention already places them.

---

## 2. Admin "Menus" screen (manage `nav_menus`)

A dedicated admin page (seeded into the sidebar itself per §1.2, under "Configuration") for staff to
add/edit/reorder/delete sidebar entries without a deploy:

- **Add menu item** form: Title, Link (route or URL), Category (combobox — searches existing
  category values, or free-types a new one), Icon (picker from the available icon set), Order.
- **All menu items** table: Order, Title, Category, Link, actions (edit/delete), grouped/sortable by
  category for easy scanning.
- Changing a row updates the sidebar for all admins immediately (no cache to bust, or bust it
  server-side on save if the nav list is cached).
- Gate this screen itself behind a permission (e.g. `manage-navigation`) — it can rearrange every
  other admin's access paths, so don't leave it open to all staff roles by default.

---

## 3. Storefront navigation bar

### 3.1 Primary nav (top bar, all public/customer pages — mockup: `design2/index.html` `<nav class="main">`)

Fixed link set (not DB-driven — five links, no admin screen needed for this level):

```
Shop            → /
New Arrivals    → /news
Preorder        → /preorder
Tournaments     → /tournaments
Account         → /account
```

Right-side icon actions: Account (icon shortcut, duplicates the Account link), Cart (with live item
count badge), theme toggle (visitor's own light/dark preference — independent of the admin's
site-wide brand theme, `FEATURES.md` §13).

### 3.2 Mobile drawer

Same five primary links, plus:

- **Rewards/points line** at the top of the drawer (customer's current loyalty/store-credit balance
  at a glance — confirm during build which balance this surfaces, given the §9/§14 reconciliation
  note in `FEATURES.md`).
- Cart link.
- **Admin panel** link — a shortcut into `/admin/login` for staff who are browsing the storefront.
  Keep this low-key (e.g. small text link at the bottom of the drawer) since it's a convenience for
  staff, not a customer-facing feature.

### 3.3 Footer navigation

```
Shop        → category quick-links (see §4 — must stay in sync with real `categories`, not hardcoded)
Help        → Track an order, Shipping & returns, Contact us
Newsletter  → email opt-in form (ties into the promo-email opt-in in FEATURES.md §4)
```

The mockup's footer "Shop" column lists category names as plain static text — in the real build these
must be real links to the catalog filtered by that category (`/?category=model-kits` or similar), and
the list itself should come from the same `categories` table as §4, not be duplicated as static markup.

---

## 4. Storefront category tabs (product taxonomy — NOT `nav_menus`)

The horizontal tab bar on the shop/catalog page (mockup: `#catTabs`) and the "grouped by category"
sections on the Preorder page are driven by the **product `categories` table** (`FEATURES.md` §1),
not by the admin's `nav_menus`:

```
All (pseudo-category, always first — shows every product)
Model Kits
RC & Diecast
Trading Cards
Paints & Tools
Display Cases
```

- These must be **live** — pulled from whatever categories actually exist in the `categories` table
  (admin-manageable via the Catalog admin screens in `FEATURES.md`), not hardcoded to this list. The
  list above is the current seed data reflected in the mockup, not a fixed enum.
  - "Display Cases" appears in the storefront category tabs in the mockup but was not seen with an
    assigned product in the sample data reviewed — keep it as a real, empty-until-stocked category
    rather than dropping it.
- A category with zero active products can still show as a tab per admin preference (confirm during
  build), or be auto-hidden — reasonable default: show only categories with at least one active
  product, to avoid dead-end tabs.
- The inline search box next to the tabs (`#catalogSearch`) filters within the currently selected
  category tab, not the whole catalog independent of it.
- Reused elsewhere: product cards' category label, the Preorder page's per-category grouping, and the
  footer "Shop" column (§3.3) all read from this same table — one source of truth, no duplicated
  category strings anywhere in the codebase.

---

## Build reference

- Admin `nav_menus` grouping/rendering behavior: `laraveladminv4.0.1/menus.html` +
  `laraveladminv4.0.1/assets/menu-data.js` + `laraveladminv4.0.1/assets/nav.js` in the mockup
  (interaction/data-shape reference only — restyle to Design 2, do not port that folder's visuals).
- Admin sidebar's actual confirmed category/item structure to seed: `design2/admin/index.html`
  `<aside class="admin-sidebar">` (and consistent across every `design2/admin/*.html` page).
- Storefront top nav, drawer, footer: `design2/index.html` `<nav class="main">`, `#drawer`,
  `<footer>`.
- Storefront category tabs: `design2/index.html` `#catTabs`; category grouping reused in
  `design2/preorder.html` and `design2/news.html`.
