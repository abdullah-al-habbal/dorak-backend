<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Illuminate\Support\Facades\Hash;
use Modules\Client\CQRS\Command\Client\DeleteAccountCommand;
use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\DeleteAccountEloquentResolver;
use Modules\Client\ValuesObjects\DeleteAccountResult;

final class DeleteAccountHandler
{
    public function __construct(
        private readonly DeleteAccountEloquentResolver $resolver,
    ) {}

    public function handle(DeleteAccountCommand $command): DeleteAccountResult
    {
        $client = ClientModel::findOrFail($command->clientId);

        if (! Hash::check($command->password, $client->password)) {
            return DeleteAccountResult::invalidCredentials();
        }

        $deleted = $this->resolver->execute($client);

        if (! $deleted) {
            return DeleteAccountResult::activeBookings();
        }

        return DeleteAccountResult::success();
    }
}
