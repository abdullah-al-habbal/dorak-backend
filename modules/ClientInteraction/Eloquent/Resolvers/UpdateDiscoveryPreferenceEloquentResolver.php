<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\CQRS\Command\UpdateDiscoveryPreferenceCommand;
use Modules\ClientInteraction\Models\ClientDiscoveryPreferenceModel;

final class UpdateDiscoveryPreferenceEloquentResolver
{
    public function resolve(UpdateDiscoveryPreferenceCommand $command): ClientDiscoveryPreferenceModel
    {
        $pref = ClientDiscoveryPreferenceModel::firstOrCreate(
            ['client_id' => $command->clientId],
        );

        $update = array_filter([
            'preferred_universe' => $command->preferredUniverse,
            'default_radius' => $command->defaultRadius,
            'hidden_brand_ids' => $command->hiddenBrandIds,
            'show_unavailable' => $command->showUnavailable,
        ], fn ($v) => $v !== null);

        if (! empty($update)) {
            $pref->update($update);
        }

        return $pref->fresh();
    }
}
