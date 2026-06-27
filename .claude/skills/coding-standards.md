# Coding standards

## Mandatory

- `declare(strict_types=1)` as the first statement after `<?php` in every file.
- `final` for all service providers and middleware. `abstract` for base classes.
- No PHPDoc comments — clean stubs only.
- No magic strings for statuses, codes, or errors — use enums (`ErrorCodeEnum`, `SuccessCodeEnum`).
- No default values in `env()` calls — all env vars defined in `.env.example`.

## PHP 8 attributes

Use PHP 8 attributes for Eloquent model metadata instead of `$fillable`/`$hidden` arrays:

```php
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class UserModel extends Authenticatable
```

## Naming

- No `Controller` suffix — use invokable action classes: `ListAllBookingsAction`.
- Actions in `Http/Actions/`, handlers in `Handlers/`, resolvers in `Eloquent/Resolvers/`.
- Command/Query objects in `CQRS/Command/` and `CQRS/Query/`.
- PascalCase for class names and module names.
- snake_case for table columns and config keys.

## Autoloading

PSR-4: `Modules\` → `modules/`, `Database\Seeders\` → `database/seeders/`, `Tests\` → `tests/`.
