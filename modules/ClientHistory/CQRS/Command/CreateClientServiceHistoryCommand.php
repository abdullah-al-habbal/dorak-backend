<?php

declare(strict_types=1);

namespace Modules\ClientHistory\CQRS\Command;

use Carbon\Carbon;

final readonly class CreateClientServiceHistoryCommand
{
    public function __construct(
        public string $clientId,
        public string $bookingId,
        public string $barberId,
        public ?string $branchId,
        public ?string $offeredServiceId,
        public ?string $catalogItemId,
        public Carbon $performedAt,
    ) {}
}
