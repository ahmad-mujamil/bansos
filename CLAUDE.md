# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Bansos** (Bantuan Sosial) is a Laravel 12 social assistance management system for Indonesian local government. It handles the full lifecycle of aid applications (pengajuan) from submission through multi-level verification.

## Development Commands

```bash
# Start all development services concurrently (server, queue, logs, vite)
composer dev

# Run tests
composer test  # clears config then runs php artisan test

# Run a single test file
php artisan test tests/Feature/SomeTest.php

# Run a single test by name
php artisan test --filter=test_name

# Build frontend assets
npm run build
npm run dev   # watch mode

# Database
php artisan migrate
php artisan db:seed

# Full fresh setup
composer setup  # install deps, copy .env, generate key, migrate, npm install & build
```

## Architecture

### Domain Model

The core entity is **Pengajuan** (application/proposal). Workflow: `DRAFT → DIAJUKAN → DIVERIFIKASI/DITOLAK → DIADOPSI`.

Three application types (`JenisPengajuan`):
- `BANSOS` — individual aid, requires `penduduk_id`
- `BANTUAN_KELOMPOK` — group aid, requires `jenis_bantuan_id`
- `HIBAH` — grant, requires `kelompok_id`

Three beneficiary types (`JenisUser`): `IND` (individual), `KLP` (kelompok/group), `ORG` (organization).

### Role Hierarchy (`RoleUser`)

`super` → `admin` → `opd` →`user`

Route groups use `middleware(['role:super'])` or `middleware(['role:super,opd'])` etc. via the `CheckRole` middleware.

### Key Patterns

**Transactions on state changes** — all `store`/`update`/`submit` in `PengajuanController` use `DB::beginTransaction()`/`commit()`/`rollBack()`.

**Authorization guards** — call `authorizeUser($pengajuan)` before `show/edit/update/submit`. Use model methods `canEdit()` and `canSubmit()` to gate UI buttons and routes.

**Flash notifications** — use `toast()->success(...)` / `toast()->error(...)` / `toast()->warning(...)`.

### Enum Consistency Rule

When adding/removing cases in `PengajuanStatus`:
1. Update badge color mapping in `resources/views/pages/pengajuan/index.blade.php` and `show.blade.php`
2. Update transition guards in `PengajuanController`
3. Blade referencing a non-existent enum case causes a runtime error — verify cases exist before referencing


### Livewire Components

- `WilayahSelect` — dynamic region/district cascading select

### Frontend

Vite + Tailwind CSS 4 + Bootstrap 5 + Blade templates. Interactive dropdowns use `select2-ajax` Blade component. Confirmation dialogs use SweetAlert (`Swal.fire(...)`).

### Media Uploads

Spatie MediaLibrary handles file attachments. Collections are named per model (e.g., `pengajuan`, `organisasi_dokumen`).

### Custom Config Files

- `config/sidebar.php` — sidebar navigation menu structure
- `config/topbar.php` — top nav structure

### Testing

Pest PHP 3 with `Unit` and `Feature` suites. Tests use SQLite in-memory DB (configured in `phpunit.xml`).

### AppServiceProvider

Only boots `Model::unguard()` — mass assignment protection is disabled globally. Rely on `$fillable` or explicit assignment rather than trusting this as a security layer.
