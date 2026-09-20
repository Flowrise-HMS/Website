# Website module

Public hospital website CMS with theme packs for FlowRise HMS.

## Current status

**Complete** for Phases 1–2 (CMS + hybrid public booking). See [Module Status](../../docs/shared/module-status.md) for the canonical matrix. Verified against code on 2026-09-20.

## Admin panel surface

Cluster **Website** in the **Administration** sidebar group (`/website`): **Site Settings** (`/website/manage-website-settings`), **Pages**, **Menus**, **News** (page heading **Posts**, button **New post**), **Team** (heading **Team Members**), **Partners**, **Gallery** (heading **Gallery Albums**), **Booking Requests** (list/edit only; row action **Review**), **Contact Inbox** (heading **Contact Submissions**; list/edit only; row action **View / mark read**). Permissions: Shield abilities per model (Page, Post, Menu, GalleryAlbum, TeamMember, Partner, BookingRequest, ContactSubmission), `View ManageWebsiteSettings`, and the custom `manage_website` / `manage_website_settings`.

Site Settings sections: **Public site** (Enable public website, Admin panel path (only shown while the site is enabled), Active theme, Enable animations), **Brand & appearance** (logos, favicon, colours, footer about text), **Default SEO**, **Contact & booking CTA**, **Online booking** (Bookable services, Default booking branch, Open weekdays, Opens at, Closes at, Slot length (minutes), Bookable days ahead). **Bookable services** lists active non-medication Core services (medication catalog entries are excluded from the select and from the public booking wizard). Saving clears the route/config caches and shows the new login URL.

## Public routes (only while "Enable public website" is on)

`/` (home), `/news`, `/news/{slug}`, `/gallery`, `/team`, `/partners`, `/contact` (Livewire `ContactForm`), `/book-appointment` (Livewire `BookingWizard`), `/sitemap.xml`, `/robots.txt`, `/{slug}` (CMS pages). When the public site is on, the Filament panel moves from `/` to `/{panel_path_slug}` (default `admin`).

## Phase 1 — CMS

- Filament cluster for pages (section blocks), news, gallery, team, partners, menus, contact inbox, site settings
- Theme packs under `resources/themes/{name}` (+ Blade views in `resources/views/themes/{name}`)
- When `website_enabled` is true, public site owns `/` and Filament uses `panel_path_slug`
- Book Appointment page uses `BookingCtaResolver` (settings / WhatsApp / phone placeholder)

## Phase 2 — Public booking

- Hybrid booking: available slots → confirmed Appointment; otherwise waitlist or preferred-time request
- Patient search (MRN / phone / email / national ID) with masked name + OTP (mail + Core `SMSChannel`)
- Guests create a minimal Patient, then book
- Website settings: curated Core services, open hours, slot length, default branch
- Filament **Booking Requests** inbox for preferred-time / waitlist-linked requests

## Phase 3 adapters

- `Modules\Website\Contracts\PharmacyCatalogContract` — Products / cart catalog

## Setup

```bash
php artisan module:migrate Website --force
php artisan migrate --path=Modules/Website/database/settings --force
# or run full app migrate for Spatie settings
```

### Seed a specific theme (recommended at install)

```bash
# Interactive picker
php artisan website:seed-theme

# Explicit theme
php artisan website:seed-theme default
php artisan website:seed-theme mediox --publish-assets --enable
php artisan website:seed-theme clinicalmaster --fresh --publish-assets --enable
```

Or via class / env:

```bash
php artisan db:seed --class="Modules\\Website\\Database\\Seeders\\Themes\\ClinicalMasterThemeSeeder"
WEBSITE_SEED_THEME=mediox php artisan db:seed --class="Modules\\Website\\Database\\Seeders\\WebsiteDatabaseSeeder"
```

Available theme seeders: `default`, `mediox`, `clinicalmaster`, `clinical-blue`. The **Active theme** dropdown shows the `theme.json` labels: **Default Hospital**, **Mediox (Home One)**, **ClinicalMaster (Physiotherapy)** and **Clinical Blue** (`clinical-blue` ships its own hero section and falls back to the default theme's other views). `php artisan website:publish-theme-assets {theme?}` republishes a theme's public assets.

Tests: `php artisan test --compact Modules/Website/tests` (10 files). 10 migrations, 11 models.

Enable the public site from **Administration → Website → Site Settings** (or pass `--enable`), then configure **Online booking** (branch + bookable services). Booking requests and waitlist-linked requests arrive in **Booking Requests**; contact form messages in **Contact Inbox**.
