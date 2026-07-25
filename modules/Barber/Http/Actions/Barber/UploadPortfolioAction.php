<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Handlers\Barber\UploadPortfolioHandler;
use Modules\Barber\Http\Requests\Barber\UploadPortfolioRequest;
use Modules\Core\Http\Actions\BaseApiAction;
use Illuminate\Support\Facades\Storage;

final class UploadPortfolioAction extends BaseApiAction
{
    public function __construct(
        private readonly UploadPortfolioHandler $handler,
    ) {}

    public function __invoke(UploadPortfolioRequest $request): JsonResponse
    {
        $photo = $this->handler->handle($request->toCommand());

        return $this->created(data: [
            'id' => $photo->id,
            'path' => $photo->path,
            'url' => Storage::disk('public')->url($photo->path),
            'sort_order' => $photo->sort_order,
        ]);
    }
}
