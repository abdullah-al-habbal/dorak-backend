---
name: how-to-add-a-route
description: Route file placement and wiring for API and web routes per module. Use whenever adding a new endpoint to a module's routes.
---

# How to add a route

## Location

Route files live in each module's `Routes/` directory:

```
modules/{Module}/Routes/
├── Api/routes.php      # API endpoints — return JSON
└── Web/web.php         # Web/Filament routes — handled by Filament panels
```

## Wiring

From the module's service provider `boot()`:

```php
public function boot(): void
{
    $this->loadRoutesFrom(__DIR__ . '/../Routes/Api/routes.php');
    $this->loadRoutesFrom(__DIR__ . '/../Routes/Web/web.php');
}
```

## API route example

```php
<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Actions\ListAllBookingsAction;

Route::get('/bookings', ListAllBookingsAction::class);
```

API routes return JSON. Actions should extend `BaseApiAction` and use `ApiResponseTrait`.

## Web routes

Web routes (Filament panels, Blade pages) use standard Laravel routing. Filament handles its own route registration via panel configuration.

## Notes

- Core routes are loaded via `ApplicationServiceProvider::loadApiV1Routes()` — see `Routes/Api/V1/api_v1_routes.php` for the current route file.
- All new modules should wire their route files in their own service provider.
- Use invokable actions, not controllers.
