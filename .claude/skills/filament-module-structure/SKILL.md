# Filament Module Structure

## Panels
3 panels, each defined by a provider in `modules/Core/Providers/Filament/`:

| Provider | Panel ID | Path | Guard | Discovers from |
|---|---|---|---|---|
| `AdminPanelProvider` | `admin` | `/admin` | `admin` | `modules/*/Filament/Panels/Admin/` |
| `BarberPanelProvider` | `barber` | `/barber` | `barber_dashboard` | `modules/*/Filament/Panels/Barber/` |
| `BranchPanelProvider` | `branch` | `/branch` | `branch` | `modules/*/Filament/Panels/Branch/` |

Discovery is dynamic: each provider scans `modules/` directories and registers any `Filament/Panels/{PanelName}/Resources|Pages|Widgets` it finds.

## Per-module resource tree
```
modules/{Module}/
└── Filament/
    └── Panels/
        ├── Admin/
        │   └── Resources/
        │       └── {Name}/
        │           ├── {Name}Resource.php
        │           ├── Pages/
        │           │   ├── Create{Name}Page.php
        │           │   ├── Edit{Name}Page.php
        │           │   ├── List{Name}sPage.php
        │           │   └── View{Name}Page.php (optional)
        │           ├── Schemas/
        │           │   ├── {Name}FormSchema.php
        │           │   └── {Name}InfolistSchema.php
        │           └── Tables/
        │               └── {Name}sTable.php
        └── Barber/ or Branch/
            └── Resources/
                └── ... (same structure)
```

## Naming conventions
- Resource class ends with `Resource`, directory name matches (no suffix stripping)
- Page classes: `Create{Name}Page`, `Edit{Name}Page`, `List{Name}sPage`
- Form schema: `{Name}FormSchema`
- Table class: `{Name}sTable`
- All classes use `declare(strict_types=1)` and are `final` where possible

## Admin panel resources
- Full CRUD (List, Create, Edit, View)
- BarberResource and BranchResource include `ToggleActivationAction` in action bar
- Show `status` column with badges (pending/enabled/disabled for activatable entities)
- Show `isBanned()` indicator on ClientResource

## Barber panel resources
- Scoped via `ScopePanelToCurrentUser` middleware — all queries automatically filter to the authenticated barber
- Resources are typically read-only (profile view, own services, own bookings, own reviews)
- Override `getEloquentQuery()` only if middleware scoping is insufficient

## Branch panel resources
- Scoped via same middleware — queries filter to the authenticated branch
- Branch-specific resources: chairs, services, affiliations, bookings, jobs, applications
- Same middleware patterns as barber panel

## Activation action
`ToggleActivationAction` lives in `modules/Activation/Filament/Actions/ToggleActivationAction.php`.
Use from any resource:
```php
ToggleActivationAction::make('toggle')
```
It creates an `ActivationLogModel` record and triggers the observer to sync entity status.

## Scoping middleware behavior

Registered only on Barber and Branch panels (not Admin).

**Barber panel scopes:**
- `BarberModel` → `whereKey(auth()->id())`
- `BookingModel` → `where('barber_id', auth()->id())`
- `OfferedServiceModel` → `where('serviceable_id', auth()->id())->where('serviceable_type', 'barber')`
- `ReviewModel` → `whereHas('booking', fn => where('barber_id', auth()->id()))`

**Branch panel scopes:**
- `BranchModel` → `whereKey(auth()->id())`
- `ChairModel` → `where('branch_id', auth()->id())`
- `BookingModel` → `whereHas('chair', fn => where('branch_id', auth()->id()))`
- `OfferedServiceModel` → `where('serviceable_id', auth()->id())->where('serviceable_type', 'branch')`
- `JobPostingModel` → `where('branch_id', auth()->id())`
- `ReviewModel` → `whereHas('booking.chair', fn => where('branch_id', auth()->id()))`
