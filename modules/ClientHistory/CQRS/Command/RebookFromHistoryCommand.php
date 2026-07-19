<?php

declare(strict_types=1);

namespace Modules\ClientHistory\CQRS\Command;

use Carbon\Carbon;

final readonly class RebookFromHistoryCommand
{
    public function __construct(
        public string $historyId,
        public string $clientId,
        public Carbon $timeSlot,
    ) {}
}
