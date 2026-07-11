# 02 — Complete File Manifest

> Every file to create, organized by phase. 42 files total.

---

## Phase 1: Domain & Data Layer (18 files)

```
modules/Marketing/
├── Providers/
│   └── MarketingServiceProvider.php                          # loads migrations, routes, translations
├── Database/
│   ├── Migrations/
│   │   ├── 0001_01_01_300001_create_marketing_pages_table.php
│   │   ├── 0001_01_01_300002_create_sections_table.php
│   │   └── 0001_01_01_300003_create_testimonials_table.php
│   ├── Factories/
│   │   ├── MarketingPageFactory.php
│   │   ├── SectionFactory.php
│   │   └── TestimonialFactory.php
│   └── Seeders/
│       └── MarketingSeeder.php                                # seeds 3 pages + sections + testimonials
├── Models/
│   ├── MarketingPageModel.php
│   ├── SectionModel.php
│   └── TestimonialModel.php
├── Eloquent/
│   └── Resolvers/
│       ├── MarketingPageEloquentResolver.php
│       ├── SectionEloquentResolver.php
│       └── TestimonialEloquentResolver.php
├── Http/
│   └── Requests/
│       ├── GetMarketingPageRequest.php
├── Routes/
│   └── Api/
│       └── V1/
│           └── marketing_routes.php
├── Lang/
│   ├── en/marketing.php
│   └── ar/marketing.php
└── Config/
    └── .gitkeep

bootstrap/providers.php                                        # +1 line: MarketingServiceProvider::class
```

---

## Phase 2: API Endpoints (5 files)

```
modules/Marketing/
├── Handlers/
│   ├── GetMarketingPageHandler.php
│   └── GetFloorPlanDemoHandler.php
├── Http/
│   └── Actions/
│       ├── GetMarketingPageAction.php
│       └── GetFloorPlanDemoAction.php

database/seeders/
└── FloorPlanDemoSeeder.php                                     # seeds demo branch + 5 chairs with ui_metadata
```

---

## Phase 3: Frontend (19 files)

```
modules/Website/
├── Providers/
│   └── WebsiteServiceProvider.php                              # loads views, web routes
├── Routes/
│   └── Web/
│       └── website_routes.php
├── Http/
│   ├── Actions/
│   │   ├── ShowHomePageAction.php
│   │   ├── ShowFeaturesPageAction.php
│   │   └── ShowPricingPageAction.php
│   └── Middleware/
│       └── SetLocaleMiddleware.php
├── Resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── components/
│       │   ├── navbar.blade.php
│       │   ├── hero.blade.php
│       │   ├── features.blade.php
│       │   ├── floor-plan-demo.blade.php
│       │   ├── testimonials.blade.php
│       │   ├── pricing.blade.php
│       │   ├── cta.blade.php
│       │   ├── footer.blade.php
│       │   └── universe-toggle.blade.php
│       └── pages/
│           ├── home.blade.php
│           ├── features.blade.php
│           └── pricing.blade.php
├── Assets/
│   └── js/
│       └── website.js                                          # Alpine store

bootstrap/providers.php                                         # +1 line: WebsiteServiceProvider::class
resources/css/app.css                                           # update: @theme + CSS variables
```

---

## Summary

| Phase | Files | Type |
|-------|-------|------|
| Phase 1 | 18 | Migrations, Models, Resolvers, Factories, Seeders, Provider, Lang |
| Phase 2 | 5 | Handlers, Actions, Seeder |
| Phase 3 | 19 | Provider, Routes, Actions, Middleware, Blade views, JS, CSS |
| **Total** | **42** | |

Registration changes: `bootstrap/providers.php` (+2 lines), `resources/css/app.css` (update).
