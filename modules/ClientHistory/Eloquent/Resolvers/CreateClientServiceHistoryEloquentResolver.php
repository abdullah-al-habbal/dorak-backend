<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Eloquent\Resolvers;

use Modules\ClientHistory\CQRS\Command\CreateClientServiceHistoryCommand;
use Modules\ClientHistory\Models\ClientServiceHistoryModel;

final class CreateClientServiceHistoryEloquentResolver
{
    public function resolve(CreateClientServiceHistoryCommand $command): ClientServiceHistoryModel
    {
        return ClientServiceHistoryModel::create([
            'client_id' => $command->clientId,
            'booking_id' => $command->bookingId,
            'barber_id' => $command->barberId,
            'branch_id' => $command->branchId,
            'offered_service_id' => $command->offeredServiceId,
            'catalog_item_id' => $command->catalogItemId,
            'performed_at' => $command->performedAt,
        ]);
    }
}
