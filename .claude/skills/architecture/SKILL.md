---
name: architecture
description: High-level module structure, bootstrap flow, PSR-4 mapping, and dependency direction. Use whenever deciding module placement, understanding the bootstrap order, or checking naming conventions.
---

# Architecture

## Module structure

Core module `Modules\Core` — foundation config, migrations, shared infrastructure. Feature modules (e.g. `Modules\Client`) add their own providers via `bootstrap/providers.php`.

```
modules/Core/
├── Config/                  # ~10 PHP files, manually loaded by ApplicationServiceProvider
├── Console/Commands/
├── CQRS/
│   ├── Command/             # Mutation payload objects
│   └── Query/               # Read payload objects
├── Database/
│   ├── Factories/
│   ├── Migrations/
│   └── Seeders/
├── Eloquent/Resolvers/      # Data access layer
│   └── BaseEloquentResolver.php
├── Enums/                   # ErrorCodeEnum, SuccessCodeEnum
├── Handlers/                # Business logic
│   └── BaseHandler.php
├── Helpers/
│   └── ApiResponseTrait.php
├── Http/
│   ├── Actions/
│   │   └── BaseApiAction.php
│   ├── Middleware/
│   │   └── AssignRequestUuidMiddleware.php
│   └── Requests/
│       └── BaseApiFormRequest.php
├── Models/
├── Providers/
│   └── ApplicationServiceProvider.php
├── Routes/
│   ├── Api/
│   └── Web/
├── Services/
│   ├── LoggerService.php
│   └── TranslatorHandlerService.php
├── ValuesObjects/
│   └── ApiResponseBodyValueObject.php
└── lang/
    ├── en/core.php
    └── ar/core.php
```

## Bootstrap flow

1. `bootstrap/app.php` — creates Laravel app, registers `AssignRequestUuidMiddleware` globally, API routes return JSON
2. `bootstrap/providers.php` — registers `ClientServiceProvider`, `ApplicationServiceProvider`
3. `ApplicationServiceProvider::register()` → `loadConfig()` — manually `require`s every file in `Config/*.php`, sets config key = filename
4. `ApplicationServiceProvider::boot()` — loads migrations from `Database/Migrations/`, translations from `lang/` under `core::` namespace, routes from `Routes/Api/V1/`

## Naming conventions

Every class **must** carry a suffix identifying its layer/role (e.g. `ClientModel`, `CreateBookingAction`, `TranslatorHandlerService`). See [coding-standards](../coding-standards/SKILL.md) for the full suffix table.

## PSR-4 mapping

| Namespace | Path |
|-----------|------|
| `Modules\` | `modules/` |
| `Database\Seeders\` | `database/seeders/` |
| `Tests\` | `tests/` |

## Dependency direction

All future modules depend on Core. Core has no dependencies on other modules.
