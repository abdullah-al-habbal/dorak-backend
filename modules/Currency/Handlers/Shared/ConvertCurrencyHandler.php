<?php

declare(strict_types=1);

namespace Modules\Currency\Handlers\Shared;

use Modules\Currency\CQRS\Query\Shared\ConvertCurrencyQuery;
use Modules\Currency\Eloquent\Resolvers\Shared\ConvertCurrencyEloquentResolver;
use Modules\Currency\ValuesObjects\ConvertCurrencyResult;

final class ConvertCurrencyHandler
{
    public function __construct(
        private readonly ConvertCurrencyEloquentResolver $resolver,
    ) {}

    public function handle(ConvertCurrencyQuery $query): ?ConvertCurrencyResult
    {
        return $this->resolver->resolve($query);
    }
}
