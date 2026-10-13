# Incomplete Features & Assumptions

This document describes known limitations and the assumptions under which the Estatein full-stack project was built and evaluated.

---

## Incomplete features

The following items are intentionally limited, deferred, or only partially implemented relative to a production product. Core assessment requirements (bilingual content, public site, admin CRUD, Sanctum, repository/service pattern, tests for critical flows) are implemented unless noted otherwise in the project README.

### Public website

| Item | Status | Notes |
|------|--------|--------|
| Contact / inquiry forms | **UI only** | Forms exist on Contact, Properties (“Let’s Make it Happen”), and Property Detail (“Inquire”). Submit is client-side only; there is no dedicated `POST` API or admin inbox to persist messages. |
| Carousels (“01 of N”) | **Partial** | Layout and controls match the design visually; full carousel behaviour (swipe, autoplay, continuous pagination across all items) is not fully implemented. |
| Image optimization | **Not implemented** | Uploads are stored as provided. No automatic resize, WebP conversion, or CDN pipeline. Large originals may slow first load of `/storage/...`. |

### Admin dashboard

| Item | Status | Notes |
|------|--------|--------|
| List pagination UI | **Partial** | API supports `perPage` / `page`. Admin tables typically load a fixed page size without page-number controls. |
| Multi-role permissions | **Not implemented** | Any authenticated admin token can access all admin routes. No editor vs super-admin roles. |
| Email notifications | **Not implemented** | No mail on contact, inquiry, or account events. |

### Quality & ops

| Item | Status | Notes |
|------|--------|--------|
| Automated frontend tests | **Not implemented** | Backend feature tests cover auth and property flows (and related cases). Frontend relies on manual QA (locale, public lists, admin CRUD, validation errors, session expiry). |
| HTTP cache for static public lists | **Optional / minimal** | FAQs, testimonials, clients, and team change infrequently; short-lived server cache may not be enabled in all environments. Correctness does not depend on it. |

### Explicitly out of scope for this submission

- Public user registration / customer accounts  
- Payments, bookings, or map integrations  
- Pixel-perfect admin UI (admin is **not** in the Figma file; it is a functional dashboard)  
- Multi-tenancy  

---

## Assumptions

### Content & locales

1. **Dynamic CMS data** (properties, features, FAQs, testimonials, clients, team members) is stored with **Spatie Laravel Translatable** JSON fields (`en` / `ar`).
2. **Static UI chrome** (navigation, buttons, section titles, form labels, errors) is provided by **vue-i18n** locale files (`en.json` / `ar.json`), not the database.
3. Switching language updates UI strings and document direction (`dir="ltr"` / `dir="rtl"`). API bilingual fields are resolved on the client via the active locale.
4. **Public APIs** return only records with **`is_published = true`**. Unpublished/draft content is visible only in the admin API after authentication.

### Authentication & admin

5. **Admin users** are provisioned via **seeders** (or artisan/DB), not via public registration.
6. Default demo credentials (e.g. `admin@estatein.test` / `password`) are for **local development only** and must be changed before any production deployment.
7. Admin authentication uses **Laravel Sanctum personal access tokens** (Bearer header). The SPA stores the token (e.g. `admin_token`) and sends it on `/api/v1/admin/*` requests.
8. On **401** from an admin API call (expired or revoked token), the client clears the token and redirects to `/admin/login` with a session-expired notice. Failed login attempts still show their own validation/error message.

### Infrastructure & API

9. Frontend and backend may run on **different origins** (Vite dev server + `php artisan serve` or equivalent). `VITE_API_URL` must point at the Laravel API base (e.g. `http://127.0.0.1:8000/api/v1`).
10. **CORS** and related env settings (`FRONTEND_URL`, Sanctum stateful domains if used) are configured so the SPA can call the API in local development.
11. Media files use the **`public` disk**. `php artisan storage:link` is required so `/storage/...` URLs work.
12. Response times measured under **`php artisan serve`** with many concurrent image requests are **not** production benchmarks. Production assumes a proper PHP-FPM/Nginx (or Herd/Valet) setup and reasonably sized assets.

### Design & architecture

13. The **public** UI targets the **Estatein / Produce UI** dark Figma template (responsive layouts for mobile, laptop, and desktop) as closely as practical within project time.
14. The **admin dashboard** is a **functional** CRUD interface (lists, forms, bilingual fields, uploads, publish flags, validation errors). It is **not** specified in the Figma design.
15. Backend structure follows **Repository → Service → Controller**, with Form Requests, API Resources, and shared base classes for list filters, search, sort, and pagination.
16. **Property list** responses are intentionally lighter than **detail** (e.g. primary/cover image emphasis, features on detail) to keep public listing payloads smaller.

### Testing

17. Backend **feature tests** (including property public visibility, admin CRUD, validation, and image behaviour) are the automated guarantee for critical API behaviour.
18. Frontend behaviour after the above (field-level 422 errors, expired session redirect, locale switching) is verified manually unless a frontend test suite is added later.

---

## Summary (short form)

**Incomplete:** public contact/inquiry persistence; full carousel behaviour; image resize pipeline; admin pagination UI; multi-role permissions; email; automated frontend tests.

**Assumptions:** seeder-based admin; Sanctum Bearer tokens; Spatie for CMS fields + vue-i18n for chrome; public = published only; admin UI not in Figma; `storage:link` required; demo credentials for local use only; production performance differs from `artisan serve`.

---

*Update this file when an item above is completed so the submission stays accurate.*
