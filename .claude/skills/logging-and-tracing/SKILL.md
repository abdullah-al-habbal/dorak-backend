---
name: logging-and-tracing
description: Logging via LoggerService with automatic request UUID tracing. Use whenever adding log statements or debugging request flows.
---

# Logging and tracing

## Log channel

Daily log channel configured via `LOG_CHANNEL` env var. Defaults to `daily` in `.env.example`.

## LoggerService

`Modules\Core\Services\LoggerService` is the canonical way to log.

```php
final class LoggerService
{
    public function info(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
}
```

## Injection

Resolved via DI in constructors:

```php
public function __construct(
    private readonly LoggerService $logger,
) {}

public function someMethod(): void
{
    $this->logger->info('Booking created', ['booking_id' => $id]);
}
```

## Request UUID tracing

Global middleware `AssignRequestUuidMiddleware` injects a UUID into every request. `LoggerService::enrichContext()` automatically adds `request_uuid` to every log entry's context array, enabling traceability across requests.
