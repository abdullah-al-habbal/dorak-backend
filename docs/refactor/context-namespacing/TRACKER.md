# Refactor Tracker: Context-Driven Namespacing

**Status:** IN PROGRESS
**Current Phase:** Phase 6 (Ralph Loop)

## Audit & Discovery
- [x] Run bash script to list all current Actions, Handlers, Resolvers, CQRS, Requests, Resources.
- [x] Categorize each file into: Client, Barber, Business, or Shared.

## Prototype & Validation
- [x] Create `scripts/migrate_context.php`.
- [x] Test script on `Marketing/GetFloorPlanDemoAction` — verified.
- [x] Verify namespace update and import replacement.

## Execution Loop (Module by Module)
- [x] `Marketing` module
- [x] `Activation` module
- [x] `Booking` module
- [x] `Brand` module
- [x] `BarberAffiliation` module
- [x] `Chair` module
- [x] `Review` module
- [ ] `Explore` module
- [ ] `JobPosting` module
- [ ] `ServiceCatalog` module
- [ ] `Currency` module
- [ ] `OfferedService` module
- [ ] `Branch` module
- [ ] `Ban` module
- [ ] `Client` module
- [ ] `Core` module
- [ ] `Website` module

## QA & Verification
- [ ] Run `composer dump-autoload`
- [ ] Run `php artisan route:clear` & `config:clear`
- [ ] Grep for old flat namespaces to ensure 100% cleanup.
- [ ] Run `composer test`

## Blockers / Errors
- (none)
