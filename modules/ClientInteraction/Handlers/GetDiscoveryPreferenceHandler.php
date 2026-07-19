<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\Eloquent\Resolvers\GetDiscoveryPreferenceEloquentResolver;
use Modules\ClientInteraction\Models\ClientDiscoveryPreferenceModel;

final class GetDiscoveryPreferenceHandler
{
    public function __construct(
        private readonly GetDiscoveryPreferenceEloquentResolver $resolver,
    ) {}

    public function handle(string $clientId): ClientDiscoveryPreferenceModel
    {
        return $this->resolver->resolve($clientId);
    }
}
