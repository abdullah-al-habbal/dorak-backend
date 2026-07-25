<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Command\Barber;

use Illuminate\Http\UploadedFile;

final readonly class UploadPortfolioCommand
{
    public function __construct(
        public string $barberId,
        public UploadedFile $photo,
    ) {}
}
