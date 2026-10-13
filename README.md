# Estatein — Full-Stack Real Estate Platform

Bilingual (English / Arabic) real-estate website and admin API, based on the **Produce UI — Estatein** Figma template (dark theme).

| Layer | Stack |
|--------|--------|
| **Frontend** | Vue 3 · TypeScript · Vite · Tailwind CSS · Pinia · TanStack Vue Query · Vue I18n · Vue Router |
| **Backend** | Laravel · Sanctum · Spatie Translatable · Repository / Service pattern |
| **Locales** | `en` / `ar` with RTL support on the public site |

---

## Table of contents

1. [Requirements](#1-requirements)
2. [Project structure](#2-project-structure)
3. [Features delivered](#3-features-delivered)
4. [Backend setup (Laravel)](#4-backend-setup-laravel)
5. [Frontend setup (Vue)](#5-frontend-setup-vue)
6. [Environment variables](#6-environment-variables)
7. [Migrations](#7-migrations)
8. [Seeders](#8-seeders)
9. [Running the app](#9-running-the-app)
10. [API overview](#10-api-overview)
11. [Admin dashboard](#11-admin-dashboard)
12. [Security](#12-security)
13. [Tests](#13-tests)
14. [Performance notes](#14-performance-notes)
15. [Common issues](#15-common-issues)
16. [Related docs](#16-related-docs)

---

## 1. Requirements

### System

| Tool | Version (recommended) |
|------|------------------------|
| PHP | **8.2+** |
| Composer | 2.x |
| MySQL / MariaDB | 8.x / 10.x (or SQLite for local demo) |
| Node.js | **20+** (LTS) |
| npm | 10+ |

### PHP extensions (typical Laravel)

`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `gd` or `imagick` (images)

### Optional

- Postman (API collection)
- Redis (not required for the base project)

---

## 2. Project structure

```text
estatein/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/Api/{Public,Admin}/
│   │   ├── Http/Requests/
│   │   ├── Http/Resources/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   └── Services/
│   ├── database/migrations/
│   ├── database/seeders/
│   ├── routes/api.php
│   └── tests/Feature/
│       ├── AdminAuthTest.php
│       ├── PropertyTest.php
│       ├── SqlInjectionAndXssTest.php
│       └── …
│
└── frontend/                # Vue 3 SPA (or estatein-vue/)
    ├── src/
    │   ├── api/             # Public + admin HTTP clients
    │   ├── components/      # UI + cards + admin widgets
    │   ├── composables/     # Vue Query, form errors, locale helpers
    │   ├── i18n/ + locales/ # en.json / ar.json
    │   ├── stores/          # Pinia (auth, locale)
    │   ├── views/           # Public pages + admin CRUD
    │   └── router/
    ├── .env.example
    └── package.json
```

> Adjust folder names if your backend lives at the repo root.

---

## 3. Features delivered

### Public website

- Pages aligned with the Estatein Figma template: **Home**, **About**, **Properties**, **Property detail**, **Services**, **Contact**
- Responsive layouts (mobile / laptop / desktop) with Tailwind CSS
- **EN / AR** UI chrome via **vue-i18n** + document `dir` (LTR/RTL)
- CMS fields from the API via Spatie JSON (`en` / `ar`) and a `tField()` helper
- Data loading with **TanStack Vue Query** (loading / error / empty + retry)
- Published-only content on public endpoints

### Admin dashboard (`/admin`)

- Sanctum **Bearer** login / logout / session expiry redirect
- CRUD for **properties** (features, multi-image, primary image, publish)
- CRUD for **features**, **FAQs**, **testimonials**, **clients**, **team members**
- Bilingual form fields with **Laravel 422** errors mapped next to inputs (`useFormErrors`, `FieldError`)
- Dashboard counts and sidebar navigation

### Backend architecture

- **Repository → Service → Controller**
- Shared bases: filters, search (translatable JSON), sort whitelist, pagination
- Form Requests + API Resources (list vs detail shaping for properties)
- Sanctum for `/api/v1/admin/*`
- Spatie **Translatable** for CMS columns

---

## 4. Backend setup (Laravel)

```bash
# From the Laravel project root
composer install

cp .env.example .env
php artisan key:generate

# Configure DB in .env (see section 6)

php artisan migrate
php artisan db:seed

# Public disk for uploads
php artisan storage:link

php artisan serve
# → http://127.0.0.1:8000
```

### Recommended packages

```bash
composer require laravel/sanctum
composer require spatie/laravel-translatable
```

---

## 5. Frontend setup (Vue)

```bash
cd frontend   # or estatein-vue

npm install

cp .env.example .env
# Set VITE_API_URL (see section 6)

npm run dev
# → http://127.0.0.1:5173
```

### Production build

```bash
npm run build
npm run preview
```

---

## 6. Environment variables

### Laravel `.env`

```env
APP_NAME=Estatein
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estatein
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local

FRONTEND_URL=http://127.0.0.1:5173
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173,127.0.0.1,127.0.0.1:5173
```

Ensure `config/cors.php` allows the SPA origin and `api/*` paths.

### Vue `.env`

```env
VITE_API_URL=http://127.0.0.1:8000/api/v1
```

Restart Vite after changing `.env`.

---

## 7. Migrations

```bash
php artisan migrate
```

### Main tables

| Table | Notes |
|-------|--------|
| `users` | Admin accounts (Sanctum tokens) |
| `personal_access_tokens` | Sanctum |
| `properties` | JSON `title`, `description`, `location`; price, beds, baths, type, `is_published`, `sort_order` |
| `property_images` | `path`, `is_primary`, `sort_order` |
| `features` | JSON `name` |
| `feature_property` | Pivot + `sort_order` |
| `faqs` | JSON `question`, `answer`; publish + sort |
| `testimonials` | JSON fields; avatar, rating, publish |
| `clients` | JSON `name`; logo, website, publish |
| `team_members` | JSON name/role/bio; photo, publish |

Recommended performance indexes (if not already present):  
`properties (is_published, sort_order)`, `type`, `property_images (property_id, is_primary, sort_order)`, and the same published/sort pattern on FAQs, testimonials, clients, team.

```bash
php artisan migrate:rollback
php artisan migrate:fresh   # destructive
```

---

## 8. Seeders

```bash
php artisan db:seed
```

Typical seed data:

| Seeder focus | Purpose |
|--------------|---------|
| Admin user | Dashboard login for local demo |
| Features / properties | Sample listings + images + feature pivot |
| FAQs, testimonials, clients, team | Bilingual published content for public pages |

**Default admin (demo only — change in production):**

```text
Email:    admin@estatein.test
Password: password
```

After seeding:

```bash
php artisan storage:link
```

---

## 9. Running the app

```bash
# Terminal 1 — API
cd backend
php artisan serve

# Terminal 2 — SPA
cd frontend
npm run dev
```

| URL | Role |
|-----|------|
| http://127.0.0.1:5173 | Public website (EN/AR toggle) |
| http://127.0.0.1:5173/admin/login | Admin dashboard |
| http://127.0.0.1:8000/api/v1/... | JSON API |

Public responses only include **`is_published = true`** rows.

---

## 10. API overview

Base path: **`/api/v1`**

### Public (no auth)

| Method | Endpoint |
|--------|----------|
| GET | `/properties` |
| GET | `/properties/{id}` |
| GET | `/faqs` |
| GET | `/testimonials` |
| GET | `/clients` |
| GET | `/team-members` |

Query params (where supported): `search`, `sort`, `direction`, `sort_locale`, `perPage`, `page`, filters (`type`, `bedrooms`, …).

**Route constraint:** `{id}` is restricted to digits only (`Route::pattern('id', '[0-9]+')`) so malformed IDs return **404** instead of a type-error **500**.

### Admin (`Authorization: Bearer {token}`)

| Area | Endpoints |
|------|-----------|
| Auth | `POST /admin/login`, `POST /admin/logout`, `GET /admin/me` |
| Properties | REST `/admin/properties` + image delete / set-primary |
| Features | `/admin/features` |
| FAQs | `/admin/faqs` |
| Testimonials | `/admin/testimonials` |
| Clients | `/admin/clients` |
| Team | `/admin/team-members` |

Bilingual payloads:

```json
{
  "title": { "en": "Seaside Villa", "ar": "فيلا على البحر" },
  "is_published": true
}
```

Multipart forms use nested keys (`title[en]`, `images[]`, `logo`, `avatar`, `photo`, …).

---

## 11. Admin dashboard

1. Open `/admin/login`
2. Sign in with the seeded admin
3. Manage properties, features, FAQs, testimonials, clients, team
4. Log out from the sidebar

Token key: `admin_token` in `localStorage`.

On **401** from `/admin/*` (except login), the client clears the token and redirects to `/admin/login?expired=1`.

Laravel **422** validation errors are shown in a banner and under the matching fields (including `title.en` / `title.ar`).

---

## 12. Security

### SQL injection

- Queries use **Eloquent / query builder parameter binding**
- **Search** and **filter** values are bound, not concatenated
- **Sort column** is restricted to a **whitelist** (invalid → `422`)
- **Sort locale** is restricted to `en` / `ar` (invalid → `422`) before any JSON path order
- **Route `{id}`** accepts only numeric IDs (`[0-9]+`)

### XSS

- API responses are **`application/json` only**
- User content is stored and returned as **text**; the SPA renders via Vue text interpolation (**no `v-html`** for CMS fields)
- Script / event-handler strings are treated as **literal data**, not executable HTML

### Auth

- Admin routes require a valid Sanctum token
- Guests cannot create/update/delete protected resources
- Logout deletes the current access token

---

## 13. Tests

```bash
cd backend
php artisan test

# Examples
php artisan test --filter=AdminAuthTest
php artisan test --filter=PropertyTest
php artisan test --filter=SqlInjectionAndXssTest
```

### Auth (`AdminAuthTest`)

- Admin can login  
- Invalid credentials fail  
- Guest cannot access `/admin/me`  
- Authenticated admin can access `/admin/me`  
- Logout invalidates the token for later requests  

### Properties (`PropertyTest`)

- Public index/detail **hide unpublished**; unpublished detail → **404**  
- Guest blocked from create/delete  
- Admin CRUD: create with feature sync; update price/publish/features; delete removes images from disk and `property_images`  
- Validation: both title locales required; negative price rejected; non-image (PDF) rejected; unknown `feature_ids` rejected  
- Images: first image primary on create; update adds images without stealing primary; deleting primary reassigns; set-primary moves the flag  
- Create/update responses use **detail** resource shape (includes `is_published`, features, images)—not the abbreviated public list shape  

### SQL injection & XSS (`SqlInjectionAndXssTest`)

**SQL injection (6 tests)**

| Case | Expected |
|------|----------|
| Classic search payloads (`' OR 1=1 --`, `DROP TABLE`, `UNION SELECT`, …) | `200` with **0** matching rows; table intact |
| Search cannot bypass `is_published` | Unpublished never listed; normal search still works |
| Malicious `sort` column | **422** (whitelist) |
| Malicious `sort_locale` | **422** (`in:en,ar`) |
| Filter `type=Villa' OR 1=1 --` | 0 rows; clean `type=Villa` still returns data |
| Route id `1 OR 1=1` / `1; DROP...` | **404**; table survives |

**XSS (4 tests)**

| Case | Expected |
|------|----------|
| `<script>…</script>` stored via admin | Returned verbatim as JSON text; `Content-Type: application/json` |
| `<img … onerror=…>` in description | Same — inert data |
| HTML payloads in search/type | Treated as literal strings |
| Admin create/update with SVG/onload-style payload | Round-trips safely as JSON |

### Frontend (manual)

- [ ] Home loads properties / FAQs / testimonials from API  
- [ ] EN ↔ AR updates chrome and RTL  
- [ ] Property detail shows gallery + features  
- [ ] Admin login → CRUD → public reflects published changes  
- [ ] 422 field errors on empty required locale fields  
- [ ] Expired token redirects to login with notice  

---

## 14. Performance notes

- Public **property list** should not default to loading full feature sets; prefer primary/cover image for cards; full gallery + features on **detail**
- Add DB indexes on `is_published` + `sort_order` (and related FKs) if missing  
- Prefer Nginx/PHP-FPM or Herd over `php artisan serve` when measuring concurrent `/storage` image load  
- Compress large seed/upload images  
- Vue Query `staleTime` reduces duplicate refetches on navigation  

---

## 15. Common issues

| Problem | Fix |
|---------|-----|
| CORS errors | `FRONTEND_URL`, `config/cors.php`, restart API |
| Images 404 | `php artisan storage:link`; public disk |
| 401 on admin | Login again; check Bearer token |
| Empty public lists | Seed + `is_published = true` |
| SPA cannot reach API | `VITE_API_URL` + restart Vite |
| Build fails on TS options | Align `tsconfig` with installed TypeScript version |
| Malformed property id returns 500 | Ensure `Route::pattern('id', '[0-9]+')` in `routes/api.php` |

---

## 16. Related docs

| File | Content |
|------|---------|
| `README.md` | This file — setup, run, API, security, tests |
| `AI_USAGE.md` | How AI was used (planning, Vue-from-React, tests, seeders, env) |
| `INCOMPLETE_FEATURES_AND_ASSUMPTIONS.md` | Known gaps and project assumptions |

---

## Scripts cheat sheet

```bash
# Backend
composer install
php artisan migrate --seed
php artisan storage:link
php artisan serve
php artisan test

# Frontend
npm install
npm run dev
npm run build
```

---

## License / design credit

UI inspired by **Produce UI — Real Estate Business Website UI Template (Dark Theme) — Estatein** (Figma Community).

---

## Assessment notes

This README covers:

- Requirements, installation, configuration, and run instructions  
- Migrations and seeders  
- Public + admin API surface  
- Security posture (SQL injection / XSS / auth) and automated tests  
- Related disclosure documents for incomplete features and AI usage  
