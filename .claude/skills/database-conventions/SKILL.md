---
name: database-conventions
description: Database migration, factory, and seeder conventions: module-owned migrations, Hash::make in factories, Eloquent-only queries. Use whenever creating migrations, factories, or seeders.
---

# Database conventions

## Migrations

Migrations in `modules/{Module}/Database/Migrations/`. Loaded from the module's service provider:

```php
$this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
```

## Primary keys

Current pattern: auto-increment `$table->id()`. UUIDs (`HasUuids` trait) not yet in use — consider for future modules where ID predictability matters.

## Factories

Factories at `modules/{Module}/Database/Factories/`. Example pattern:

```php
class ClientFactory extends Factory
{
    protected $model = ClientModel::class;

    public function definition(): array
    {
        return [
            'password' => Hash::make('password'),
        ];
    }
}
```

Always use `Hash::make('password')` rather than plain text.

## Seeders

Seeders live in `database/seeders/` (not per-module). Use `artisan db:seed` to run.

## Eloquent only

No raw SQL or `DB::select()`/`DB::statement()`. All queries through Eloquent models via resolvers (`Eloquent/Resolvers/`).

Use `Model::query()` or `new Model()->newQuery()` — not `Model::where()` static calls in business logic.

## Spatie packages

- `spatie/laravel-permission` — RBAC roles/permissions
- `spatie/laravel-translatable` — multi-language model fields
