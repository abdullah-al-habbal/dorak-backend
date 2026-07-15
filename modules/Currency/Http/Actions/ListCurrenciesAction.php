<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Currency\Http\Resources\CurrencyResource;
use Modules\Currency\Models\CurrencyModel;

final class ListCurrenciesAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $currencies = CurrencyModel::orderBy('code')->get();

        return $this->ok(
            data: CurrencyResource::collection($currencies),
        );
    }
}
