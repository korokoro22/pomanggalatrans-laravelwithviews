# AGENTS.md

## Project Overview

PO Manggala Trans — a Laravel 13 inventory and logistics management system for a transportation company. Manages items (barang), incoming/outgoing stock, buses, service packages, and delivery notes (nota jalan) with PDF reporting and QR code tracking.

## Tech Stack

- **PHP 8.3** / **Laravel 13.8**
- **SQLite** (default database, including tests)
- **AdminLTE 3** (jeroennoten/laravel-adminlte) — admin panel UI
- **Tailwind CSS 4** + **Vite 8** — frontend build
- **dompdf** — PDF export (landscape A4 for lists, portrait for single-item)
- **intervention/image** — image handling
- **simple-qrcode** — QR code generation (SVG format)
- **Laravel Pint** — code formatting
- **PHPUnit 12** — testing

## Commands

```bash
# Full setup (composer install, key generate, migrate, npm build)
composer setup

# Development (runs server + queue + pail + vite concurrently)
composer dev

# Run tests (clears config first, then runs PHPUnit)
composer test

# Run a single test file
php artisan test tests/Feature/YourTest.php

# Run a single test method
php artisan test --filter=test_method_name

# Build frontend assets
npm run build

# Vite dev server (hot reload)
npm run dev

# Regenerate all QR codes for items
php artisan qrcode:regenerate

# Create storage symlink (also available via GET /generate)
php artisan storage:link

# Format code
./vendor/bin/pint
```

## Architecture

### Authentication
- Login uses **`name`** (username), not `email` — see `AuthController`
- Routes protected by `auth` middleware; guest routes for login/logout

### Models & Tables
- Table names are **snake_case** and differ from model names (e.g., `Barang_masuk` → `barang_masuk`, `Transaksi_keluar` → `transaksi_keluar`)
- Key models: `Barang`, `Barang_masuk`, `Barang_masuk_detail`, `Transaksi_keluar`, `Transaksi_keluar_detail`, `Bus`, `Paket_service`, `Paket_service_item`, `NotaJalan`, `NotaJalanDetail`, `LaporanBus`
- `Barang` has `foto_url` and `tanggal_masuk_formatted` appended attributes

### QR Codes
- Stored in `storage/app/public/qrcode/` as SVG files
- Filename pattern: `barang-{slug}-{id}.svg`
- Regenerate all via `php artisan qrcode:regenerate`

### PDF Exports
- Every major resource has `exportPdf` (list) and `exportPdfShow` (single) routes
- List PDFs use **landscape A4**; single-item PDFs use **portrait A4**
- Views receive `$storagePath` for resolving image paths in PDFs

### URL Encoding
- `nama_barang` is **base64-encoded** when passed in URLs (see `MasterBarangController`)
- Routes like `master-barang/nama/{nama_barang}` expect the encoded value

### Frontend
- Blade views in `resources/views/` organized by resource
- AdminLTE layout wraps all authenticated pages
- Vite entry points: `resources/css/app.css`, `resources/js/app.js`
- Bunny.net font (Instrument Sans) loaded via `laravel-vite-plugin`

## Testing

- Tests use **in-memory SQLite** (`DB_DATABASE=:memory:`)
- `BCRYPT_ROUNDS=4` for speed
- `phpunit.xml` sets `APP_ENV=testing` and uses `array` cache/session
- Only example tests exist — no real test coverage yet

## Conventions

- **Indonesian language** used for UI text, validation messages, and code comments
- Carbon locale set to `id` (Indonesian) in `AppServiceProvider`
- Bootstrap pagination (not Tailwind) via `Paginator::useBootstrap()`
- Controllers use `compact()` for passing data to views
- Resource controllers follow Laravel conventions with additional export routes defined separately in `routes/web.php`

## Gotchas

- `.npmrc` has `ignore-scripts=true` — run `npm install` with `--ignore-scripts` flag if needed
- The `/generate` route calls `storage:link` via Artisan — useful if symlink is lost
- `config/adminlte.php` controls the AdminLTE panel title and menu
- No `lang/` directory for app translations — all strings are inline in views
