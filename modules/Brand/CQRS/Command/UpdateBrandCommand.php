<?php

declare(strict_types=1);

namespace Modules\Brand\CQRS\Command;

final readonly class UpdateBrandCommand
{
    public function __construct(
        public string $brandId,
        public ?string $nameEn,
        public ?string $nameAr,
        public ?string $ownerId,
        public ?string $baseCurrencyId,
        public ?string $logo,
        public ?string $descriptionEn,
        public ?string $descriptionAr,
    ) {}
}
