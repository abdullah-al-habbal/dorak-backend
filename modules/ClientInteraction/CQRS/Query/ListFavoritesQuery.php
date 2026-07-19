<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Query;

use Modules\ClientInteraction\Enums\FavorableTypeEnum;

final readonly class ListFavoritesQuery
{
    public function __construct(
        public string $clientId,
        public ?FavorableTypeEnum $favorableType,
        public int $perPage,
    ) {}
}
