<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;

final class ConvertCurrencyAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $amount = (float) $request->query('amount', 1);

        if ($from === null || $to === null) {
            return $this->validationError(['from' => 'Required', 'to' => 'Required']);
        }

        $fromCurrency = CurrencyModel::where('code', $from)->firstOrFail();
        $toCurrency = CurrencyModel::where('code', $to)->firstOrFail();

        if ($fromCurrency->id === $toCurrency->id) {
            return $this->ok(data: [
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
                'result' => $amount,
                'rate' => 1.0,
            ]);
        }

        $rate = ExchangeRateModel::where('from_currency_id', $fromCurrency->id)
            ->where('to_currency_id', $toCurrency->id)
            ->where('effective_at', '<=', now())
            ->orderByDesc('effective_at')
            ->first();

        if ($rate === null) {
            return $this->notFound(message: 'Exchange rate not found for this pair');
        }

        return $this->ok(data: [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'result' => round($amount * (float) $rate->rate, 2),
            'rate' => (float) $rate->rate,
        ]);
    }
}
