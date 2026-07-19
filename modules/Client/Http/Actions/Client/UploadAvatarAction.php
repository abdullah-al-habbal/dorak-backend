<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Client\Http\Requests\Client\UploadAvatarRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UploadAvatarAction extends BaseApiAction
{
    public function __invoke(UploadAvatarRequest $request): JsonResponse
    {
        $client = $request->user();

        $path = $request->file('avatar')->store('avatars', 'public');

        if ($client->avatar) {
            Storage::disk('public')->delete($client->avatar);
        }

        $client->update(['avatar' => $path]);

        return $this->ok(data: [
            'avatar_url' => Storage::disk('public')->url($path),
        ]);
    }
}
