<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Actions\Admin;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Currency\Http\Resources\Shared\ExchangeRateResource;
use Modules\Currency\Models\ExchangeRateModel;

final class ListExchangeRatesAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $rates = ExchangeRateModel::with(['fromCurrency', 'toCurrency'])
            ->orderByDesc('effective_at')
            ->get();

        return $this->ok(
            data: ExchangeRateResource::collection($rates),
        );
    }
}
