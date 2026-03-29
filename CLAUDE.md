# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Memoora** — a full-stack note-taking web app built with Laravel 12 + Breeze, Blade, TailwindCSS 3 (class-based dark mode), and Alpine.js. Features: auth (register/login/password reset), notes CRUD, search, pin, archive, dark mode, responsive sidebar UI.

## Commands

### Development
```bash
composer install && npm install
cp .env.example .env && php artisan key:generate && php artisan migrate
composer run dev          # PHP + Vite + queue + log tailing concurrently
npm run build             # Production asset build
```

### Testing
```bash
php artisan test                                      # All tests (24 tests)
./vendor/bin/phpunit --testsuite Feature              # Feature tests only
./vendor/bin/phpunit --filter test_method_name        # Single test
```

### Code Quality
```bash
php artisan pint          # Fix code style (Laravel Pint)
php artisan pint --test   # Check without fixing
php artisan pail          # Tail logs in real-time
```

## Architecture

### Tech Stack
- **Backend**: Laravel 12, PHP 8.2+, Laravel Breeze auth (blade stack)
- **Frontend**: Blade components, TailwindCSS 3 (PostCSS), Alpine.js, `darkMode: 'class'` strategy
- **Database**: MySQL (dev default, see `.env.example`). Notes use soft deletes.
- **Auth**: Breeze-generated controllers in `app/Http/Controllers/Auth/`

### Data Model
- `users` — standard Laravel, has many notes
- `notes` — `user_id` FK, `title`, `content` (nullable), `drawing` (nullable, canvas data), `is_pinned` (bool), `is_archived` (bool), `deleted_at` (soft delete)

### Controllers
- `DashboardController` — stats + recent notes for authenticated home
- `NoteController` — CRUD + `pin()`, `archive()`, `pinned()`, `archived()` extra actions; uses `AuthorizesRequests` + `NotePolicy`
- `SettingsController` — wraps profile update, password update, account delete (previously `/profile`, now `/settings`)

### Authorization
`NotePolicy` (`app/Policies/NotePolicy.php`) — auto-discovered; enforces `user_id` ownership on view/update/delete. Always `$this->authorize(...)` in controller before mutating a note.

### Route Structure (all under `auth` middleware)
```
GET  /               → redirect to /dashboard or /login
GET  /dashboard      → dashboard
GET  /notes          → index (with ?search=)
GET  /notes/create   → create form
POST /notes          → store
GET  /notes/{note}/edit  → edit form
PATCH /notes/{note}      → update
DELETE /notes/{note}     → soft delete
PATCH /notes/{note}/pin      → toggle pin
PATCH /notes/{note}/archive  → toggle archive
GET  /pinned         → pinned list
GET  /archive        → archived list
GET  /settings       → settings page
PATCH /settings/profile   → update name/email
PATCH /settings/password  → update password
DELETE /settings          → delete account
```

### Dark Mode
- `tailwind.config.js` has `darkMode: 'class'`
- Inline `<script>` in `<head>` of both layouts applies `.dark` from `localStorage` before paint (prevents FOUC)
- Toggle button (`#darkModeToggle`) wired in `resources/js/app.js`
- Preference stored in `localStorage` under key `theme` (`'dark'` | `'light'`)

### View Structure
- `layouts/app.blade.php` — authenticated layout: fixed sidebar, top bar, flash messages, Alpine.js sidebar toggle for mobile
- `layouts/guest.blade.php` — centered card layout for auth pages
- `notes/_card.blade.php` — reusable note card partial (Alpine.js dropdown for actions)
- `notes/_canvas.blade.php` — drawing canvas partial included in note create/edit forms
- CSS utility classes defined in `resources/css/app.css` `@layer components`: `.nav-link`, `.nav-link-active`, `.card`, `.form-input`, `.btn-primary`, `.btn-secondary`
- Global Alpine.js confirm modal registered as `Alpine.store('confirm')` in `resources/js/app.js` — use this instead of `window.confirm()` throughout the app

## Coding Conventions
- Form Request classes for all validation (`StoreNoteRequest`, `UpdateNoteRequest`, `ProfileUpdateRequest`)
- Soft deletes on `notes` table — never hard-delete from UI
- Paginate with `->paginate(12)->withQueryString()` to preserve search params
- Always use `$this->authorize(...)` before mutating user-owned resources
