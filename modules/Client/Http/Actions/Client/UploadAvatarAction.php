<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\UploadAvatarHandler;
use Modules\Client\Http\Requests\Client\UploadAvatarRequest;
use Modules\Core\Http\Actions\BaseApiAction;
use Illuminate\Support\Facades\Storage;

final class UploadAvatarAction extends BaseApiAction
{
    public function __construct(
        private readonly UploadAvatarHandler $handler,
    ) {}

    public function __invoke(UploadAvatarRequest $request): JsonResponse
    {
        $client = $this->handler->handle($request->toCommand());

        return $this->ok(data: [
            'avatar_url' => Storage::disk('public')->url($client->avatar),
        ]);
    }
}
