<?php

declare(strict_types=1);

namespace Modules\Brand\CQRS\Command\Client;

final readonly class CreateBrandCommand
{
    public function __construct(
        public string $ownerId,
        public string $nameEn,
        public string $nameAr,
        public string $baseCurrencyId,
        public ?string $logo,
        public ?string $descriptionEn,
        public ?string $descriptionAr,
    ) {}
}
