<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Brand\Handlers\Client\CreateBrandHandler;
use Modules\Brand\Http\Requests\Client\CreateBrandRequest;
use Modules\Brand\Http\Resources\Shared\BrandResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateBrandAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateBrandHandler $handler,
    ) {}

    public function __invoke(CreateBrandRequest $request): JsonResponse
    {
        $brand = $this->handler->handle($request->toCommand());

        return $this->created(
            data: new BrandResource($brand),
            message: 'Brand created successfully',
        );
    }
}
