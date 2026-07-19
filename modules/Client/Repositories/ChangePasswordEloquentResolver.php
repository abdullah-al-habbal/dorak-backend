<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Client\CQRS\Command\Client\ChangePasswordCommand;
use Modules\Client\Models\ClientModel;

final class ChangePasswordEloquentResolver
{
    public function execute(ChangePasswordCommand $command): void
    {
        $client = ClientModel::findOrFail($command->clientId);

        DB::transaction(function () use ($client, $command): void {
            $client->update(['password' => $command->password]);
            $client->tokens()->delete();
        });
    }
}
