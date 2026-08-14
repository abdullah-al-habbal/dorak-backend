## Quick start

```bash
composer setup          # install + .env + key:generate + migrate + npm
composer dev            # server, queue, logs, vite concurrently
composer test           # config:clear + pest/phpunit (MySQL dorak_test via .env.testing)
```

- Husky pre-push hook runs `pint --test` + `composer test` before every push; CI only checks formatting (`pint --test`). Tests are gated locally, not in CI.

## Architecture

- **Core module** `Modules\Core` — foundation config, migrations, shared infrastructure. Feature modules (e.g. `Modules\Client`) add their own providers via `bootstrap/providers.php`.
- No `app/`, `config/`, `routes/` directories
- `bootstrap/app.php` registers `AssignRequestUuidMiddleware` globally; API routes return JSON
- `Modules\Core\Providers\ApplicationServiceProvider::loadAppConfig()` manually `require`s every file in `modules/Core/Config/*.php` and sets config key = filename — Laravel does NOT auto-load these. It **skips when config is cached** (keys already baked into `config.php`; re-requiring would call `env()` which returns null after caching) and **preserves `app.providers`** (injected from `bootstrap/providers.php` — clobbering it breaks provider registration after `config:cache`).
- Translations loaded from `modules/Core/lang/` under `core::` namespace
- Migrations loaded from `modules/Core/Database/Migrations/`
- Error handling via `ErrorCodeEnum` (string backed) with HTTP status + `core::messages.*` lang keys
- `AssignRequestUuidMiddleware` injects UUID into every request

## Config & .env

- All `env()` calls in config files have **no defaults** (2nd arg removed). Every key equals a `.env` / `.env.example` entry.
- `.env` is gitignored. `.env.example` is the reference with all ~100 keys.
- `AUTH_MODEL` is hardcoded as `ClientModel::class` in auth.php — not env-driven (no backslash in .env).
- `MAIL_SENDMAIL_PATH` must be quoted in .env: `"/usr/sbin/sendmail -bs -i"` (space in value).

## Technical Concepts

Before implementing any feature, read `docs/13_technical-concepts.md` — mandatory CRUD patterns (optimistic/pessimistic updates, version locking, idempotency keys, soft delete, PATCH vs PUT, race conditions, batch operations, retry backoff, consistency models, field projection) and Flutter/Laravel integration patterns.

## Code conventions

- **Before writing ANY new code, read `.claude/skills/laravel-attributes/SKILL.md`** — full Laravel 13 attribute reference. Use attributes (not properties) for models, jobs, commands, requests, resources, factories, tests, DI.
- `declare(strict_types=1)` on every file
- Final classes for service providers and middleware
- PHP 8 attributes for Eloquent model meta (`#[Fillable]`, `#[Hidden]`, `#[Table]`, `#[Translatable]`)
- PSR-4: `Modules\\` → `modules/`, `Database\\Seeders\\` → `database/seeders/`, `Tests\\` → `tests/`
- Test config targets a dedicated MySQL/MariaDB test DB via gitignored `.env.testing` (DB `dorak_test`), array cache/session/mail, sync queue

## Remaining Gaps (from `docs/02_prd.md` §5 + §8)

All original backend gaps resolved — Application, OfferedService, Ban, Social Login APIs all built and contract-tested.

Genuine remaining gaps (not in code): A/B testing framework, real AI face analysis (`modules/ClientFaceProfile/Jobs/AnalyzeFacePhotoJob.php` is an MVP stub), Phase 3 "Power & Polish", ~8 open product decisions. See `current_state.md` §3.

> **Frontend (2026-08-09):** Flutter apps removed from this repo — moved to `~/dorak-frontend`, being rebuilt from scratch (new UI/UX). Backend docs referencing `dorak-frontend/apps/...` paths are historical; the API contract sections (`docs/feature-index.md`, `docs/13_technical-concepts.md`) remain the stable contract for the rebuild.

## Framework

- Laravel ^13.8, PHP ^8.3
- Filament ^3.0, Spatie Permissions, Spatie Translatable, Sanctum, Socialite
- Vite with Tailwind CSS v4, `@tailwindcss/vite` plugin
