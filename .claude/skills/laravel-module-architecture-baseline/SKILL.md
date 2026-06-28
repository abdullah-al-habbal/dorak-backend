---
name: laravel-module-architecture-baseline
description: The mandatory backend architecture standard for this salon/barbershop platform. Use this whenever creating or editing ANY backend class - a new module, an action, a handler, an Eloquent resolver, a presenter, a form request, a model, a service provider, or a Filament resource/page - and whenever deciding WHERE a piece of code should live. Enforces the modular-by-domain layout under modules/{Name}/ and the strict Action -> Handler -> EloquentResolver flow (no controllers, no service layer, no repositories; each layer is a single-method final class named by action). Always consult this skill before writing backend code; it overrides default Laravel conventions. Mirrors docs/11.
---

# Module Architecture — baseline standard

The non‑optional shape of the backend. It overrides default Laravel habits (no `BookingController@index`, no `BookingService`, no `BookingRepository`). Full rationale + folder tour: `docs/11_backend-architecture.md`. This skill is **conventions + a checklist**, not code — *ask to have the concrete Laravel code patterns added as `references/patterns.md`.*

## The hard rules

1. **Everything lives in a module.** New backend code goes under `modules/{Name}/`, never in `app/` (which is glue + providers only). One module per aggregate from `docs/06` (`Brand`, `Branch`, `Chair`, `Barber`, `Affiliation`, `Service`, `Booking`, `Review`, `Job`, `Currency`, `Settings`, `Auth`, …).
2. **No Controller, no Service, no Repository — ever.** Use the triad instead:
   - **Action** — invokable (`__invoke`), in `Http/Actions/`, named `{Verb}{Subject}Action` (e.g. `ListAllBookingsAction`). Thin: take the validated request, call the handler, return via presenter/resource. **No business logic, no Eloquent.**
   - **Handler** — in `Handlers/`, named `{Verb}{Subject}Handler`, **exactly one method `handle()`**. All logic for that one action; enforces the relevant `docs/04` rules; calls the resolver for data.
   - **EloquentResolver** — in `Repositories/` (or `Resolvers/` — match the project's chosen folder), named `{Verb}{Subject}EloquentResolver`, **exactly one method**, containing **only** the Eloquent operation. The **only** place Eloquent runs.
3. **One action = one HTTP responsibility = one method per layer.** If a class would gain a second public method, that's a new action (new triad). Nothing accumulates methods.
4. **`final` on every class.** No inheritance. Share via a **trait** or by **calling a collaborator**, never a base class.
5. **No interfaces, no abstract bases.** Concrete classes only; the contract is the method signature.
6. **`newQuery()`, never `Model::query()`.** Resolvers inject the model in the constructor and call `$this->model->newQuery()`.
7. **Filament reads, never queries.** A Filament resource/page calls a resolver (for tables/scoped data) or a presenter (for infolists). It never writes raw Eloquent.

## Provider & boot rules

- Register every module provider **by name** in `bootstrap/providers.php` (no auto‑discovery).
- **`register()` is empty.** No bindings, no deferred providers.
- In `boot()`, guard each `load*` call with `File::exists()` / `File::isDirectory()` — a missing directory is not an error.
- **Never register Filament inside a module provider** — the two panel providers (`AdminPanelProvider`, `ClientPanelProvider`) scan `modules/{Module}/Filament/{Admin|Client}/` at runtime.

## Translation‑first (enforces docs/04 J1/J2)

- Every module ships **both** `lang/en/` and `lang/ar/`.
- **No hardcoded UI strings** — always translation keys (`__('booking::labels.time_slot')`).
- Translatable model attributes use `SpatieTranslatable`.

## Where each file goes (quick map)

```
modules/{Name}/
├── Providers/{Name}ServiceProvider.php
├── Models/{Entity}Model.php            (final)
├── Http/Requests/{Action}Request.php   (final)
├── Http/Actions/{Action}Action.php     (final, __invoke)
├── Handlers/{Action}Handler.php        (final, handle())
├── Repositories/{Action}EloquentResolver.php  (final, one method, newQuery())
├── Presenters/{Name}Presenter.php      (final)
├── Database/{Migrations,Seeders,Factories}/
├── Filament/{Admin,Client}/{Resources,Pages,Widgets}/
├── lang/{en,ar}/{name}.php
└── Resources/views/
```

## Per‑task checklist

- [ ] code is inside the correct `modules/{Name}/` (not `app/`)
- [ ] new behavior = a new **Action + Handler + EloquentResolver** triad, named by action
- [ ] **no** Controller / Service / Repository class created
- [ ] Action is invokable and thin; Handler has only `handle()`; Resolver has one Eloquent‑only method
- [ ] every class is `final`; no `implements`, no `extends` of a project base
- [ ] resolver uses constructor‑injected model + `newQuery()`
- [ ] Filament resources call resolver/presenter, never raw Eloquent
- [ ] provider added to `bootstrap/providers.php`; `register()` empty; `boot()` guarded with `File::exists`
- [ ] both `lang/en` and `lang/ar` present; no hardcoded strings
- [ ] names follow the `docs/11` §6 convention table

## When this skill applies in the loop

CRESCENT phase 6 **Plan/Act** — it dictates *where* code goes and *what classes* to create before any line is written. Pair with **migrations** (module‑owned tables + cascades), **validations** (the form requests), **testing** (one test per cited rule), **type‑safety** (enums/DTOs), and the recommended **rbac / tenancy‑isolation / api‑resources / concurrency‑safety** skills.

> Want the concrete code patterns (an Action + Handler + EloquentResolver triad, a `final` model with Spatie translatable casts, a guarded service provider, a panel provider scanner)? Ask and they'll be added as `references/patterns.md` here.