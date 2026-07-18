# 01 — Website Implementation Plan (PRD)

> **Status:** Approved. Proceed to Phase 1.
> **Derived from:** `docs/README.md` (11 docs), `AGENTS.md`, `.claude/skills/architecture/SKILL.md`, existing domain modules.
> **Audience:** Engineering.

---

## 1. Overview

Build the public-facing Dorak Website (Blade/Tailwind/Alpine) and a new `Marketing` domain module to serve dynamic content. The work is split into three phases: **Domain & Data (Marketing module)** → **API Endpoints** → **Frontend (Website module)**.

---

## 2. Architecture Decisions

| Decision | Choice | Rationale |
|----------|--------|-----------|
| New module for marketing content | `Modules\Marketing` | Domain separation: marketing entities are a distinct bounded context. Website module consumes them. |
| Website module for presentation | `Modules\Website` (enhanced) | Houses Blade views, Web routes, Alpine.js, frontend assets. Follows the existing module pattern. |
| Route registration | Each module loads its own routes via its ServiceProvider | Follows Core pattern (`ApplicationServiceProvider::loadApiV1Routes`). |
| API routes pattern | `Action → Handler → EloquentResolver` | Matches existing pattern (HealthCheck). No controllers. |
| Web routes pattern | Invokable Actions returning Blade views | Same Action pattern but return `View` instead of `JsonResponse`. Still no controllers. |
| Floor plan demo | API endpoint in Marketing module, consumes Chair data read-only | Keeps Chair query logic near its domain. Website only renders JSON. |
| CSS theming | CSS variables on `:root`, toggled via `data-theme` attribute | Tailwind v4 `@theme` directive references vars. No duplicate CSS per universe. |
| Translations | `core::` namespace for site chrome, `marketing::` for content | Marketing module manages its own translatable fields via Spatie. |
| Universe visibility | `universe_visibility` column on sections (all, men_only, women_only) | Backend filters content per universe; frontend only renders what it receives. |

---

## 3. Module: `Modules\Marketing` — Marketing Content Domain

### 3.1 Entity Schema

**marketing_pages**

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| slug | string | unique, e.g. 'home', 'features', 'pricing' |
| title | json | translatable (en/ar) |
| meta_description | json | nullable, translatable |
| created_at | timestamp | |
| updated_at | timestamp | |

**sections**

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| page_id | uuid (FK) | -> marketing_pages.id, cascadeOnDelete |
| type | string | hero, feature_list, testimonials, floor_plan_demo, pricing, cta |
| content | json | translatable payload (text, images, links, CTA) |
| sort_order | integer | ordering within a page |
| universe_visibility | string | all, men_only, women_only |
| created_at | timestamp | |
| updated_at | timestamp | |

**testimonials**

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| section_id | uuid (FK) | nullable, -> sections.id, nullOnDelete |
| author_name | string | |
| author_role | string | Owner, Client, Barber |
| quote | json | translatable |
| rating | integer | 1-5 |
| created_at | timestamp | |
| updated_at | timestamp | |

### 3.2 Models

| Model | Table | Key Traits | Translatable |
|-------|-------|------------|--------------|
| `MarketingPageModel` | marketing_pages | HasUuids, HasFactory | title, meta_description |
| `SectionModel` | sections | HasUuids, HasFactory | content |
| `TestimonialModel` | testimonials | HasUuids, HasFactory | quote |

Relationships:
- `MarketingPageModel::sections()` → HasMany `SectionModel`, ordered by `sort_order`
- `SectionModel::page()` → BelongsTo `MarketingPageModel`
- `SectionModel::testimonials()` → HasMany `TestimonialModel`
- `TestimonialModel::section()` → BelongsTo `SectionModel`

### 3.3 EloquentResolvers

| Resolver | Method | Purpose |
|----------|--------|---------|
| `MarketingPageEloquentResolver` | `findBySlug(string $slug): ?MarketingPageModel` | Fetch page by slug with locale-aware translatable fields |
| `SectionEloquentResolver` | `findByPageId(string $pageId): Collection` | Fetch ordered sections for a page, filtered by universe visibility |
| `TestimonialEloquentResolver` | `findBySectionId(string $sectionId): Collection` | Fetch testimonials for a section |

### 3.4 Handlers

| Handler | Input | Output | Logic |
|---------|-------|--------|-------|
| `GetMarketingPageHandler` | `(string $slug, string $locale, string $universe)` | array{page, sections} | Resolves page by slug, filters sections by universe, loads testimonials for testimonial-type sections, translates content to locale |
| `GetFloorPlanDemoHandler` | `(string $locale)` | array{branch, canvas, chairs} | Fetches a seeded "Demo Branch", loads its chairs with ui_metadata, formats into canvas dimensions + chair positions |

### 3.5 Actions

| Action | Method | Route | Request | Response |
|--------|--------|-------|---------|----------|
| `GetMarketingPageAction` | GET | `/api/v1/marketing/pages/{slug}` | `GetMarketingPageRequest` (locale, universe query params) | `ApiResponseBodyValueObject` with page data |
| `GetFloorPlanDemoAction` | GET | `/api/v1/website/floor-plan-demo` | none | `ApiResponseBodyValueObject` with floor plan payload |

### 3.6 Service Provider

`MarketingServiceProvider`:
- `boot()`: load migrations from `Database/Migrations/`, load translations from `Lang/` under `marketing::`, load routes from `Routes/Api/V1/`

### 3.7 Route Files

`modules/Marketing/Routes/Api/V1/marketing_routes.php`:
```php
Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/marketing/pages/{slug}', GetMarketingPageAction::class)->name('marketing.pages.show');
    Route::get('/website/floor-plan-demo', GetFloorPlanDemoAction::class)->name('website.floor-plan-demo');
});
```

---

## 4. Module: `Modules\Website` — Frontend Presentation

### 4.1 Provider

`WebsiteServiceProvider`:
- `boot()`: load views from `Resources/views/` under `website::` namespace, load web routes from `Routes/Web/`

### 4.2 Web Routes

`modules/Website/Routes/Web/website_routes.php`:
- `GET /{locale?}` → `ShowHomePageAction` (default: browser detect → ar)
- `GET /{locale}/features` → `ShowFeaturesPageAction`
- `GET /{locale}/pricing` → `ShowPricingPageAction`

### 4.3 Web Actions (return Blade views)

| Action | Route | Data Fetched | View |
|--------|-------|-------------|------|
| `ShowHomePageAction` | `/` or `/{locale}` | Marketing page 'home' via `GetMarketingPageHandler` | `website::pages.home` |
| `ShowFeaturesPageAction` | `/{locale}/features` | Marketing page 'features' via `GetMarketingPageHandler` | `website::pages.features` |
| `ShowPricingPageAction` | `/{locale}/pricing` | Marketing page 'pricing' via `GetMarketingPageHandler` | `website::pages.pricing` |

All web actions SSR the marketing content into Blade for SEO.

### 4.4 Middleware

`SetLocaleMiddleware`:
- Reads `{locale}` from URL prefix (ar/en)
- Sets `App::setLocale($locale)`
- Shares `$locale` and `$availableLocales` with all views
- Redirects `/` to browser preferred language or `/ar`

### 4.5 Layout & Component Tree

```
layouts/app.blade.php
├── <head> — SEO meta tags from marketing page data, CSS variables
├── <body x-data="websiteStore()">
│   ├── navbar (universe toggle, locale switcher, nav links)
│   ├── {{ $slot }} (page content)
│   │   ├── pages/home.blade.php
│   │   │   ├── hero (SSR from section.content)
│   │   │   ├── features (SSR from section.content)
│   │   │   ├── floor-plan-demo (Alpine.js fetch)
│   │   │   ├── testimonials (SSR from section.content)
│   │   │   └── cta (SSR from section.content)
│   │   ├── pages/features.blade.php
│   │   └── pages/pricing.blade.php
│   └── footer
```

### 4.6 Blade Components

| Component | Props | Notes |
|-----------|-------|-------|
| `navbar` | `$locale`, `$universes` | Responsive, sticky, universe toggle + lang switcher |
| `hero` | `$content` (JSON payload) | Full-width, gradient background via CSS vars, heading, subheading, CTA |
| `features` | `$content` | Grid of feature cards, data from backend |
| `floor-plan-demo` | none | Alpine component, fetches from API, renders SVG chairs |
| `testimonials` | `$testimonials` | Carousel/list of testimonial cards, stars |
| `pricing` | `$content` | Pricing table: Freemium vs Premium |
| `cta` | `$content` | Call-to-action banner |
| `footer` | none | Links, social, copyright |
| `universe-toggle` | `$currentUniverse` | Button group: Neutral / Men / Women |

### 4.7 Tailwind v4 Configuration

CSS variables on `:root` in the master layout:

```css
:root {
  --color-accent-primary: #c8a96e;    /* neutral gold */
  --color-accent-secondary: #8b7355;
  --gradient-hero-from: #1e293b;
  --gradient-hero-to: #334155;
  --font-display: 'Tajawal', sans-serif;
  --font-body: 'Inter', sans-serif;
}

[data-theme="men"] {
  --color-accent-primary: #b8860b;    /* warm copper */
  --color-accent-secondary: #8b6914;
  --gradient-hero-from: #1a1a2e;
  --gradient-hero-to: #16213e;
}

[data-theme="women"] {
  --color-accent-primary: #d4a0b0;    /* elegant rose */
  --color-accent-secondary: #b8869c;
  --gradient-hero-from: #2d1b2e;
  --gradient-hero-to: #1e1320;
}
```

Tailwind v4 `app.css` uses `@theme` directive to reference these vars:

```css
@import "tailwindcss";
@theme {
  --color-accent: var(--color-accent-primary);
  --color-accent-secondary: var(--color-accent-secondary);
}
```

### 4.8 Alpine.js Store

```js
document.addEventListener('alpine:init', () => {
  Alpine.store('website', {
    universe: 'neutral',    // 'neutral' | 'men' | 'women'
    locale: 'ar',           // 'ar' | 'en'
    floorPlan: null,        // fetched JSON
    loadingFloorPlan: false,
    async toggleUniverse(u) {
      this.universe = u;
      document.documentElement.dataset.theme = u;
    },
    async fetchFloorPlan() {
      this.loadingFloorPlan = true;
      const res = await fetch('/api/v1/website/floor-plan-demo');
      this.floorPlan = await res.json();
      this.loadingFloorPlan = false;
    }
  });
});
```

### 4.9 Floor Plan Demo Rendering

Alpine component iterates over `floorPlan.chairs`, renders SVG `<rect>` or `<circle>` elements positioned by `ui_metadata.position_x` / `position_y`, colored by status (`available`=green, `occupied`=red, `maintenance`=yellow).

---

## 5. Bootstrap Registration

Add to `bootstrap/providers.php`:

```php
use Modules\Marketing\Providers\MarketingServiceProvider;
use Modules\Website\Providers\WebsiteServiceProvider;

return [
    // ... existing
    MarketingServiceProvider::class,
    WebsiteServiceProvider::class,
];
```

---

## 6. Seed Data (MVP)

`MarketingSeeder` seeds:
- 1 `MarketingPage` with slug `home`, title {"en": "Home", "ar": "الرئيسية"}
- 1 `MarketingPage` with slug `features`, title {"en": "Features", "ar": "المميزات"}
- 1 `MarketingPage` with slug `pricing`, title {"en": "Pricing", "ar": "الأسعار"}
- 5-7 `Section` records across pages (hero, feature_list, testimonials, floor_plan_demo, pricing, cta)
- 3 `Testimonial` records (one per persona: Owner, Client, Barber)

`FloorPlanDemoSeeder` seeds (or uses existing seeders from Chair module):
- 1 "Demo Branch" with 5 chairs at varied positions
- Each chair has `ui_metadata` with shape, position_x/y, width, height

---

## 7. Implementation Phases

### Phase 1: Domain & Data Layer

| # | Task | Files |
|---|------|-------|
| 1.1 | Create Marketing module directory structure | `modules/Marketing/Config/.gitkeep`, `Providers/`, `Database/Migrations/`, `Database/Factories/`, `Database/Seeders/`, `Models/`, `Http/Actions/`, `Http/Requests/`, `Handlers/`, `Eloquent/Resolvers/`, `Routes/Api/V1/` |
| 1.2 | Create migrations | `0001_01_01_300001_create_marketing_pages_table.php`, `0001_01_01_300002_create_sections_table.php`, `0001_01_01_300003_create_testimonials_table.php` |
| 1.3 | Create Models | `MarketingPageModel.php`, `SectionModel.php`, `TestimonialModel.php` |
| 1.4 | Create EloquentResolvers | `MarketingPageEloquentResolver.php`, `SectionEloquentResolver.php`, `TestimonialEloquentResolver.php` |
| 1.5 | Create Factories | `MarketingPageFactory.php`, `SectionFactory.php`, `TestimonialFactory.php` |
| 1.6 | Create Seeder | `MarketingSeeder.php` (seeds pages + sections + testimonials) |
| 1.7 | Create ServiceProvider | `MarketingServiceProvider.php` |
| 1.8 | Register in `bootstrap/providers.php` | Add `MarketingServiceProvider::class` |

### Phase 2: API Endpoints

| # | Task | Files |
|---|------|-------|
| 2.1 | Create API Request | `GetMarketingPageRequest.php` |
| 2.2 | Create Handlers | `GetMarketingPageHandler.php`, `GetFloorPlanDemoHandler.php` |
| 2.3 | Create Actions | `GetMarketingPageAction.php`, `GetFloorPlanDemoAction.php` |
| 2.4 | Create API routes | `marketing_routes.php` |
| 2.5 | Seed demo branch + chairs | `FloorPlanDemoSeeder.php` (or extend ChairSeeder) |
| 2.6 | Run migrations + seed, test endpoints | `php artisan migrate --seed`, manual curl/test |

### Phase 3: Frontend (Blade + Tailwind + Alpine)

| # | Task | Files |
|---|------|-------|
| 3.1 | Create Website module structure | `modules/Website/Providers/`, `Resources/views/layouts/`, `Resources/views/components/`, `Resources/views/pages/`, `Routes/Web/`, `Http/Actions/`, `Http/Middleware/` |
| 3.2 | Create ServiceProvider | `WebsiteServiceProvider.php` |
| 3.3 | Create Middleware | `SetLocaleMiddleware.php` |
| 3.4 | Create Web routes | `website_routes.php` |
| 3.5 | Create Web Actions | `ShowHomePageAction.php`, `ShowFeaturesPageAction.php`, `ShowPricingPageAction.php` |
| 3.6 | Create master layout | `layouts/app.blade.php` |
| 3.7 | Create Blade components | navbar, hero, features, floor-plan-demo, testimonials, pricing, cta, footer, universe-toggle |
| 3.8 | Create page views | `pages/home.blade.php`, `pages/features.blade.php`, `pages/pricing.blade.php` |
| 3.9 | Setup Tailwind v4 | `resources/css/app.css` with `@theme` + CSS variables |
| 3.10 | Write Alpine.js store | `resources/js/website.js` |
| 3.11 | Register in `bootstrap/providers.php` | Add `WebsiteServiceProvider::class` |
| 3.12 | Register Vite entry points | `vite.config.js` updates |

---

## 8. Naming & Code Conventions

- `declare(strict_types=1);` on every PHP file
- All concrete classes `final`
- Suffixes: `Model`, `Action`, `Handler`, `EloquentResolver`, `Request`, `Factory`, `Seeder`, `Middleware`, `ServiceProvider`
- No Laravel Controllers. Use invokable Actions.
- No hardcoded text in Blade. All content from backend.
- API responses use `ApiResponseBodyValueObject` envelope via `ApiResponseTrait`

---

## 9. Testing Strategy

- Unit tests for EloquentResolvers (mock Eloquent, test query logic)
- Feature tests for API endpoints (assert JSON structure, status codes, locale filtering, universe filtering)
- Feature tests for Web routes (assert view returned, SEO meta present, locale handling)
- Pest PHP tests following existing pattern in `tests/`

---

## 10. Dependencies

- **New:** Spatie Translatable (already in composer.json per AGENTS.md)
- **Existing:** Core module's `BaseApiAction`, `ApiResponseTrait`, `ApiResponseBodyValueObject`
- **Existing:** Chair module's `ChairModel` + `BranchModel` (for floor plan demo query)
- **Frontend:** Alpine.js (already via Vite/Tailwind setup), `@tailwindcss/vite` plugin
