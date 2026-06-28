# Dorak Backend (دورك)

A modular, multi‑tenant Laravel backend for the **Dorak** salon and barber shop community platform in Syria.  
Built for a dual‑universe consumer app (Men's Grooming & Women's Beauty), with an interactive floor‑plan booking system, B2B job marketplace, and offline‑resilient architecture.

## Tech Stack

- **PHP** 8.3+ & **Laravel** 13.x
- **MySQL** 8.0 (primary database)
- **Flutter** (client app, API consumed via Sanctum)
- **Filament** 3.x (admin & client panels)
- **Spatie** packages: Translatable, Laravel Permission
- **Laravel** Pennant (feature flags), Sanctum (API tokens), Socialite (social login)
- **Pest** / PHPUnit for testing, **Larastan** for static analysis

## Getting Started

### 1. Clone & Install

```bash
git clone git@github-dbnkalhbalbgmail:abdullah-al-habbal/dorak-backend.git
cd dorak-backend
composer install
```

### 2. Environment

Copy `.env.example` to `.env` and fill in your database, mail, and social provider credentials.  
All `env()` calls in config have **no defaults**, so every key must be present in your `.env` file.

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database

Create a MySQL database and set credentials in `.env`.  
Then run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

### 4. Development

```bash
composer dev      # Starts server, queue, logs, Vite concurrently
composer test     # Runs PHPUnit/Pest tests
composer analyze  # Runs Larastan (PHPStan) at max level
```

## Project Architecture

Dorak Backend follows a **modular monolith** approach. All code lives under `modules/`.  
The `Core` module provides the foundation: configuration, translations, base HTTP classes, CQRS primitives, and the user identity system. Future domain modules (Booking, Barber, Salon, etc.) depend one‑way on `Core`.

```
modules/
└── Core/
    ├── Config/                  # All Laravel config files (loaded manually)
    ├── Console/Commands/
    ├── CQRS/                    # Command & Query payloads
    ├── Database/
    │   ├── Factories/
    │   ├── Migrations/
    │   └── Seeders/
    ├── Eloquent/Resolvers/      # Data access layer (single responsibility)
    ├── Enums/
    ├── Handlers/                # Business logic (one handle() method)
    ├── Helpers/                 # ApiResponseTrait
    ├── Http/
    │   ├── Actions/             # Invocable API actions
    │   ├── Middleware/
    │   └── Requests/            # Form requests → Command/Query
    ├── Models/
    ├── Providers/
    ├── Routes/
    ├── Services/
    ├── ValuesObjects/
    └── lang/                    # ar/ & en/ translation files
```

**Key invariants:**

- **No `app/` or `config/` directories** – everything has been moved into modules.
- **`env()` only in config files** – nowhere else.
- **CQRS everywhere** – Requests build Command/Query objects, Actions dispatch to Handlers, Handlers use Resolvers.
- **Suffix conventions** – `Model`, `Enum`, `Action`, `Handler`, `Resolver`, `Command`, `Query`, `Request`, `Middleware`, `Service`, `Presenter`, etc.
- **All classes `final`** unless intentionally extensible.
- **`declare(strict_types=1)`** on every file.
- **Translations** under `lang/` with `core::messages.*` keys.

## AI Agent Guidance

AI coding agents (like Claude Code, OpenCode) should read:

- `AGENTS.md` – concise overview and conventions.
- `.claude/skills/` – detailed skill files for architecture, coding standards, CQRS, testing, naming, etc.
- `docs/` – product requirements, C4 diagrams, house rules, and more (see `docs/README.md`).

These files are the **source of truth** for any future development.

## Testing

Tests live in the root `tests/` directory under `Unit`, `Integration`, and `Feature` subdirectories.  
Integration and feature tests use a real MySQL database (defined in `.env.testing`) and the `RefreshDatabase` trait.

```bash
php artisan test                 # all tests
php artisan test --filter Core   # only Core-related tests
```

## Deployment

- The application is ready for shared hosting (Hostinger, etc.) or any PHP‑capable server.
- Run `php artisan optimize` before deploying.
- Ensure all `.env` variables are set in production, especially `APP_KEY`, database credentials, and social login secrets.

## Contributing

1. Read `AGENTS.md` and the `.claude/skills/` files.
2. Create a new branch per feature.
3. Follow the established naming conventions and code rules.
4. Write tests in the `tests/` directory.
5. Ensure `composer analyze` and `composer test` pass before pushing.

## License

Proprietary – all rights reserved.
