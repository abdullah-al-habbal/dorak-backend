<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Command\Barber;

final readonly class UpdateProfileCommand
{
    public function __construct(
        public string $barberId,
        public ?array $name,
        public ?string $email,
        public ?string $password,
        public ?bool $isFreelancer,
    ) {}
}
