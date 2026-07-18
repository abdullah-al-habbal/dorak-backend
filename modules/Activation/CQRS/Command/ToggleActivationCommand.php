<?php

declare(strict_types=1);

namespace Modules\Activation\CQRS\Command;

final readonly class ToggleActivationCommand
{
    public function __construct(
        public string $barberId,
        public bool $activate,
        public ?string $reason,
    ) {}
}
