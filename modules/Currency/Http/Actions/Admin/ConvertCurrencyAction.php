<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Actions\Admin;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Currency\Handlers\Shared\ConvertCurrencyHandler;
use Modules\Currency\Http\Requests\Admin\ConvertCurrencyRequest;

final class ConvertCurrencyAction extends BaseApiAction
{
    public function __construct(
        private readonly ConvertCurrencyHandler $handler,
    ) {}

    public function __invoke(ConvertCurrencyRequest $request): JsonResponse
    {
        $result = $this->handler->handle($request->toQuery());

        if ($result === null) {
            return $this->notFound(message: 'Exchange rate not found for this pair');
        }

        return $this->ok(data: [
            'from' => $result->from,
            'to' => $result->to,
            'amount' => $result->amount,
            'result' => $result->result,
            'rate' => $result->rate,
        ]);
    }
}
