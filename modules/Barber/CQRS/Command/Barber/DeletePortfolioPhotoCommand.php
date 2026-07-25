<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Command\Barber;

final readonly class DeletePortfolioPhotoCommand
{
    public function __construct(
        public string $barberId,
        public string $photoId,
    ) {}
}
