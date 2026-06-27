## Quick start

```bash
composer setup          # install + .env + key:generate + migrate + npm
composer dev            # server, queue, logs, vite concurrently
composer test           # config:clear + pest/phpunit
composer analyze        # phpstan level 9 via larastan
```

## Architecture

- **Single module** `Modules\Core` — no `app/`, `config/`, `routes/` directories
- `bootstrap/app.php` registers `AssignRequestUuidMiddleware` globally; API routes return JSON
- `Modules\Core\Providers\ApplicationServiceProvider::loadConfig()` manually `require`s every file in `modules/Core/Config/*.php` and sets config key = filename — Laravel does NOT auto-load these
- Translations loaded from `modules/Core/lang/` under `core::` namespace
- Migrations loaded from `modules/Core/Database/Migrations/`
- Error handling via `ErrorCodeEnum` (string backed) with HTTP status + `core::messages.*` lang keys
- `AssignRequestUuidMiddleware` injects UUID into every request

## Config & .env

- All `env()` calls in config files have **no defaults** (2nd arg removed). Every key equals a `.env` / `.env.example` entry.
- `.env` is gitignored. `.env.example` is the reference with all ~100 keys.
- `AUTH_MODEL` is hardcoded as `UserModel::class` in auth.php — not env-driven (no backslash in .env).
- `MAIL_SENDMAIL_PATH` must be quoted in .env: `"/usr/sbin/sendmail -bs -i"` (space in value).

## Code conventions

- `declare(strict_types=1)` on every file
- Final classes for service providers and middleware
- PHP 8 attributes for Eloquent model meta (`#[Fillable]`, `#[Hidden]`)
- PSR-4: `Modules\\` → `modules/`, `Database\\Seeders\\` → `database/seeders/`, `Tests\\` → `tests/`
- Test config uses sqlite `:memory:`, array cache/session/mail, sync queue

## Framework

- Laravel ^13.8, PHP ^8.3
- Filament ^3.0, Spatie Permissions, Spatie Translatable, Sanctum, Socialite
- Vite with Tailwind CSS v4, `@tailwindcss/vite` plugin
