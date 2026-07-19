<?php

declare(strict_types=1);

namespace Modules\ClientHistory\CQRS\Command;

use Modules\ClientHistory\Enums\HistoryMediaType;

final readonly class AttachHistoryMediaCommand
{
    public function __construct(
        public string $historyId,
        public string $photoUrl,
        public HistoryMediaType $photoType,
    ) {}
}
