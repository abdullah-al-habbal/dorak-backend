# Idea: Context-Driven Namespacing Refactor

**ID:** LOCAL-001
**Type:** refactor
**Created:** 2024-05-XX

## In the user's words

Transition from flat per-module structure to Context-Driven (Client, Barber, Business, Shared) namespaced structure for all Actions, Handlers, Resolvers, Requests, Resources, and CQRS objects.

## What this changes

- Prevents "god-modules" by clearly separating business rules per frontend app
- Each HTTP layer file explicitly declares which frontend it serves (`Client/`, `Barber/`, `Business/`, `Shared/`)
- Consistent pattern across all 12+ modules
- Handlers, Resolvers, CQRS follow the same context split

## Naming Resolution: `Modules\Client` vs `Client` Context

The `Client` module (auth/profile) has the same name as the `Client` context (customer frontend). When module == context, sub-directory creates `Client/Client/` redundancy. Decision:

**Always apply context subdirs uniformly**, including when module == context.
- `modules/Client/Http/Actions/Client/LoginAction.php` (namespace: `Modules\Client\Http\Actions\Client`)
- `modules/Booking/Http/Actions/Client/ListUserBookingsAction.php` (namespace: `Modules\Booking\Http\Actions\Client`)

First `Client` = module (business domain). Second `Client` = context (frontend consumer). Same word, different meaning. Uniformity over brevity.

## Deviation from original blueprint

`Api/V1/` prefix dropped from Action/Request/Resource paths. Reason:
- Route versioning already handled by `BaseModuleServiceProvider` (`prefix('api/v1')`)
- Adding `Api/V1/` to file paths adds ~56 extra file moves with no structural benefit
- Pattern: `Http/Actions/{Context}/` not `Http/Actions/Api/V1/{Context}/`

## Context Map Summary

| Context | Modules | Actions |
|---------|---------|---------|
| **Client** | Client (all), Booking (4), Review (1), Brand (2), Chair (1), ServiceCatalog (3), Ban (1) | ~25 |
| **Barber** | BarberAffiliation (4), JobPosting (3) | ~7 |
| **Business** | Activation (2) | ~2 |
| **Shared** | Explore (4), Brand (2), Chair (2), Currency (3), ServiceCatalog (2), OfferedService (1), Branch (1), JobPosting (2), Review (1), Marketing (2), Core (1) | ~22 |

Total ~158 files across all layers.

## Open questions

- Should Route files be context-split too? (e.g., `Routes/Api/V1/Client/client.php`)
- Do Filament panel resources also need context namespacing?
