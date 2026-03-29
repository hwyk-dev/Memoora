# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Memoora** — a full-stack note-taking web app built with Laravel 12 + Breeze, Blade, TailwindCSS 3 (class-based dark mode), and Alpine.js. Features: auth (register/login/Google OAuth via Firebase), notes CRUD with search/pin/archive, admin panel, ban/suspend system, multi-language (en/ja/my), dark mode, responsive sidebar UI.

## Commands

### Development
```bash
composer install && npm install
cp .env.example .env && php artisan key:generate && php artisan migrate
composer run dev          # PHP + Vite + queue + log tailing concurrently
npm run build             # Production asset build
php artisan db:seed --class=AdminUserSeeder   # Create default admin@example.com / password
```

### Testing
```bash
php artisan test                                      # All tests (25 tests)
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
- **Auth**: Breeze controllers in `app/Http/Controllers/Auth/` + `GoogleController` for Firebase JWT verification

### Data Model
- `users` — `name`, `email`, `password` (nullable for Google-only), `firebase_uid`, `is_admin` (bool), `last_login_at`, `banned_at`, `suspended_until`, `ban_reason`, `locale` (default `'en'`)
- `notes` — `user_id` FK, `title`, `content` (nullable), `drawing` (nullable, canvas data), `is_pinned`, `is_archived`, `deleted_at` (soft delete)

### Controllers
- `DashboardController` — stats (total/pinned/archived) + recent notes for home
- `NoteController` — CRUD + `pin()`, `archive()`, `pinned()`, `archived()` actions; uses `AuthorizesRequests` + `NotePolicy`
- `SettingsController` — profile update, password update, locale update, account delete
- `Admin\DashboardController` — platform-wide stats + recent users/notes
- `Admin\UserController` — paginated user list with search/filter, user detail, ban/unban/suspend/unsuspend/delete
- `Auth\GoogleController` — verifies Firebase ID token (manual JWT via `firebase/php-jwt`), `firstOrCreate` user by email

### Middleware
- `EnsureUserIsActive` (alias: `active`) — logs out banned/suspended users, redirects to login with error. Applied to all auth routes.
- `EnsureUserIsAdmin` (alias: `admin`) — 403 if `!is_admin`. Applied to all `/admin/*` routes.
- `SetLocale` — web middleware (runs on every request); reads locale from `$user->locale` → `session('locale')` → app default.

### Route Structure
```
# Auth (guest)
POST /auth/google/callback         → auth.google.callback

# Authenticated + active
GET  /dashboard
GET/POST /notes, /notes/create, /notes/{note}/edit
PATCH /notes/{note}/pin|archive
DELETE /notes/{note}               → soft delete
GET  /pinned, /archive
GET  /settings
PATCH /settings/profile|password|locale
DELETE /settings                   → account delete

# Admin only
GET    /admin                      → admin.dashboard
GET    /admin/users                → admin.users.index  (search + status filter)
GET    /admin/users/{user}         → admin.users.show
PATCH  /admin/users/{user}/ban|unban|suspend|unsuspend
DELETE /admin/users/{user}         → hard delete
```

### Google / Firebase Auth
- Frontend: `resources/js/firebase-auth.js` — calls `signInWithPopup`, posts ID token to `/auth/google/callback`
- Backend: `GoogleController::verifyFirebaseToken()` — fetches Google's X.509 public keys (cached 1 hour), verifies JWT manually; no Firebase Admin SDK needed
- Config: `config/firebase.php` reads `FIREBASE_PROJECT_ID` from `.env`; frontend uses `VITE_FIREBASE_*` env vars

### Localization
All UI strings are in `lang/{en,ja,my}/messages.php` organized by section (`nav`, `auth`, `dashboard`, `notes`, `settings`, `admin`, `languages`). Use `__('messages.section.key')` in views. Locale is persisted to `users.locale` via `PATCH /settings/locale`.

### Dark Mode
- `tailwind.config.js` has `darkMode: 'class'`
- Inline `<script>` in `<head>` of both layouts applies `.dark` from `localStorage` before paint (prevents FOUC)
- Toggle button (`#darkModeToggle`) wired in `resources/js/app.js`
- Preference stored in `localStorage` under key `theme`

### View Structure
- `layouts/app.blade.php` — authenticated layout: fixed sidebar (with Admin section for admins), top bar, flash messages
- `layouts/guest.blade.php` — centered card for auth pages
- `notes/_card.blade.php` — reusable note card (Alpine.js dropdown for pin/archive/delete)
- `notes/_canvas.blade.php` — drawing canvas partial in create/edit forms
- `admin/users/index.blade.php` — teleported dropdowns (`x-teleport="body"` + `position:fixed` from `getBoundingClientRect()`) to escape `overflow-hidden` table container
- CSS utility classes in `resources/css/app.css` `@layer components`: `.nav-link`, `.nav-link-active`, `.card`, `.form-input`, `.btn-primary`, `.btn-secondary`
- Global Alpine.js confirm modal: `Alpine.store('confirm')` in `resources/js/app.js` — use instead of `window.confirm()`

## Coding Conventions
- Form Request classes for validation (`StoreNoteRequest`, `UpdateNoteRequest`, `ProfileUpdateRequest`)
- Soft deletes on `notes` — never hard-delete from UI; admin hard-deletes users via `$user->forceDelete()` or `$user->delete()` (no soft delete on users)
- Paginate with `->paginate(12)->withQueryString()` for notes; `->paginate(15)->withQueryString()` for admin user list
- Always `$this->authorize(...)` before mutating user-owned notes
- Admin actions protect against self-targeting: `$user->is($request->user())` check before ban/suspend/delete
- `User::isBanned()` checks `banned_at !== null`; `isSuspended()` checks `suspended_until->isFuture()` — expired suspensions auto-lift
- Registration sets `email_verified_at = now()` immediately; Google sign-in also sets it
- `last_login_at` updated on every successful login (both password and Google)
