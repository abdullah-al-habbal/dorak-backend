---
name: cqrs-pattern
description: CQRS pattern implementation with Command/Query objects flowing through Action -> Handler -> EloquentResolver. Use whenever building a read or write endpoint — actions build command/query objects, handlers contain logic, resolvers access data.
---

# CQRS pattern

## Flow

```
Request (validates input)
  → builds Command (mutation) or Query (read) object
    → Action (invokable, extends BaseApiAction)
      → Handler (extends BaseHandler, contains business logic)
        → EloquentResolver (single query/mutation method)
      → Response via ApiResponseTrait
```

Commands mutate state. Queries read state. Both flow through the same Action → Handler → Resolver pipeline.

## Read example: List bookings

### Query

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\CQRS\Query;

final class ListAllBookingsQuery
{
    public function __construct(
        public readonly ?string $status = null,
        public readonly int $perPage = 15,
    ) {}
}
```

### Resolver

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Modules\Core\Eloquent\Resolvers\BaseEloquentResolver;

final class ListAllBookingsEloquentResolver extends BaseEloquentResolver
{
    public function resolve(object $payload): Model|Collection|array|null
    {
        return Booking::query()
            ->when($payload->status, fn ($q) => $q->where('status', $payload->status))
            ->paginate($payload->perPage);
    }
}
```

### Handler

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\Handlers;

use Modules\Booking\CQRS\Query\ListAllBookingsQuery;
use Modules\Booking\Eloquent\Resolvers\ListAllBookingsEloquentResolver;
use Modules\Core\Handlers\BaseHandler;

final class ListAllBookingsHandler extends BaseHandler
{
    public function __construct(
        private readonly ListAllBookingsEloquentResolver $resolver,
    ) {}

    public function handle(object $payload): mixed
    {
        return $this->resolver->resolve($payload);
    }
}
```

### Action

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Modules\Booking\CQRS\Query\ListAllBookingsQuery;
use Modules\Booking\Handlers\ListAllBookingsHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListAllBookingsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListAllBookingsHandler $handler,
    ) {}

    public function __invoke(ListAllBookingsRequest $request): mixed
    {
        $query = new ListAllBookingsQuery(
            status: $request->validated('status'),
            perPage: (int) $request->validated('per_page', 15),
        );

        $result = $this->handler->handle($query);

        return $this->paginated($result, BookingResource::class);
    }
}
```

## Write example: Create booking

### Command

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\CQRS\Command;

final class CreateBookingCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
    ) {}
}
```

### Request

```php
<?php
declare(strict_types=1);

namespace Modules\Booking\Http\Requests;

use Modules\Booking\CQRS\Command\CreateBookingCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateBookingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ];
    }

    public function toCommand(): CreateBookingCommand
    {
        return new CreateBookingCommand(
            title: $this->validated('title'),
            description: $this->validated('description'),
        );
    }
}
```

The request builds the Command/Query object explicitly. The Action calls `$request->toCommand()`.

## Key rules

- Commands in `CQRS/Command/`, Queries in `CQRS/Query/`.
- Handlers in `Handlers/`, Resolvers in `Eloquent/Resolvers/`.
- Resolver has exactly one `resolve()` method — single query/mutation per class.
- Handler is the only class that calls the resolver. Action never calls resolver directly.
- Request validation happens before `toCommand()`/`toQuery()` — payload is always valid.
