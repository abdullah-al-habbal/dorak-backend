# Architecture

## Module structure

Single module `Modules\Core` — no `app/`, `config/`, `routes/` directories.

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
2. `bootstrap/providers.php` — registers `ApplicationServiceProvider`
3. `ApplicationServiceProvider::register()` → `loadConfig()` — manually `require`s every file in `Config/*.php`, sets config key = filename
4. `ApplicationServiceProvider::boot()` — loads migrations from `Database/Migrations/`, translations from `lang/` under `core::` namespace

## Naming conventions

Every class **must** carry a suffix identifying its layer/role (e.g. `UserModel`, `CreateBookingAction`, `TranslatorHandlerService`). See [coding-standards.md](coding-standards.md) for the full suffix table.

## PSR-4 mapping

| Namespace | Path |
|-----------|------|
| `Modules\` | `modules/` |
| `Database\Seeders\` | `database/seeders/` |
| `Tests\` | `tests/` |

## Dependency direction

All future modules depend on Core. Core has no dependencies on other modules.
