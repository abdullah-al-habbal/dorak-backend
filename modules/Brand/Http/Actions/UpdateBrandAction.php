<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Brand\Handlers\UpdateBrandHandler;
use Modules\Brand\Http\Requests\UpdateBrandRequest;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateBrandAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateBrandHandler $handler,
    ) {}

    public function __invoke(UpdateBrandRequest $request, string $brand): JsonResponse
    {
        $brand = $this->handler->handle($request->toCommand($brand));

        return $this->ok(
            data: new BrandResource($brand),
            message: 'Brand updated successfully',
        );
    }
}
