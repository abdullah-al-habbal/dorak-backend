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

### Mandatory class-name suffixes

Every class **must** carry a suffix matching its layer/role. No bare names like `User` or `Booking`.

| Layer / Role            | Suffix           | Example                                    |
|-------------------------|------------------|--------------------------------------------|
| Eloquent Model          | `Model`          | `UserModel`, `BookingModel`                |
| Enum                    | `Enum`           | `ErrorCodeEnum`, `SuccessCodeEnum`         |
| Invocable Action        | `Action`         | `ListAllBookingsAction`, `CreateBookingAction` |
| Business Handler        | `Handler`        | `ListAllBookingsHandler`, `CreateBookingHandler` |
| Data Resolver           | `Resolver`       | `ListAllBookingsEloquentResolver`          |
| CQRS Command payload    | `Command`        | `CreateBookingCommand`                     |
| CQRS Query payload      | `Query`          | `FindAvailableChairsQuery`                 |
| Form Request            | `Request`        | `CreateBookingRequest`                     |
| API Resource / Presenter| `Presenter`      | `BookingPresenter`                         |
| Middleware              | `Middleware`     | `AssignRequestUuidMiddleware`              |
| Event                   | `Event`          | `BookingCreatedEvent`                      |
| Listener                | `Listener`       | `SendBookingConfirmationListener`          |
| Service (infrastructure)| `Service`        | `LoggerService`, `TranslatorHandlerService` |
| Service Provider        | `ServiceProvider`| `ApplicationServiceProvider`               |
| Trait                   | `Trait`          | `ApiResponseTrait`                         |
| Seeder                  | `Seeder`         | `UserSeeder`                               |
| Factory                 | `Factory`        | `UserFactory`                              |
| Console Command         | `Command`        | `SyncBookingsCommand`                      |
| Exception               | `Exception`      | `ChairAlreadyBookedException`              |
| Job                     | `Job`            | `SendReminderJob`                          |
| Notification            | `Notification`   | `BookingConfirmationNotification`          |
| Value Object            | `ValueObject`    | `ApiResponseBodyValueObject`               |
| Filament Resource       | `Resource`       | `BookingResource`                          |
| Filament Page           | `Page`           | `FloorPlanPage`                            |
| Filament Widget         | `Widget`         | `BookingsOverviewWidget`                   |

## Autoloading

PSR-4: `Modules\` → `modules/`, `Database\Seeders\` → `database/seeders/`, `Tests\` → `tests/`.
