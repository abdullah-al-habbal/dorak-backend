# Testing

## Database

- All integration and feature tests use a real MySQL database (not SQLite).
- A separate test database (`dorak_test`) is defined in `.env.testing` and used automatically when `APP_ENV=testing`.
- The `RefreshDatabase` trait is applied via the module's base TestCase, guaranteeing a clean state for every test.

## Module test structure

Each module contains its own `Tests/` directory:

```
modules/{Module}/
├── Tests/
│   ├── Unit/          ← no database; pure logic
│   ├── Integration/   ← database-backed (RefreshDatabase)
│   ├── Feature/       ← HTTP + database (RefreshDatabase)
│   ├── TestCase.php   ← module-specific base (optional)
│   └── Pest.php       ← applies TestCase to Integration/Feature
```

## Running tests

```bash
php artisan test                    # all modules
php artisan test --filter Core      # Core module only
php artisan test --testsuite Unit   # unit tests only
```

## Conventions

- Never call `env()` in test code — use `config()`.
- Pest syntax with `uses(TestCase::class)` for Integration and Feature tests.
- Unit tests do not use `TestCase` — pure logic, no Laravel app boot.
- The root `tests/` directory does not exist.
