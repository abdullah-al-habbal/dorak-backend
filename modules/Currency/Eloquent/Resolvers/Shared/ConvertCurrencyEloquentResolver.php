<?php

declare(strict_types=1);

namespace Modules\Currency\Eloquent\Resolvers\Shared;

use Modules\Currency\CQRS\Query\Shared\ConvertCurrencyQuery;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;
use Modules\Currency\ValuesObjects\ConvertCurrencyResult;

final class ConvertCurrencyEloquentResolver
{
    public function resolve(ConvertCurrencyQuery $query): ?ConvertCurrencyResult
    {
        $fromCurrency = CurrencyModel::where('code', $query->from)->firstOrFail();
        $toCurrency = CurrencyModel::where('code', $query->to)->firstOrFail();

        if ($fromCurrency->id === $toCurrency->id) {
            return new ConvertCurrencyResult(
                from: $query->from,
                to: $query->to,
                amount: $query->amount,
                result: $query->amount,
                rate: 1.0,
            );
        }

        $rate = ExchangeRateModel::where('from_currency_id', $fromCurrency->id)
            ->where('to_currency_id', $toCurrency->id)
            ->where('effective_at', '<=', now())
            ->orderByDesc('effective_at')
            ->first();

        if ($rate === null) {
            return null;
        }

        return new ConvertCurrencyResult(
            from: $query->from,
            to: $query->to,
            amount: $query->amount,
            result: round($query->amount * (float) $rate->rate, 2),
            rate: (float) $rate->rate,
        );
    }
}
