<?php

declare(strict_types=1);

namespace Modules\Client\Eloquent\Resolvers\Client;

use Illuminate\Support\Facades\Storage;
use Modules\Client\CQRS\Command\Client\UploadAvatarCommand;
use Modules\Client\Models\ClientModel;

final class UploadAvatarEloquentResolver
{
    public function resolve(UploadAvatarCommand $command): ClientModel
    {
        $client = ClientModel::findOrFail($command->clientId);

        if ($command->oldAvatarPath) {
            Storage::disk('public')->delete($command->oldAvatarPath);
        }

        $client->update(['avatar' => $command->filePath]);

        return $client->fresh();
    }
}
