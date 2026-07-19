<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\Models\ClientDiscoveryPreferenceModel;

final class GetDiscoveryPreferenceEloquentResolver
{
    public function resolve(string $clientId): ClientDiscoveryPreferenceModel
    {
        return ClientDiscoveryPreferenceModel::firstOrCreate(
            ['client_id' => $clientId],
            ['default_radius' => 50.0, 'show_unavailable' => true],
        );
    }
}
