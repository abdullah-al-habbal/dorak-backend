# BE-18 — Context-Driven Namespacing (Refactor)

## Status: ✅ Complete
## Frontend Consumer: — (code organization refactor, no API changes)

## What Was Built
- All HTTP-layer files (Actions, Handlers, Eloquent/Resolvers, CQRS, Requests, Resources) moved into `{Client|Barber|Business|Shared}/` subdirs across all 17 modules
- `Api/V1/` prefix dropped from file paths — versioning handled at route layer via `BaseModuleServiceProvider::prefix('api/v1')`
- `scripts/migrate_context.php` — automated migration tool (moves file, updates namespace, updates all `use` imports in `modules/`, `bootstrap/`, `config/`)
- ~150+ files reorganized with zero API behavior changes

### Context Map
| Context | Modules |
|---------|---------|
| Client | Client (all), Booking (4 actions), Brand (2), Chair (1), ServiceCatalog (3), Review (1), Ban (1) |
| Barber | BarberAffiliation (4), JobPosting (3) |
| Business | Activation (2) |
| Shared | Explore (4), Brand (2), Chair (2), Currency (3), ServiceCatalog (2), OfferedService (1), Branch (1), JobPosting (2), Review (1), Marketing (2), Core (1), Website (3) |

### Naming Convention
```
modules/{Module}/{Layer}/{Client|Barber|Business|Shared}/{ClassName}.php
```
Example: `modules/Client/Http/Actions/Client/UpdateProfileAction.php`

### Key Decisions
- `Modules\Client` vs `Client` context: when module == context name, subdir creates `Client/Client/` — applied uniformly
- Base classes (`BaseApiAction`, `BaseApiFormRequest`) remain at root (abstract infrastructure)
- `scripts/migrate_context.php` does NOT scan `tests/` — manual namespace fixup required post-migration

## API Endpoints (none — refactoring only)
No API changes. All existing routes and behavior preserved.

## Response Schemas (none)
## Tests: 282 passing, 1532 assertions (full suite — zero regressions)
