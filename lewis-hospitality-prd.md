# Lewis Hospitality Management System — Product Requirements Document

**Project:** Lewis Hospitality Management System (Lewis HMS)
**Stack:** Laravel 13, Livewire 4, Flux UI Pro, Alpine.js, Tailwind CSS v4, PostgreSQL
**Deployment:** Laravel Cloud
**Author:** Kenneth Ekow Inkum
**Version:** 2.0
**Date:** March 2026

---

## Table of Contents

1. [Overview](#1-overview)
2. [System Philosophy](#2-system-philosophy)
3. [Users & Roles](#3-users--roles)
4. [Current State of Demo](#4-current-state-of-demo)
5. [Tier 1 — Critical Features](#5-tier-1--critical-features)
6. [Tier 2 — Nice-to-Have Features](#6-tier-2--nice-to-have-features)
7. [Tier 3 — AI-Powered Features](#7-tier-3--ai-powered-features)
8. [Third-Party Integrations](#8-third-party-integrations)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [Database Schema](#10-database-schema)
11. [Routes Reference](#11-routes-reference)
12. [Implementation Status](#12-implementation-status)

---

## 1. Overview

The Lewis Hospitality Management System (Lewis HMS) is a custom-built, full-stack platform serving as both the public digital presence and the internal operating backbone for Lewis Companies — a hospitality group operating nearly 20 restaurant brands across the DC metro area and internationally. Brands include Green Pig Bistro, Buena Vida Gastrolounge, Dudley's Sport and Ale, Barley Mac, Stan's Restaurant, The Harbour Grille, and Fire Works Pizza.

The system is built on the TALL stack (Tailwind CSS v4, Alpine.js, Livewire 4, Laravel 13) with Flux UI Pro as the admin component library, deployed on Laravel Cloud. It is designed to act as the **central nervous system** of restaurant operations — unifying front-of-house, back-of-house, and business intelligence into a single platform that reduces manual work, eliminates data silos, and continuously improves the guest experience.

---

## 2. System Philosophy

Most restaurant software forces operators to cobble together 5 to 10 disconnected tools — a POS here, a reservation system there, a spreadsheet for inventory, another dashboard for analytics. Each system speaks its own language. Data lives in silos. Staff waste time on double entry. Managers make decisions on stale reports.

Lewis HMS takes the opposite approach: **one platform, one source of truth, built specifically for how the Lewis Companies operate.**

The three layers of the system are:

- **Front-of-House** — Guest-facing websites, online ordering, reservations, QR menus, loyalty, and CRM
- **Back-of-House** — POS, kitchen display, inventory, staff scheduling, supplier management, and table management
- **Business Intelligence** — Real-time reporting, cost analytics, AI-powered forecasting, and automated insights

Each layer feeds the others. A reservation creates a table allocation. A completed order updates inventory. Sales data feeds the demand forecast. The forecast drives staff scheduling. Everything connects.

---

## 3. Users & Roles

### 3.1 Public Guest (Unauthenticated)

Visits the restaurant website to browse menus, make reservations, view events, place online orders, and find location information. No login required. Can optionally create a loyalty account.

### 3.2 Loyalty Member (Authenticated Guest)

A registered guest with a loyalty account. Can view their points balance, order history, earned rewards, and personalised promotions. Managed through the guest-facing portal, separate from the staff admin.

### 3.3 Kitchen Staff

Back-of-house only. Sees the Kitchen Display System (KDS) — incoming orders, preparation queue, and order status. No access to financial data, guest records, or settings.

### 3.4 Floor Staff / Server

Takes orders, manages tables, processes payments via the POS, and views their assigned tables. Limited read access to reservations and the floor plan for their shift.

### 3.5 Content Editor

Front-of-house content management only — menus, events, gallery, promotions. Cannot access POS data, financial reports, or staff records.

### 3.6 Restaurant Manager

Full operational access for their assigned location — POS data, inventory, staff scheduling, reservations, reporting, and content. Cannot access system settings or other locations.

### 3.7 Area Manager / Regional Supervisor

Cross-location read access for reporting and performance monitoring. Cannot modify content or operational settings at individual locations.

### 3.8 Super Admin

Full platform access across all locations — system settings, integration credentials, user management, billing, and audit logs. Intended for the platform developer or a designated technical lead.

---

## 4. Current State of Demo

The current demo deployment at `lewisrestaurant-main-ekv5k2.laravel.cloud` covers the public-facing shell. The following reflects what is built versus what this PRD specifies.

| Feature | Status |
|---|---|
| Homepage / hero section | ✅ Built |
| Menu display (public) | ✅ Built |
| Reservations page (public) | ✅ Built |
| Contact / location page | ❌ Not built |
| Events & promotions (public) | ❌ Not built |
| Gallery (public) | ❌ Not built |
| Menu management (admin CMS) | ❌ Not built |
| POS system | ❌ Not built |
| Kitchen Display System | ❌ Not built |
| Order management | ❌ Not built |
| Inventory management | ❌ Not built |
| Staff scheduling | ❌ Not built |
| Table & floor management | ❌ Not built |
| CRM & loyalty | ❌ Not built |
| Reporting & analytics | ❌ Not built |
| AI-powered features | ❌ Not built |

---

## 5. Tier 1 — Critical Features

These are non-negotiable for a production-grade hospitality management system. They form the operational core that everything else depends on.

---

### 5.1 Public-Facing Website

#### 5.1.1 Homepage

**Requirements:**
- Full-width hero section — background image or video, restaurant name, tagline, and two primary CTAs: **Reserve a Table** and **Order Online**
- Featured dishes section — 3 to 4 signature items with image, name, and short description; pulled from menu CMS with `featured = true`
- About / brand story section — short narrative with image; editable from admin
- Events & promotions strip — next 2 to 3 upcoming events or active promotions; auto-hidden when none are active
- Instagram feed embed — latest 6 posts via Instagram Basic Display API; cached every 6 hours via scheduled job
- Guest testimonials — manually curated quotes, manageable from admin
- Location and hours strip — address, hours, phone, link to full contact page
- Footer — logo, navigation, social icons, copyright

**Livewire Components:** `FeaturedDishes`, `UpcomingEvents`, `InstagramFeed`

---

#### 5.1.2 Menu Page

**Requirements:**
- Menu organised by categories — Starters, Mains, Grill, Sides, Desserts, Drinks
- Each item shows: name, description, price, optional image, dietary tags
- Dietary tag filter bar — Vegetarian, Vegan, Gluten-Free, Halal, Spicy, Contains Nuts
- Category tab navigation with smooth scroll
- Seasonal / timed availability — items visible only during configured date ranges or days of week
- PDF menu download — auto-generated from live CMS data, cached and served as a static file
- Alpine.js client-side search — filters items by name as guest types, no server round-trip

**Livewire Components:** `MenuListing`, `MenuSearch`

---

#### 5.1.3 Reservations Page

**Requirements:**
- Embedded third-party widget — OpenTable or Resy; embed code stored in settings and rendered via Blade component
- Direct booking fallback form (Livewire) — name, email, phone, date, time, party size, special requests; stored to `reservations` table; confirmation email via Laravel Mail
- Private dining / large group inquiry form — for groups of 15 or more; additional field for event type; triggers staff notification email
- Location selector — when multiple locations exist, guest selects which location they are booking

**Livewire Components:** `ReservationForm`, `PrivateDiningForm`, `LocationSelector`

---

#### 5.1.4 Events & Promotions Page

**Requirements:**
- Event cards grid — upcoming events sorted by date; past events auto-archived
- Each card shows: image, title, date/time, short description, CTA
- Event detail page — full description, event gallery, date/time/location, CTA
- Active promotions section — above events list; auto-hides expired promotions
- Happy hour section — recurring schedule with per-day times
- Filter by type — Events, Happy Hours, Seasonal Offers
- Optional RSVP form per event — toggled in admin; collects name, email, party size; sends confirmation

**Livewire Components:** `EventsListing`, `PromotionsBar`, `RsvpForm`

---

#### 5.1.5 Gallery Page

**Requirements:**
- Responsive masonry grid — 3 columns desktop, 2 tablet, 1 mobile
- Collections: Food, Ambience, Events, Behind the Scenes
- Collection filter tabs with Alpine.js transition
- Lightbox on click — full-screen with next/previous and swipe support on mobile
- Images via Spatie Media Library — responsive breakpoints, WebP output, lazy loading

**Livewire Components:** `GalleryGrid`; lightbox via Alpine.js

---

#### 5.1.6 Contact & Location Page

**Requirements:**
- Address with Google Maps Embed API iframe
- Opening hours per day — today's hours highlighted; "Closed" shown explicitly
- Click-to-call phone, mailto email link
- Multi-location selector — updates map, hours, address, and phone reactively
- General contact form — name, email, subject, message; stored to `contact_submissions`; triggers staff notification
- Social media links

**Livewire Components:** `ContactForm`, `LocationSelector`

---

#### 5.1.7 About Page

**Requirements:**
- Restaurant origin story — rich text, editable from admin
- Team section — optional; staff photos, names, roles; manageable from admin
- Awards and press mentions — optional; logos or text references
- Values / philosophy section — short statements on food, sourcing, and hospitality
- All sections individually toggled show/hide from admin without deleting content

---

### 5.2 Point of Sale (POS)

The POS is the operational heart of the system. It processes all transactions, feeds the kitchen, and generates the financial data that drives reporting.

**Requirements:**
- Order taking — by table, by seat, or by counter; add items from the live menu with modifiers and special instructions
- Multiple payment methods — card (Stripe Terminal), cash, mobile (Apple Pay, Google Pay), contactless; split bills by item or by amount
- Order types — Dine-in, Takeout, Delivery
- Discount and promotion application — manual discounts, pre-configured promotions, loyalty reward redemption
- Void and refund management — with manager approval and reason logging
- Receipt options — print, email, or SMS
- Shift management — open and close cash drawer, reconcile end-of-shift totals
- Offline mode — POS continues to function without internet; queues transactions for sync on reconnect
- Hardware support — tablet-based (iPad or Android); optional receipt printer and card reader via Stripe Terminal SDK

**Livewire Components:** `PosTerminal`, `OrderCart`, `PaymentProcessor`, `ShiftReconciliation`

---

### 5.3 Order Management

**Requirements:**
- Real-time order flow — Table → Kitchen → Ready → Served; status visible to floor staff and kitchen simultaneously via Laravel Reverb
- Order status — Pending, In Preparation, Ready, Served, Completed, Voided
- Order modifications — add or remove items before kitchen confirmation; changes after confirmation require manager approval and generate a KDS alert
- Order timeline — timestamped log of each status change per order
- Multi-course support — starters, mains, and desserts fired separately; floor staff controls course timing
- Delivery order integration — orders from third-party platforms routed into the same queue with source label

**Livewire Components:** `OrderBoard`, `OrderDetail`, `CourseController`

---

### 5.4 Kitchen Display System (KDS)

**Requirements:**
- Real-time ticket display — new orders appear instantly via Laravel Reverb; no page refresh
- Ticket layout — table number, order type, items with modifiers, special instructions, time elapsed since order placed
- Colour-coded urgency — amber at a configurable threshold (default: 8 minutes), red at a second threshold (default: 12 minutes)
- Item-level completion — kitchen staff marks individual items as prepared; ticket auto-closes when all items are done
- Course firing — floor staff sends "fire" signal for next course from POS; KDS highlights newly fired items
- Station routing — items routed to specific kitchen stations based on item category (e.g. grill station, cold station, bar)
- Order recall — recently bumped tickets can be recalled within a configurable window (default: 5 minutes)

**Livewire Components:** `KdsBoard`, `KdsTicket`; real-time via Laravel Reverb

---

### 5.5 Menu Management (Admin CMS)

**POS Relationship — Design Decision:**

The web CMS and the POS are intentionally decoupled in Phase 1. The POS handles live transactions; the CMS handles guest-facing presentation. In Phase 1, staff maintain both — a known operational trade-off resolved in Phase 2 via POS API sync (see Section 8).

**Requirements:**
- Category CRUD — create, rename, reorder (drag-and-drop), delete with item-count warning
- Item CRUD — name, description, price, category, image, dietary tags (multi-select), featured flag, availability (always / date range / days of week), active toggle, `pos_id` (nullable — populated by Phase 2 sync)
- Item modifiers — define modifier groups (e.g. "Cooking preference": Rare, Medium, Well Done) and attach to items; used by POS during order taking
- Combo / set meal builder — define fixed-price combos from existing items
- Bulk actions — activate/deactivate multiple items, reassign categories
- Drag-and-drop reorder within categories
- Image upload via Spatie Media Library with responsive variant auto-generation
- "View on site" preview link from each item

**Livewire Components:** `MenuCategoryManager`, `MenuItemForm`, `MenuItemTable`, `ModifierGroupForm`, `ComboBuilder`

---

### 5.6 Inventory & Supply Chain

**Ingredient Management:**
- Ingredient catalogue — name, unit of measure, current stock level, minimum threshold, cost per unit, supplier
- Stock alerts — notification when stock falls below minimum threshold; visible in dashboard and sent via email
- Wastage logging — record discarded stock with reason (spoilage, prep waste, spillage); feeds waste reporting

**Recipe Management:**
- Link menu items to recipes — define ingredients and quantities consumed per item sold
- Automatic stock deduction — when an order is completed, ingredient stock reduced per recipe definition
- Food cost calculation — auto-calculated cost per dish based on current ingredient prices

**Purchase Orders:**
- Create and send purchase orders to suppliers from the admin panel
- PO tracks: supplier, items, quantities, expected delivery date, status (Draft, Sent, Received, Partial)
- On marking Received, stock levels automatically incremented
- Partial delivery support — individual line items marked received independently

**Supplier Management:**
- Supplier profiles — name, contact, product list, lead time, payment terms
- Supplier performance — average delivery time and order accuracy calculated from PO history

---

### 5.7 Staff & Workforce Management

**Staff Profiles:**
- Employee records — name, role, contact details, employment type, hourly rate, emergency contact
- Encrypted document storage — employment contracts, ID copies via Spatie Media Library

**Shift Scheduling:**
- Weekly schedule builder — drag-and-drop shifts onto a calendar grid per location
- Conflict detection — flags overlapping shifts or insufficient rest time
- Schedule publishing — staff notified by email when schedule is published or changed
- Shift swap requests — staff requests swap; manager approves from admin

**Attendance:**
- Clock-in / clock-out via a dedicated tablet at the location
- Late arrival and early departure auto-flagged against schedule
- Attendance report per staff member and per period

**Payroll:**
- Hours summary export per pay period with overtime calculation
- CSV export compatible with ADP and Gusto
- Direct payroll API integration (Phase 2)

---

### 5.8 Reservations & Table Management

**Floor Plan:**
- Visual drag-and-drop floor plan editor — place and label tables, define capacity per table, organise by section (Main Dining, Patio, Bar)
- Table status — Available, Reserved, Occupied, Needs Cleaning; colour-coded on the live floor plan view

**Reservations:**
- Online booking via embedded OpenTable / Resy widget or direct Livewire form
- Walk-in entry directly from the floor plan view
- Waitlist — when no tables are available, guests added with estimated wait; SMS notification via Twilio when table is ready
- Guest notes — birthday, allergies, VIP status attached to any reservation
- Automated confirmation and 24-hour reminder emails via Laravel Mail

**Livewire Components:** `FloorPlanEditor`, `FloorPlanView`, `ReservationManager`, `WaitlistManager`

---

### 5.9 Customer Relationship Management (CRM)

**Guest Profiles:**
- Auto-created on first reservation or loyalty signup
- Profile shows: visit history, total spend, average spend per visit, dietary preferences, staff notes, loyalty balance
- Manual profile creation for walk-in regulars

**Loyalty Programme:**
- Points earning — configurable points per dollar; applied automatically when a loyalty member pays via POS
- Rewards catalogue — define rewards with points cost (e.g. free starter: 200 points)
- Redemption — staff looks up member by email or phone at POS and applies reward
- Points ledger — full history of earned and redeemed points
- Guest portal — loyalty members log in to view balance, history, and available rewards

**Promotions & Targeting:**
- Promotion builder — percentage discount, fixed amount, free item, BOGO; valid date range, usage limit
- Audience targeting — all guests, loyalty members only, guests inactive for X days, guests with X+ visits
- Promotion codes — optional code-based redemption for marketing campaigns

---

### 5.10 Reporting & Analytics

**Sales Reports:**
- Revenue breakdown by category, item, order type, payment method, and location
- Average order value, covers per day, revenue per cover
- Peak hours heatmap — hourly revenue and cover count across days of the week
- Period comparison — current vs previous period; current vs same period last year

**Food Cost & Margins:**
- Theoretical food cost — from recipe definitions and sales volume
- Actual food cost — from purchase orders and wastage logs
- Variance report — theoretical vs actual; flags high-variance items
- Gross profit margin per item and per category

**Staff Performance:**
- Sales per server per shift and per period
- Average table turn time per server
- Upsell rate per server

**Operational Reports:**
- Table utilisation rate
- Reservation vs walk-in ratio
- Cancellation and no-show rate
- Inventory turnover rate per ingredient

**Export:** All reports exportable to CSV and PDF; scheduled report email delivery configurable per report

---

### 5.11 Security & Compliance

- HTTPS enforced via Laravel Cloud SSL
- PCI DSS compliance — card data handled exclusively by Stripe; only tokens stored in the application
- Laravel Fortify authentication with rate limiting and brute force protection
- Spatie Laravel Permission — role checks on every route, Livewire action, and API endpoint
- CSRF protection on all forms
- Input sanitisation on all rich text fields
- Audit log — every admin and operational action recorded with user, timestamp, before/after values, and IP address
- Guest data deletion on request — GDPR / CCPA compliance; configurable data retention policy
- Encrypted storage for sensitive staff documents

---

## 6. Tier 2 — Nice-to-Have Features

Planned for Phase 3 and beyond. These differentiate a good system from a great one.

---

### 6.1 QR Code Self-Ordering

- QR code per table generated from the floor plan; guest scans to open the menu on their phone
- Guest places order from their phone — goes straight to KDS; no server required for order taking
- Guest can add items throughout the meal (additional rounds, desserts)
- Optional: guest pays via QR flow using a Stripe payment link; receipt emailed automatically

---

### 6.2 Third-Party Delivery Integration

- Integration with Uber Eats, DoorDash, and Grubhub via Otter or Deliverect aggregator middleware
- Incoming delivery orders routed into the same order queue as direct orders, labelled with source
- Menu sync — push menu updates to delivery platforms from the web CMS
- Delivery performance tracking — acceptance rate, average prep time, cancellation rate per platform

---

### 6.3 Multi-Branch / Franchise Management

- Centralised admin with location-level permissions
- Standardised menu templates — head office defines a base menu; locations can add local items but cannot modify standardised items without approval
- Cross-location consolidated reporting — aggregate revenue, food cost, and staff performance in a single view
- Brand compliance monitoring — flags locations with content deviating from approved brand guidelines

---

### 6.4 Financial & Accounting Integration

- QuickBooks and Xero integration — daily sales summaries pushed automatically as journal entries
- Invoice scanning — attach supplier invoices to purchase orders; OCR pre-fills key fields (vendor, amount, date, items)
- Budgeting — set monthly revenue and cost targets per location; actual vs budget tracked in the reporting dashboard

---

### 6.5 Recipe & Food Costing Tools

- Full recipe card — method, photos, and plating notes; used for staff training and kitchen consistency
- Portion cost calculator — yield percentage and prep waste inputs to get accurate cost per portion
- Menu engineering matrix — plots all items on a profitability vs popularity matrix (Stars, Plowhorses, Puzzles, Dogs); guides menu optimisation decisions

---

### 6.6 Feedback & Review Management

- Post-visit feedback form — sent automatically 2 hours after a reservation is marked Completed; 5-star scale with optional comment
- Review aggregation — Google and Yelp reviews pulled via API into a single inbox in admin
- Reply to Google reviews directly from the admin panel
- Sentiment trend — average rating over time; flagged negative reviews surfaced in the daily dashboard

---

### 6.7 IoT Device Integration

- Smart cold storage sensors — temperature alerts when readings exceed safe thresholds; logged for compliance records
- Smart scales — link to ingredient measurement during prep; auto-log portion weights for yield tracking

---

## 7. Tier 3 — AI-Powered Features

Game-changers that make the system an intelligent platform. Planned for Phase 4 using PrismPHP as the Laravel AI integration layer with OpenAI GPT-4o.

---

### 7.1 Demand Forecasting

- Predicts covers, revenue, and per-item sales for any future date
- Training signals: historical sales, day of week, local holidays, school calendar, local events, weather forecast API
- Output feeds directly into: ingredient purchasing recommendations, staff scheduling suggestions, and kitchen prep guides

---

### 7.2 Inventory Optimisation AI

- Recommended purchase quantities based on demand forecast and current stock
- Waste reduction alerts — flags ingredients approaching expiry not forecasted to be consumed; suggests running a special
- Anomaly detection — flags consumption significantly exceeding theoretical usage; surfaces potential theft or unlogged waste

---

### 7.3 Dynamic Pricing

- Happy hour optimisation — recommends optimal promotion windows and discount levels to maximise total revenue
- Surge pricing for local events — when a nearby event is detected, suggests modest price increases on high-demand items
- All suggestions advisory — manager approves before any price change takes effect

---

### 7.4 Customer Personalisation

- Guest segmentation — ML clustering of loyalty members by visit frequency, spend, menu preferences, and time patterns; produces named segments (e.g. "Weekend Brunch Regulars", "High-Value Business Diners")
- Personalised promotions — targeted offers per segment; content and offer level tailored to segment behaviour
- Guest preferences panel — when a loyalty member makes a reservation, staff see their most ordered items and likely preferences on the reservation detail view
- Smart loyalty rewards — AI suggests which reward is most likely to drive a repeat visit for a specific guest

---

### 7.5 Intelligent Staff Scheduling

- Auto-schedule generation — based on demand forecast; meets projected demand while minimising labour cost and respecting staff availability
- Overtime warnings — flags schedules where any staff member exceeds contracted hours or where labour cost exceeds a configurable percentage of forecasted revenue
- Performance-aware scheduling — high-performing servers weighted toward high-value shifts

---

### 7.6 Kitchen Intelligence

- Prep time prediction — predicts preparation time per dish based on current queue depth and historical KDS data; gives floor staff accurate wait time estimates
- Bottleneck detection — identifies which kitchen station most frequently causes delays; surfaces in the operational report and on the KDS supervisor view
- Recipe optimisation suggestions — flags dishes with high food cost variance and below-average feedback as candidates for recipe review or menu removal

---

### 7.7 Conversational AI Interfaces

- Reservation chatbot — embedded on the reservations page and via WhatsApp Business API; handles booking, modification, and cancellation in natural language; escalates to human when outside scope
- Customer support bot — answers common questions (hours, parking, allergens, dress code) 24/7; trained on the restaurant's content
- Manager assistant — in-app bot answering operational questions in natural language: "What was last Tuesday's food cost?", "Which server had the highest average order value this month?", "How much chicken do we need to order for the weekend?"

---

### 7.8 Automated Business Intelligence

- Natural language daily digest — "Revenue was 12% below forecast yesterday. The shortfall was concentrated in the dinner service. Likely cause: rain between 6pm and 9pm, which historically reduces walk-ins by 18%."
- Anomaly alerts — automatic notifications for significant deviations: unexpected revenue spikes, unusual staff clock-in patterns, inventory consumption anomalies
- Scenario simulation — "What if I extended happy hour by one hour?" or "What would revenue look like if I added a Sunday brunch service?" — modelled against historical data with a confidence range

---

### 7.9 Computer Vision (Advanced Phase)

- Plate recognition — camera at the pass; AI identifies the dish before it leaves the kitchen; cross-references with the order ticket; flags incorrect dishes before they are served
- Inventory tracking — camera-based stock level monitoring for high-value ingredients in cold storage; reduces manual counts
- Food quality monitoring — flags presentation deviations from the reference plate photo defined in the recipe card

---

## 8. Third-Party Integrations

| Integration | Phase | Purpose | Implementation |
|---|---|---|---|
| OpenTable / Resy | 1 | Reservation widget | Embed code in settings, rendered via Blade |
| Stripe Payments | 1 | POS card processing, online payments | Stripe Terminal SDK for POS; Stripe.js for web |
| Laravel Mail / SMTP | 1 | All transactional emails | Reservations, RSVPs, contact forms, invitations, schedules |
| Google Maps Embed | 1 | Location maps | Iframe embed per location |
| Instagram Basic Display API | 1 | Homepage feed | Scheduled job, 6-hour cache |
| Google Analytics 4 | 1 | Public site analytics | Measurement ID in settings, injected into `<head>` |
| Spatie Media Library | 1 | All image management | Menus, events, gallery, staff, settings |
| Spatie Laravel Permission | 1 | Role-based access control | Roles across all user types |
| Laravel Reverb (WebSockets) | 1 | Real-time KDS and order status | Broadcasts order events to KDS and floor staff |
| Toast / Square (embed) | 1 | Online ordering embed | Link or embed code in settings |
| Toast / Square API | 2 | Menu sync from POS | Scheduled sync job; `pos_id` on `menu_items`; POS owns names and prices, CMS owns presentation |
| Twilio | 2 | Waitlist SMS notifications | SMS sent when table is ready |
| Mailchimp / Klaviyo | 2 | Email marketing and loyalty campaigns | API key in settings; audience sync from CRM |
| Uber Eats / DoorDash / Grubhub | 2 | Delivery order routing | Via Otter or Deliverect middleware |
| QuickBooks / Xero | 2 | Accounting journal entry sync | Daily sales pushed via API |
| Gusto / ADP | 2 | Payroll integration | Hours export or API sync |
| Google / Yelp Review APIs | 2 | Review aggregation | Scheduled pull into review inbox |
| WhatsApp Business API | 3 | AI reservation and support chatbot | Via Twilio WhatsApp or Meta Cloud API |
| OpenAI GPT-4o via PrismPHP | 3 | All AI features | Forecasting, insights, chatbot, recommendations |
| OpenWeatherMap API | 3 | Demand forecast input | Scheduled daily fetch for 7-day forecast |

---

## 9. Non-Functional Requirements

### 9.1 Performance

- Google PageSpeed score above 90 (mobile and desktop) for all public pages
- All images served in WebP format via Spatie Responsive Images with multiple breakpoints
- Route and config caching enabled in production via Laravel Cloud
- Full-page caching for public routes invalidated on content publish
- KDS and order board updates via WebSocket — no polling
- Lazy loading on all below-fold images
- All long-running tasks handled via Laravel Horizon + Redis queues — never blocks a web request

### 9.2 Security

- HTTPS enforced on all routes via Laravel Cloud SSL
- PCI DSS compliance — card data handled exclusively by Stripe; only tokens stored
- Laravel Fortify with rate limiting and brute force protection
- Spatie Laravel Permission — role checks on every route, Livewire action, and API endpoint
- CSRF protection on all forms
- Input sanitisation on all rich text fields
- Audit log on all admin and operational actions
- Encrypted storage for sensitive staff documents
- GDPR / CCPA compliance — guest data deletion on request; configurable retention policy

### 9.3 Accessibility

- WCAG 2.1 AA compliance across all public pages
- All images require alt text — enforced in admin upload flow
- Keyboard navigable menus, modals, and forms
- ARIA labels on all interactive Livewire components
- Sufficient colour contrast via Tailwind CSS v4 design tokens

### 9.4 Mobile Responsiveness

- Mobile-first layout — all public pages designed for 375px viewport upward
- Touch-friendly tap targets — minimum 44×44px
- Hamburger navigation with Alpine.js transition
- Gallery lightbox with swipe gesture support
- POS interface optimised for tablet — tested at 768px and 1024px
- KDS interface optimised for mounted kitchen display — landscape 1080p

### 9.5 Scalability & Reliability

- Architecture supports 50+ locations without schema changes
- Database queries optimised — eager loading enforced; N+1 issues eliminated; indexes on all foreign keys and frequently filtered columns
- Scheduled jobs — menu PDF generation, Instagram feed refresh, POS sync, demand forecast, report email delivery

---

## 10. Database Schema

### `users`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | |
| email | string | Unique |
| password | string | Bcrypt hashed |
| invitation_token | string | Nullable — cleared on acceptance |
| invitation_accepted_at | timestamp | Nullable |
| last_login_at | timestamp | Nullable |
| active | boolean | Soft deactivation |
| created_at / updated_at | timestamp | |

> Roles assigned via Spatie Laravel Permission `model_has_roles` pivot.

---

### `locations`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | e.g. "Arlington", "Courthouse" |
| address | string | |
| city | string | |
| state | string | |
| zip | string | |
| phone | string | |
| email | string | |
| maps_embed_url | string | Google Maps iframe src |
| is_primary | boolean | |
| active | boolean | |
| created_at / updated_at | timestamp | |

### `location_hours`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| day_of_week | tinyint | 0=Sunday through 6=Saturday |
| opens_at | time | Nullable |
| closes_at | time | Nullable |
| closed | boolean | |

---

### `menu_categories`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | |
| slug | string | |
| sort_order | integer | |
| active | boolean | |
| created_at / updated_at | timestamp | |

### `menu_items`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| menu_category_id | bigint | Foreign key |
| name | string | |
| slug | string | |
| description | text | |
| price | decimal(8,2) | |
| dietary_tags | json | Array of tag slugs |
| featured | boolean | |
| available_always | boolean | |
| available_from | date | Nullable |
| available_until | date | Nullable |
| available_days | json | Array of day integers |
| sort_order | integer | |
| active | boolean | |
| pos_id | string | Nullable — POS item ID for Phase 2 sync |
| created_at / updated_at | timestamp | |

### `modifier_groups`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | e.g. "Cooking preference", "Add-ons" |
| required | boolean | Must the guest select an option? |
| min_selections | tinyint | |
| max_selections | tinyint | |

### `modifier_options`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| modifier_group_id | bigint | Foreign key |
| name | string | e.g. "Rare", "Extra cheese" |
| price_adjustment | decimal(8,2) | 0.00 if no charge |
| sort_order | integer | |

### `menu_item_modifier_groups`

| Column | Type | Notes |
|---|---|---|
| menu_item_id | bigint | Foreign key |
| modifier_group_id | bigint | Foreign key |

---

### `tables`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| name | string | e.g. "Table 4", "Bar Seat 2" |
| capacity | tinyint | |
| section | string | e.g. "Main Dining", "Patio", "Bar" |
| pos_x | float | Floor plan X position |
| pos_y | float | Floor plan Y position |
| shape | enum | round, square, rectangle |
| status | enum | available, reserved, occupied, needs_cleaning |
| active | boolean | |

---

### `orders`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| table_id | bigint | Foreign key — nullable for takeout/delivery |
| user_id | bigint | Foreign key — server who took the order |
| guest_id | bigint | Foreign key — nullable; linked loyalty member |
| order_type | enum | dine_in, takeout, delivery |
| source | enum | pos, online, uber_eats, doordash, grubhub |
| status | enum | pending, in_preparation, ready, served, completed, voided |
| subtotal | decimal(10,2) | |
| discount_amount | decimal(10,2) | |
| tax_amount | decimal(10,2) | |
| total | decimal(10,2) | |
| notes | text | Nullable |
| created_at / updated_at | timestamp | |

### `order_items`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| order_id | bigint | Foreign key |
| menu_item_id | bigint | Foreign key |
| quantity | integer | |
| unit_price | decimal(8,2) | Price at time of order — snapshot |
| modifiers | json | Selected modifier options snapshot |
| special_instructions | text | Nullable |
| course | tinyint | 1=Starter, 2=Main, 3=Dessert |
| status | enum | pending, fired, in_preparation, ready, served |
| created_at / updated_at | timestamp | |

### `payments`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| order_id | bigint | Foreign key |
| method | enum | card, cash, mobile, loyalty_redemption |
| amount | decimal(10,2) | |
| stripe_payment_intent_id | string | Nullable |
| status | enum | pending, completed, refunded, failed |
| processed_at | timestamp | Nullable |
| created_at / updated_at | timestamp | |

### `shifts`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| user_id | bigint | Foreign key |
| opened_at | timestamp | |
| closed_at | timestamp | Nullable |
| opening_float | decimal(10,2) | |
| closing_cash | decimal(10,2) | Nullable |
| total_sales | decimal(10,2) | Calculated on close |
| notes | text | Nullable |

---

### `reservations`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| table_id | bigint | Foreign key — nullable until assigned |
| guest_id | bigint | Foreign key — nullable; linked loyalty member |
| name | string | |
| email | string | |
| phone | string | |
| date | date | |
| time | time | |
| party_size | integer | |
| special_requests | text | Nullable |
| status | enum | pending, confirmed, seated, completed, no_show, cancelled |
| source | enum | online_form, phone, opentable, resy, walk_in |
| notes | text | Internal staff notes |
| reminder_sent_at | timestamp | Nullable |
| created_at / updated_at | timestamp | |

---

### `events`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key — nullable means all locations |
| title | string | |
| slug | string | |
| description | longtext | Rich text |
| starts_at | datetime | |
| ends_at | datetime | Nullable |
| cta_type | enum | rsvp, external_link, none |
| cta_label | string | Nullable |
| cta_url | string | Nullable |
| rsvp_enabled | boolean | |
| published | boolean | |
| created_at / updated_at | timestamp | |

### `event_rsvps`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| event_id | bigint | Foreign key |
| name | string | |
| email | string | |
| party_size | integer | |
| created_at / updated_at | timestamp | |

### `promotions`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key — nullable means all locations |
| title | string | |
| description | text | |
| type | enum | percentage, fixed_amount, free_item, bogo |
| discount_value | decimal(8,2) | Nullable |
| free_item_id | bigint | FK to menu_items — nullable |
| applicable_to | enum | all, loyalty_members, targeted_segment |
| starts_at | date | |
| ends_at | date | |
| show_on_homepage | boolean | |
| active | boolean | |
| created_at / updated_at | timestamp | |

### `happy_hours`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| day_of_week | tinyint | 0=Sunday through 6=Saturday |
| starts_at | time | |
| ends_at | time | |
| label | string | e.g. "Happy Hour" |
| active | boolean | |

---

### `guests`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | |
| email | string | Unique |
| phone | string | Nullable |
| dietary_preferences | json | Nullable |
| notes | text | Internal staff notes |
| loyalty_points | integer | Running balance |
| created_at / updated_at | timestamp | |

### `loyalty_transactions`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| guest_id | bigint | Foreign key |
| order_id | bigint | Foreign key — nullable for manual adjustments |
| type | enum | earned, redeemed, adjusted, expired |
| points | integer | Positive for earned, negative for redeemed |
| description | string | |
| created_at | timestamp | |

---

### `ingredients`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| supplier_id | bigint | Foreign key — nullable |
| name | string | |
| unit | string | e.g. kg, litre, each |
| stock_level | decimal(10,3) | Current stock |
| minimum_threshold | decimal(10,3) | Alert trigger level |
| cost_per_unit | decimal(8,4) | |
| active | boolean | |
| created_at / updated_at | timestamp | |

### `recipes`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| menu_item_id | bigint | Foreign key |
| yield_percentage | decimal(5,2) | Accounts for prep waste |
| notes | text | Method, plating notes |
| created_at / updated_at | timestamp | |

### `recipe_ingredients`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| recipe_id | bigint | Foreign key |
| ingredient_id | bigint | Foreign key |
| quantity | decimal(10,3) | Per serving |

### `wastage_logs`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| ingredient_id | bigint | Foreign key |
| user_id | bigint | Foreign key |
| quantity | decimal(10,3) | |
| reason | enum | spoilage, prep_waste, spillage, other |
| notes | text | Nullable |
| logged_at | timestamp | |

### `suppliers`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | |
| contact_name | string | Nullable |
| email | string | Nullable |
| phone | string | Nullable |
| lead_time_days | tinyint | |
| payment_terms | string | Nullable |
| active | boolean | |
| created_at / updated_at | timestamp | |

### `purchase_orders`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| supplier_id | bigint | Foreign key |
| user_id | bigint | Foreign key — created by |
| status | enum | draft, sent, received, partial, cancelled |
| expected_delivery_date | date | Nullable |
| notes | text | Nullable |
| created_at / updated_at | timestamp | |

### `purchase_order_items`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| purchase_order_id | bigint | Foreign key |
| ingredient_id | bigint | Foreign key |
| quantity_ordered | decimal(10,3) | |
| quantity_received | decimal(10,3) | Nullable |
| unit_cost | decimal(8,4) | |
| received_at | timestamp | Nullable |

---

### `staff_schedules`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| user_id | bigint | Foreign key |
| shift_date | date | |
| starts_at | time | |
| ends_at | time | |
| role | string | Role for this shift |
| published | boolean | Visible to staff when true |
| created_at / updated_at | timestamp | |

### `attendance_records`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key |
| user_id | bigint | Foreign key |
| staff_schedule_id | bigint | Foreign key — nullable for unscheduled clock-ins |
| clocked_in_at | timestamp | |
| clocked_out_at | timestamp | Nullable |
| late_flag | boolean | |
| early_departure_flag | boolean | |

---

### `contact_submissions`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| location_id | bigint | Foreign key — nullable |
| name | string | |
| email | string | |
| subject | string | |
| message | text | |
| read_at | timestamp | Nullable |
| archived_at | timestamp | Nullable |
| created_at / updated_at | timestamp | |

### `seo_settings`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| page | string | home, menu, events, gallery, contact, about, event:{slug} |
| meta_title | string | Nullable |
| meta_description | string | Nullable |
| og_title | string | Nullable |
| og_description | string | Nullable |
| og_image | string | Nullable |
| created_at / updated_at | timestamp | |

### `settings`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| key | string | Unique |
| value | longtext | JSON-encoded for complex values |
| updated_at | timestamp | |

### `audit_logs`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| user_id | bigint | Foreign key — nullable for system actions |
| action | string | e.g. menu_item.created, order.voided |
| model_type | string | Nullable |
| model_id | bigint | Nullable |
| old_values | json | Nullable |
| new_values | json | Nullable |
| ip_address | string | |
| created_at | timestamp | |

---

## 11. Routes Reference

### Public Routes (Guest)

| Method | URI | Description |
|---|---|---|
| GET | `/` | Homepage |
| GET | `/menu` | Full menu |
| GET | `/events` | Events & promotions |
| GET | `/events/{slug}` | Event detail |
| GET | `/gallery` | Photo gallery |
| GET | `/reservations` | Reservations page |
| GET | `/contact` | Contact & location |
| GET | `/about` | About page |
| GET | `/order` | Online ordering |
| GET | `/menu.pdf` | Auto-generated PDF menu |
| GET | `/sitemap.xml` | Auto-generated sitemap |

### Guest Portal (Loyalty Member)

| Method | URI | Description |
|---|---|---|
| GET | `/my/dashboard` | Loyalty dashboard |
| GET | `/my/points` | Points balance and history |
| GET | `/my/rewards` | Available rewards |
| GET | `/my/orders` | Order history |

### POS Routes (Staff — Authenticated)

| Method | URI | Description |
|---|---|---|
| GET | `/pos` | POS terminal |
| GET | `/pos/floor` | Floor plan view |
| GET | `/pos/orders` | Active order board |
| GET | `/kds` | Kitchen Display System |

### Admin Routes (Authenticated)

| Method | URI | Description |
|---|---|---|
| GET | `/admin` | Dashboard |
| GET/POST | `/admin/menu/categories` | Category management |
| GET/POST | `/admin/menu/items` | Menu item management |
| GET/POST | `/admin/menu/modifiers` | Modifier group management |
| GET/POST | `/admin/events` | Events management |
| GET/POST | `/admin/promotions` | Promotions management |
| GET/POST | `/admin/happy-hours` | Happy hour schedule |
| GET | `/admin/rsvps/{event}` | RSVP responses |
| GET/POST | `/admin/gallery` | Gallery management |
| GET/POST | `/admin/reservations` | Reservations management |
| GET/POST | `/admin/tables` | Table & floor plan management |
| GET | `/admin/orders` | Order history |
| GET | `/admin/pos/shifts` | Shift history and reconciliation |
| GET/POST | `/admin/inventory` | Ingredient stock management |
| GET/POST | `/admin/inventory/recipes` | Recipe management |
| GET/POST | `/admin/inventory/purchase-orders` | Purchase orders |
| GET/POST | `/admin/inventory/suppliers` | Supplier management |
| GET | `/admin/inventory/wastage` | Wastage log |
| GET/POST | `/admin/staff` | Staff profiles |
| GET/POST | `/admin/staff/schedules` | Shift scheduling |
| GET | `/admin/staff/attendance` | Attendance records |
| GET | `/admin/staff/payroll` | Payroll hours export |
| GET/POST | `/admin/crm/guests` | Guest profiles |
| GET/POST | `/admin/crm/loyalty` | Loyalty programme settings |
| GET/POST | `/admin/crm/promotions-targeted` | Targeted promotion builder |
| GET | `/admin/reports/sales` | Sales reports |
| GET | `/admin/reports/food-cost` | Food cost & margins |
| GET | `/admin/reports/staff` | Staff performance |
| GET | `/admin/reports/operations` | Operational reports |
| GET | `/admin/contact-submissions` | Contact submissions |
| GET/POST | `/admin/locations` | Location management |
| GET/POST | `/admin/seo` | SEO settings |
| GET/POST | `/admin/settings` | Global settings |
| GET/POST | `/admin/users` | User management |
| GET | `/admin/audit-log` | Audit log |

### Auth Routes (Fortify)

| Method | URI | Description |
|---|---|---|
| GET | `/login` | Login page |
| POST | `/login` | Authenticate |
| POST | `/logout` | Logout |
| GET | `/forgot-password` | Password reset request |
| POST | `/reset-password` | Password reset |
| GET | `/invitation/{token}` | Accept staff invitation |

---

## 12. Implementation Status

### Tier 1 — Critical

| Feature | Priority | Status |
|---|---|---|
| Homepage | High | ✅ Built |
| Menu display (public) | High | ✅ Built |
| Reservations page (public) | High | ✅ Built |
| Contact / location page | High | ❌ Next |
| Events & promotions (public) | High | ❌ Next |
| Gallery (public) | High | ❌ Next |
| Mobile responsiveness polish | High | ❌ Next |
| Menu management (admin CMS) | High | ❌ Planned |
| About page | Medium | ❌ Planned |
| POS system | High | ❌ Planned |
| Kitchen Display System | High | ❌ Planned |
| Order management | High | ❌ Planned |
| Table & floor management | High | ❌ Planned |
| Reservations management (admin) | High | ❌ Planned |
| Inventory — ingredients & stock | High | ❌ Planned |
| Inventory — recipe linking | High | ❌ Planned |
| Inventory — purchase orders | Medium | ❌ Planned |
| Supplier management | Medium | ❌ Planned |
| Staff profiles | Medium | ❌ Planned |
| Shift scheduling | Medium | ❌ Planned |
| Attendance tracking | Medium | ❌ Planned |
| CRM — guest profiles | Medium | ❌ Planned |
| Loyalty programme | Medium | ❌ Planned |
| Sales reporting | High | ❌ Planned |
| Food cost reporting | High | ❌ Planned |
| Staff performance reporting | Medium | ❌ Planned |
| Operational reporting | Medium | ❌ Planned |
| SEO management (admin) | Medium | ❌ Planned |
| Settings panel | Medium | ❌ Planned |
| User & role management | Medium | ❌ Planned |
| Audit log | Medium | ❌ Planned |
| Security & PCI compliance | High | ❌ Planned |

### Tier 2 — Nice-to-Have

| Feature | Priority | Status |
|---|---|---|
| QR code self-ordering | Medium | ❌ Planned |
| Third-party delivery integration | Medium | ❌ Planned |
| Multi-branch consolidated reporting | Medium | ❌ Planned |
| QuickBooks / Xero accounting sync | Low | ❌ Planned |
| Recipe card builder (full) | Low | ❌ Planned |
| Menu engineering matrix | Low | ❌ Planned |
| Guest feedback & review management | Medium | ❌ Planned |
| Payroll integration (Gusto / ADP) | Low | ❌ Planned |
| IoT device integration | Low | ❌ Planned |

### Tier 3 — AI-Powered

| Feature | Priority | Status |
|---|---|---|
| Demand forecasting | High | ❌ Planned |
| Inventory optimisation AI | Medium | ❌ Planned |
| Dynamic pricing suggestions | Medium | ❌ Planned |
| Customer personalisation & segmentation | Medium | ❌ Planned |
| Intelligent staff scheduling | Medium | ❌ Planned |
| Kitchen intelligence | Medium | ❌ Planned |
| Reservation / support chatbot | Medium | ❌ Planned |
| Manager assistant (natural language) | Medium | ❌ Planned |
| Automated BI insights & anomaly detection | Low | ❌ Planned |
| Scenario simulation | Low | ❌ Planned |
| Computer vision | Low | ❌ Planned |

---

*This is a living specification. Update the Implementation Status table as features are completed. Version the document on significant scope changes.*
