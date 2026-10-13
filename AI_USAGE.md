# AI Usage

This document describes how generative AI (Grok, built by xAI) was used during the development of the Estatein full-stack assessment project. AI was used as an **assistant** for planning, scaffolding, debugging, and documentation—not as a substitute for understanding or owning the final codebase.

All generated code was reviewed, adapted, and integrated by the developer. Architecture decisions (repository/service pattern, Spatie Translatable, Sanctum, Vue composables, admin vs public API split) were directed by the assessment requirements and refined iteratively with AI support.

---

## Summary of AI usage

| Area | Role of AI |
|------|------------|
| **Project planning** | Break down the assessment into phases, ERD-oriented data model, file hierarchy, commit order, and frontend/backend boundaries |
| **Vue structure & ecosystem** | Map React knowledge to Vue 3 (SFCs, composables, Pinia, Vue Router, Vue Query, vue-i18n); explain unfamiliar APIs and folder conventions |
| **Seeders** | Draft bilingual seed data and seeder structure consistent with Spatie JSON fields and publish flags |
| **Test cases** | Outline and draft PHPUnit/feature tests (auth, published scope, validation, property images) |
| **Environment & setup** | Diagnose install/build failures (Node/TypeScript config, `.env`, CORS, `storage:link`, Sanctum) |
| **Implementation support** | Patterns for BaseRepository/Service/Resources, Vue Query composables, i18n, admin CRUD forms (always reviewed before merge) |

AI was **not** used to invent requirements outside the assessment PDF or the Figma-based UI scope.

---

## 1. Project planning

### Purpose

Structure the work so backend APIs, public Vue UI, admin dashboard, and documentation could be delivered in a clear order.

### Representative prompts

```text
Given this full-stack technical assessment (Laravel API + Vue 3 TypeScript SPA,
bilingual EN/AR, admin CRUD, repository/service pattern), propose a phased
to-do list, suggested project folder hierarchy, and a high-level ERD for
properties, features, FAQs, testimonials, clients, and team members.
```


```text
For static UI copy versus CMS content, should bilingual strings live in the
database or in locale files? Justify a split that satisfies AR/EN and RTL
without unnecessary admin CRUD for every label.
```

---

## 2. Vue.js structure and unfamiliar libraries

### Purpose

The developer’s primary frontend experience is **React**. AI was used to translate familiar React patterns into **Vue 3 + TypeScript**, and to introduce libraries that were new or used differently than in a typical React stack (e.g. Pinia vs Redux/Zustand, Vue Router vs React Router, composables vs hooks, vue-i18n, TanStack Vue Query, `<script setup>` SFCs).

### Representative prompts

```text
I am experienced with React (hooks, React Query, React Router, Context/Zustand)
but less familiar with Vue 3. Map a standard React SPA structure to Vue 3 +
TypeScript + Vite for this Estatein project: where do routes, global state,
API clients, and page-level data fetching live?
```

```text
Explain Vue 3 Composition API composables as the equivalent of custom React
hooks. Show a useProperties composable with TanStack Vue Query that lists and
fetches a single property from our Laravel /api/v1 endpoints, including
loading and error states for the UI.
```

```text
Compare Pinia and Vue Router to Redux/Zustand and React Router for our needs:
auth token storage, locale (en/ar + RTL), and route guards for /admin/* so
unauthenticated users are redirected to login.
```

```text
How does vue-i18n differ from typical React i18n libraries for static UI
strings, and how should we combine it with Spatie-translated API fields
({ en, ar }) so components use t() for chrome and a tField() helper for CMS
content?
```

```text
Clarify Vue SFC patterns I do not use daily in React: script setup, defineProps,
v-model on custom inputs (e.g. bilingual LocalizedFields), and when to use
router-view nested layouts for public site vs admin shell.
```

---

## 3. Seeders

### Purpose

Produce realistic bilingual demo data aligned with models, `is_published`, and image paths for local demos and manual QA.

### Representative prompts

```text
Draft a Laravel DatabaseSeeder plan for Estatein: admin user, features,
properties with feature pivot and images, FAQs, testimonials, clients, and
team members. Use Spatie-style JSON for translatable fields (en/ar) and mark
sample records as published where appropriate.
```

```text
Adjust seed content so public endpoints return enough rows for the home page
sections (featured properties, testimonials, FAQs) and the About page
(team, clients), without hard-coding secrets beyond a documented demo admin.
```

---

## 4. Test cases

### Purpose

Cover critical API behaviour required by the assessment: authentication, authorization boundaries, published-only public data, validation, and property image edge cases.

### Representative prompts

```text
Propose PHPUnit feature tests for admin Sanctum auth: successful login,
invalid credentials, guest cannot access /admin/me, authenticated me,
and logout invalidates the token for subsequent requests.
```

```text
Write feature tests for properties: public index and show hide unpublished
records; guests cannot create or delete; admin can create with feature_ids
and images; update re-syncs features and publish state; delete removes DB
rows and stored image files; validation rejects missing title.ar, negative
price, non-image uploads, and unknown feature_ids; primary image rules on
create, update, delete-primary, and set-primary.
```

```text
After implementing PropertyResource list-vs-detail shaping, suggest assertions
so create/update responses still include is_published, features, and images
and are not truncated to the public list shape.
```

---

## 5. Environment and setup errors

### Purpose

Unblock local development when tooling, TypeScript, or Laravel/Vue integration failed.

### Representative prompts

```text
npm run build fails with erasableSyntaxOnly / TypeScript version mismatch on
tsconfig.node.json while the project uses TypeScript 5.7. Explain the cause
and the minimal config change to restore a clean build.
```

```text
Vue SPA calls Laravel at VITE_API_URL but browser shows CORS or 401 on admin
routes. Checklist for .env, Sanctum, Bearer token storage, and cors config
for a split frontend/backend local setup.
```

```text
Property images return 404 under /storage/... after upload. Confirm
filesystem disk, storage:link, and how the API resource should expose the
public URL.
```

```text
Admin session appears stuck after logout or token revoke (me still returns
200). How should the client and tests clear guards/tokens so the next request
is unauthorized?
```

---

## 6. Implementation patterns (supporting use)

### Purpose

Accelerate consistent patterns already chosen for the project; not blind copy-paste.

### Representative prompts



```text
Map Laravel 422 validation errors (e.g. title.ar) to admin form fields and a
banner message; redirect to login on 401 for /admin/* only.
```

---

## Human review and ownership

- Generated snippets were **read, tested, and often rewritten** to match the real routes, Form Requests, and Figma constraints.
- Bugs found while writing tests (e.g. PropertyResource treating create responses as list shape) were **fixed in application code**, not left as AI output.
- Final behaviour is verified with **`php artisan test`**, manual public/admin flows, and the project README.

---

## Tools

| Tool | Use |
|------|-----|
| Grok (xAI) | Planning, code patterns, tests, seeders, env debugging, README / assumptions docs |

No other AI coding agents were required for the scope described above.

---

## Disclosure statement (short)

> Generative AI (Grok by xAI) assisted with project planning, mapping React experience to Vue 3 structure and libraries, seeder drafts, feature-test design, environment troubleshooting, and implementation patterns. All code was reviewed and integrated by the developer. Assessment requirements and final responsibility for correctness remain with the author.
