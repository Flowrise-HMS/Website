# Website module

Public hospital website CMS with theme packs for FlowRise HMS.

## Current status

**Complete** for Phases 1–2 (CMS + hybrid public booking). See [Module Status](../../docs/shared/module-status.md) for the canonical matrix.

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

Available theme seeders: `default`, `mediox`, `clinicalmaster`.

Enable the public site from **Website → Site Settings** (or pass `--enable`), then configure **Online booking** (branch + bookable services).
