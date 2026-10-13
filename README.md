# Estatein — Full-Stack Real Estate Platform

Bilingual (English / Arabic) real-estate website and admin API, based on the **Produce UI — Estatein** Figma template (dark theme).

| Layer | Stack |
|--------|--------|
| **Frontend** | Vue 3 · TypeScript · Vite · Tailwind CSS · Pinia · Vue Query · Vue I18n · Vue Router |
| **Backend** | Laravel · Sanctum · Spatie Translatable · Repository / Service pattern |
| **Locales** | `en` / `ar` with RTL support on the public site |

---

## Table of contents

1. [Requirements](#1-requirements)
2. [Project structure](#2-project-structure)
3. [Backend setup (Laravel)](#3-backend-setup-laravel)
4. [Frontend setup (Vue)](#4-frontend-setup-vue)
5. [Environment variables](#5-environment-variables)
6. [Migrations](#6-migrations)
7. [Seeders](#7-seeders)
8. [Running the app](#8-running-the-app)
9. [API overview](#9-api-overview)
10. [Admin dashboard](#10-admin-dashboard)
11. [Tests](#11-tests)
12. [Common issues](#12-common-issues)

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
├── backend/                 # Laravel API (your Laravel app root)
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
│
└── estatein-vue/            # Vue 3 SPA (this folder if cloned alone)
    ├── src/
    │   ├── api/             # Public + admin HTTP clients
    │   ├── components/      # UI + cards + admin widgets
    │   ├── composables/     # Vue Query hooks
    │   ├── i18n/ + locales/ # en.json / ar.json
    │   ├── stores/          # Pinia (auth, locale)
    │   ├── views/           # Public pages + admin CRUD
    │   └── router/
    ├── .env.example
    └── package.json
```

> Adjust folder names if your backend lives at the repo root instead of `backend/`.

---

## 3. Backend setup (Laravel)

```bash
# From the Laravel project root
composer install

cp .env.example .env
php artisan key:generate

# Configure DB in .env (see section 5)

php artisan migrate
php artisan db:seed

# Public disk for property / avatar / logo / photo uploads
php artisan storage:link

# Sanctum (if not already installed)
# composer require laravel/sanctum
# php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# php artisan migrate

php artisan serve
# → http://127.0.0.1:8000
```

### Recommended packages

```bash
composer require laravel/sanctum
composer require spatie/laravel-translatable
```

### Architecture (backend)

- **Repository** → data access, filters, search (including JSON translatable fields), sort, pagination  
- **Service** → business logic, transactions, image handling, public `is_published` scope  
- **Form requests** → validation (bilingual `title.en` / `title.ar`, uploads)  
- **API resources** → consistent JSON (`fields`, `translated()`, pagination meta)  
- **Sanctum** → Bearer tokens for `/api/v1/admin/*`

---

## 4. Frontend setup (Vue)

```bash
cd estatein-vue   # or your frontend directory

npm install

cp .env.example .env
# Set VITE_API_URL (see section 5)

npm run dev
# → http://127.0.0.1:5173
```

### Production build

```bash
npm run build
npm run preview   # optional local preview of dist/
```

Serve `dist/` behind Nginx, or deploy to Vercel/Netlify with `VITE_API_URL` pointing at your API.

---

## 5. Environment variables

### Laravel `.env` (backend)

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

# SPA origin (CORS)
FRONTEND_URL=http://127.0.0.1:5173
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173,127.0.0.1,127.0.0.1:5173
```

Ensure `config/cors.php` allows your frontend origin and `api/*` paths.

### Vue `.env` (frontend)

```env
VITE_API_URL=http://127.0.0.1:8000/api/v1
```

| Variable | Description |
|----------|-------------|
| `VITE_API_URL` | Base URL for all public + admin API calls (no trailing slash after `v1` is fine as used in code) |

---

## 6. Migrations

Run from the Laravel root:

```bash
php artisan migrate
```

### Main tables

| Table | Notes |
|-------|--------|
| `users` | Admin accounts (Sanctum tokens) |
| `personal_access_tokens` | Sanctum |
| `properties` | JSON: `title`, `description`, `location`; price, beds, baths, type, `is_published`, `sort_order` |
| `property_images` | `path`, `is_primary`, `sort_order` |
| `features` | JSON `name` (translatable) |
| `feature_property` | Pivot + `sort_order` |
| `faqs` | JSON `question`, `answer`; `is_published`, `sort_order` |
| `testimonials` | JSON name/title/body/location; avatar, rating, publish |
| `clients` | JSON `name`; logo, website, publish |
| `team_members` | JSON name/role/bio; photo, publish |

```bash
# Rollback last batch
php artisan migrate:rollback

# Fresh database (destructive)
php artisan migrate:fresh
```

---

## 7. Seeders

```bash
php artisan db:seed
# or specific:
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=DatabaseSeeder
```

### Expected seed data (typical)

| Seeder | Purpose |
|--------|---------|
| `AdminUserSeeder` | Default admin user for dashboard login |
| Features / Properties | Sample listings + images (optional paths) |
| FAQs, Testimonials, Clients, Team | Bilingual published demo content |

**Default admin (if using the project seeder):**

```text
Email:    admin@estatein.test
Password: password
```

Change this in production.

After seeding:

```bash
php artisan storage:link
```

so image URLs under `/storage/...` work.

---

## 8. Running the app

Use **two terminals**:

```bash
# Terminal 1 — API
cd backend   # Laravel root
php artisan serve

# Terminal 2 — SPA
cd estatein-vue
npm run dev
```

| URL | Role |
|-----|------|
| http://127.0.0.1:5173 | Public website (EN/AR toggle) |
| http://127.0.0.1:5173/admin/login | Admin dashboard |
| http://127.0.0.1:8000/api/v1/... | JSON API |

Public content only shows rows with **`is_published = true`**. Unpublished items appear only in admin.

---

## 9. API overview

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

Query params (where supported): `search`, `sort`, `direction`, `perPage`, `page`, filters (`type`, `bedrooms`, …), `relations[]`.

### Admin (`Authorization: Bearer {token}`)

| Area | Endpoints |
|------|-----------|
| Auth | `POST /admin/login`, `POST /admin/logout`, `GET /admin/me` |
| Properties | full REST `/admin/properties` + image delete/primary |
| Features | `/admin/features` |
| FAQs | `/admin/faqs` |
| Testimonials | `/admin/testimonials` |
| Clients | `/admin/clients` |
| Team | `/admin/team-members` |

Bilingual payloads use nested keys, e.g.:

```json
{
  "title": { "en": "Seaside Villa", "ar": "فيلا على البحر" },
  "is_published": true
}
```

Multipart forms use `title[en]`, `images[]`, `logo`, `avatar`, `photo`, etc.

---

## 10. Admin dashboard

1. Open `/admin/login`  
2. Sign in with the seeded admin  
3. Manage:
   - Properties (features, multi-image, primary image, publish)
   - Features, FAQs, Testimonials, Clients, Team  
4. Log out from the sidebar  

Token is stored in `localStorage` as `admin_token` and sent on each API request.

---

## 11. Tests

### Backend (PHPUnit / Pest)

```bash
cd backend
php artisan test

# Filter examples
php artisan test --filter=AdminAuthTest
php artisan test --filter=ContentResourcesTest
```

**Typical coverage**

- Admin login / invalid credentials / me / logout  
- Guest cannot access admin routes  
- Public lists hide unpublished content  
- CRUD happy paths for content resources  
- Validation (e.g. both `en` and `ar` required where defined)

### Frontend

No mandatory unit-test suite in the base scaffold. Manual checks:

- [ ] Home loads properties / FAQs / testimonials from API  
- [ ] EN ↔ AR toggle updates UI chrome and `dir="rtl"`  
- [ ] Property detail shows gallery + features  
- [ ] Admin login → CRUD → public page reflects published changes  

Optional later: Vitest + Vue Testing Library.

---

## 12. Common issues

| Problem | Fix |
|---------|-----|
| CORS errors | Set `FRONTEND_URL`, allow origin in `config/cors.php`, restart `artisan serve` |
| Images 404 | `php artisan storage:link`; ensure files stored on `public` disk |
| 401 on admin | Login again; check Bearer token; `auth:sanctum` on admin routes |
| Empty public lists | Seed data + set `is_published = true` |
| Vue cannot reach API | `VITE_API_URL` must match Laravel URL; restart Vite after `.env` change |
| Translatable search | Use JSON path search in repository (`title->en`, `title->ar`) |
| File upload on update | Admin uses `POST` + `_method=PUT` with `FormData` |

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

- **Requirements** (runtime & stack)  
- **Installation** (Composer + npm)  
- **Configuration** (`.env`, CORS, storage)  
- **Run** (API + SPA)  
- **Migrations** & **seeders**  
- **Tests** (backend + manual frontend checklist)  

For AI-assisted development documentation, see `AI_USAGE.md` if provided in the submission package.
