# Translation conventions

## Location

All user-facing strings are in `modules/Core/lang/`:

```
modules/Core/lang/
├── en/core.php
└── ar/core.php
```

## Key structure

Keys use `core::` namespace: `core::messages.success`, `core::messages.not_found`.

## Adding a new message

Add in both languages:

```php
// modules/Core/lang/en/core.php
'messages' => [
    'booking_confirmed' => 'Booking confirmed',
    // ...
],

// modules/Core/lang/ar/core.php
'messages' => [
    'booking_confirmed' => 'تم تأكيد الحجز',
    // ...
],
```

## Retrieving strings

### From any class

```php
app(TranslatorHandlerService::class)->translate('core::messages.booking_confirmed');
```

### From ApiResponseTrait

```php
$this->trans('core::messages.booking_confirmed');
```

### From enums

`ErrorCodeEnum` and `SuccessCodeEnum` have `getMessageKey()` returning the key. `ApiResponseTrait` auto-resolves the message:

```php
$this->error(ErrorCodeEnum::RESOURCE_NOT_FOUND);
// Automatically translates core::messages.not_found
```

## TranslatorHandlerService

`Modules\Core\Services\TranslatorHandlerService` wraps `__()` with fallback — if the key is missing, it returns the key itself instead of an empty string.
