# Lewis HMS — Phase 1 Implementation Plan

## Context

The Lewis Hospitality Management System is a full-stack platform for Lewis Companies (nearly 20 restaurant brands, DC metro area). The codebase has auth scaffolding (Fortify + 2FA, Flux Pro, Spatie Permission installed) and a homepage shell, but **no domain models, migrations, routes, or Livewire components** exist yet.

**User decisions:**
- Start with Phase 1 — Foundation (core DB + remaining public pages)
- Admin layout — Flux Pro sidebar
- Real-time — Build with Reverb from Phase 3 (not polling)

---

## Phase 1 Scope

### Step 1: Core Database Schema

Run `php artisan make:migration` for all domain tables in dependency order:

**Group A — Foundational (no foreign deps)**
1. `create_locations_table` — name, address, city, state, zip, phone, email, maps_embed_url, is_primary, active
2. `create_location_hours_table` — location_id, day_of_week (tinyint 0–6), opens_at (time), closes_at (time), closed (bool)
3. `create_settings_table` — key (unique string), value (longtext), updated_at
4. `create_seo_settings_table` — page, meta_title, meta_description, og_title, og_description, og_image
5. `create_audit_logs_table` — user_id (nullable), action, model_type, model_id, old_values (json), new_values (json), ip_address

**Group B — Menu**
6. `create_menu_categories_table` — name, slug, sort_order, active
7. `create_menu_items_table` — menu_category_id, name, slug, description, price (decimal 8,2), dietary_tags (json), featured, available_always, available_from, available_until, available_days (json), sort_order, active, pos_id (nullable string)
8. `create_modifier_groups_table` — name, required, min_selections (tinyint), max_selections (tinyint)
9. `create_modifier_options_table` — modifier_group_id, name, price_adjustment (decimal 8,2), sort_order
10. `create_menu_item_modifier_groups_table` — pivot: menu_item_id, modifier_group_id

**Group C — Public-facing content**
11. `create_events_table` — location_id (nullable FK), title, slug, description (longtext), starts_at (datetime), ends_at (nullable datetime), cta_type (enum: rsvp/external_link/none), cta_label, cta_url, rsvp_enabled (bool), published (bool)
12. `create_event_rsvps_table` — event_id, name, email, party_size
13. `create_promotions_table` — location_id (nullable), title, description, type (enum), discount_value, free_item_id (nullable FK→menu_items), applicable_to (enum), starts_at, ends_at, show_on_homepage, active
14. `create_happy_hours_table` — location_id, day_of_week, starts_at (time), ends_at (time), label, active
15. `create_contact_submissions_table` — location_id (nullable), name, email, subject, message, read_at, archived_at

**Group D — Guests / Reservations (deferred to Phase 3 operationally but needed for schema)**
16. `create_guests_table` — name, email (unique), phone, dietary_preferences (json), notes, loyalty_points (int default 0)
17. `create_tables_table` — location_id, name, capacity (tinyint), section, pos_x (float), pos_y (float), shape (enum: round/square/rectangle), status (enum: available/reserved/occupied/needs_cleaning), active
18. `create_reservations_table` — location_id, table_id (nullable), guest_id (nullable), name, email, phone, date, time, party_size, special_requests, status (enum: pending/confirmed/seated/completed/no_show/cancelled), source (enum: online_form/phone/opentable/resy/walk_in), notes, reminder_sent_at

**Artisan commands to generate model + migration + factory in one shot:**
```bash
php artisan make:model Location -mf
php artisan make:model LocationHour -mf
php artisan make:model Setting -mf
php artisan make:model SeoSetting -mf
php artisan make:model AuditLog -mf
php artisan make:model MenuCategory -mf
php artisan make:model MenuItem -mf
php artisan make:model ModifierGroup -mf
php artisan make:model ModifierOption -mf
php artisan make:model Event -mf
php artisan make:model EventRsvp -mf
php artisan make:model Promotion -mf
php artisan make:model HappyHour -mf
php artisan make:model ContactSubmission -mf
php artisan make:model Guest -mf
php artisan make:model Table -mf
php artisan make:model Reservation -mf
```
Then run `php artisan make:migration create_menu_item_modifier_groups_table` separately (pivot — no model needed).

Each model gets: proper relationships, fillable/casts, factory, and seeder for dev data.

**Spatie Permission roles seeded:**
`super_admin`, `restaurant_manager`, `area_manager`, `content_editor`, `server`, `kitchen_staff`, `loyalty_member`

---

### Step 2: Public Pages — Contact & Location

**Route:** `GET /contact` → Livewire full-page component

**Livewire Components:**
- `app/Livewire/Pages/Contact.php` — renders `resources/views/livewire/pages/contact.blade.php`
  - Properties: `$locations` (all active), `$selectedLocationId`, computed `$selectedLocation`
  - Action: `submitContactForm()` — validates, saves to `contact_submissions`, fires notification mail, shows success
- `app/Livewire/LocationSelector.php` — reactive dropdown/tab that updates map + hours

**View requirements:**
- Google Maps iframe (`maps_embed_url` from selected location)
- Hours table — today highlighted, closed shown explicitly
- Click-to-call phone, mailto email
- Multi-location selector (if multiple locations active) — reactive via `wire:model`
- Contact form: name, email, subject, message

---

### Step 3: Public Pages — Events & Promotions

**Routes:**
- `GET /events` → Livewire full-page
- `GET /events/{slug}` → Livewire full-page (event detail)

**Livewire Components:**
- `app/Livewire/Pages/Events.php`
  - Properties: `$filter` (enum: all/events/happy_hours/seasonal), upcoming events eager-loaded, active promotions
  - Events: sorted by `starts_at`; past events excluded via `where('starts_at', '>=', now())`
  - Promotions: `where('active', true)->where('ends_at', '>=', now())`
- `app/Livewire/Pages/EventDetail.php`
  - Loads event by slug; 404 if not published
  - RSVP form shown when `$event->rsvp_enabled` — validates, saves `EventRsvp`, sends confirmation mail
- `app/Livewire/PromotionsBar.php` — reusable strip for homepage + events page

**View requirements:**
- Event card grid (image via Spatie Media Library, title, date, description, CTA)
- Promotions section above event list (auto-hidden when none active)
- Happy hour section — recurring schedule display
- Alpine.js filter tabs with transition
- RSVP form modal (conditional)

---

### Step 4: Public Pages — Gallery

**Route:** `GET /gallery` → Livewire full-page

**Livewire Component:**
- `app/Livewire/Pages/Gallery.php`
  - Properties: `$collection` (all/food/ambience/events/behind_the_scenes)
  - Loads media via Spatie Media Library collections
  - Filters via `wire:model` on collection select

**View requirements:**
- Responsive masonry grid — 3 cols desktop, 2 tablet, 1 mobile (CSS `columns` or `grid`)
- Collection filter tabs with Alpine.js transition
- Lightbox — Alpine.js with `$dispatch` to open; full-screen, prev/next, swipe on mobile (no JS library dependency — use Alpine + CSS)
- All images lazy-loaded (`loading="lazy"`), WebP via Spatie responsive images

---

### Step 5: Public Pages — About

**Route:** `GET /about` → standard Blade view (no Livewire needed — static content)

**View requirements:**
- Origin story (rich text from settings, editable later via admin)
- Team section (optional — toggled via `settings` key `about.show_team`)
- Awards/press mentions (optional — toggle)
- Values/philosophy section
- All sections: read from `settings` table; show/hide flags stored as `about.section_name.visible`

---

### Step 6: Homepage Completion

Wire up the remaining dynamic Livewire components on the existing homepage:

- `app/Livewire/FeaturedDishes.php` — queries `MenuItem::where('featured', true)->where('active', true)->limit(4)`; eager loads media
- `app/Livewire/UpcomingEvents.php` — queries next 2–3 published events
- `app/Livewire/InstagramFeed.php` — reads from cache key `instagram.feed`; falls back to empty state gracefully; Instagram Basic Display API fetched via scheduled job every 6 hours

**Scheduled Job:**
- `php artisan make:command RefreshInstagramFeed` — fetches latest 6 posts, stores to cache for 6 hours
- Registered in `routes/console.php`: `Schedule::command('instagram:refresh')->everySixHours()`

---

### Step 7: Menu Page Polish

Enhance the existing menu display:

- `app/Livewire/Pages/Menu.php` (if not already Livewire) — add:
  - `$search` property — Alpine.js client-side search filtering (no server round-trip; `x-show` on items matching search)
  - `$activeTags` (array) — dietary tag filter via `wire:model`
  - Seasonal availability scoping: `available_always OR (available_from <= today AND available_until >= today)`
- PDF menu download: `GET /menu.pdf` → controller action generating PDF via `barryvdh/laravel-dompdf` or `spatie/laravel-pdf`; cached as static file, invalidated when menu items change

---

## Factories & Seeders

Each model gets a factory. DatabaseSeeder (dev only) seeds:
- 1 primary location with 7 days of hours
- 4 menu categories with 5–8 items each (2–3 featured), including dietary tags
- 2–3 modifier groups attached to relevant items
- 2 upcoming events (1 with RSVP, 1 with external link)
- 1 active promotion
- 3 happy hour schedules
- Gallery images using placeholder URLs

---

## Tests (Pest)

Per CLAUDE.md: every change must be tested. Minimum viable coverage for Phase 1:

```bash
php artisan make:test --pest Pages/ContactPageTest
php artisan make:test --pest Pages/EventsPageTest
php artisan make:test --pest Pages/GalleryPageTest
php artisan make:test --pest Pages/AboutPageTest
php artisan make:test --pest Pages/MenuPageTest
php artisan make:test --pest --unit Models/MenuItemTest
php artisan make:test --pest --unit Models/EventTest
```

Coverage: page renders, form validation, DB records created, scopes (past/upcoming, published-only, seasonal availability).

---

## Critical File Paths

| Type | Path |
|---|---|
| Domain migrations | `database/migrations/` (18 new files) |
| Domain models | `app/Models/` (17 new files) |
| Domain factories | `database/factories/` (17 new files) |
| Public Livewire pages | `app/Livewire/Pages/Contact.php`, `Events.php`, `EventDetail.php`, `Gallery.php` |
| Reusable components | `app/Livewire/FeaturedDishes.php`, `UpcomingEvents.php`, `InstagramFeed.php`, `PromotionsBar.php`, `LocationSelector.php` |
| Blade views | `resources/views/livewire/pages/` — contact, events, events/show, gallery, about |
| Routes | `routes/web.php` — add `/contact`, `/events`, `/events/{slug}`, `/gallery`, `/about`, `/menu.pdf` |
| Scheduled commands | `app/Console/Commands/RefreshInstagramFeed.php` |
| Tests | `tests/Feature/Pages/` (5 files), `tests/Unit/Models/` (2 files) |
| Seeders | `database/seeders/` — LocationSeeder, MenuSeeder, EventSeeder, GallerySeeder |

---

## Verification

1. Run all migrations: `php artisan migrate:fresh --seed`
2. Visit `/contact`, `/events`, `/events/{slug}`, `/gallery`, `/about` — confirm pages load
3. Submit contact form — confirm `contact_submissions` record created
4. Submit RSVP form — confirm `event_rsvps` record created and email queued
5. Run tests: `php artisan test --compact`
6. Run Pint: `vendor/bin/pint --dirty --format agent`

---

## PostgreSQL-Specific Enhancements

The application runs on PostgreSQL — the following native capabilities will be used where appropriate rather than workarounds:

| Feature | Where applied |
|---|---|
| **`jsonb`** (not `json`) | `dietary_tags`, `available_days`, `old_values`/`new_values` (audit_logs), `loyalty_preferences` — use `->jsonb()` in migrations for indexed, queryable JSON |
| **Point / geography columns** | `locations` — add `latitude` (decimal 10,7) and `longitude` (decimal 10,7) for future proximity queries; no PostGIS extension required for simple lat/lng math |
| **`tsrange`** | `menu_items` seasonal window — consider `available_range tsrange` as an alternative to `available_from`/`available_until` date pair; allows native range overlap operator (`&&`) |
| **Full-text search** | Menu item search — use `whereRaw("to_tsvector('english', name || ' ' || description) @@ plainto_tsquery(?)", [$search])` instead of `LIKE %search%`; add a GIN index on the tsvector expression in migration |
| **GIN indexes** | `dietary_tags jsonb` column — `$table->rawIndex("dietary_tags", "menu_items_dietary_tags_gin", "USING gin")` for efficient `@>` containment queries |
| **`time` columns** | `location_hours.opens_at`, `closes_at`, `happy_hours.starts_at`/`ends_at` — PostgreSQL native `time` type handles comparison operators correctly |
| **Enum types** | Use Laravel `->enum()` for database-level enforcement on `status`, `source`, `cta_type`, `shape` columns |

**Migration notes:**
- Replace `->json()` with `->jsonb()` for all JSON columns
- Add lat/lng to `locations` migration
- Add GIN index on `menu_items.dietary_tags`
- Add a tsvector GIN expression index on `menu_items` for full-text search

---

## Out of Scope for Phase 1

- Admin CMS (Phase 2)
- POS, KDS, Order management (Phase 3)
- Reverb / WebSockets setup (Phase 3)
- Inventory, staff, CRM, loyalty (Phase 3–4)
- AI features (Phase 5)
