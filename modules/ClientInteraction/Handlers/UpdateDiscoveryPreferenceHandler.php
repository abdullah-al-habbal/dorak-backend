<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\CQRS\Command\UpdateDiscoveryPreferenceCommand;
use Modules\ClientInteraction\Eloquent\Resolvers\UpdateDiscoveryPreferenceEloquentResolver;
use Modules\ClientInteraction\Models\ClientDiscoveryPreferenceModel;

final class UpdateDiscoveryPreferenceHandler
{
    public function __construct(
        private readonly UpdateDiscoveryPreferenceEloquentResolver $resolver,
    ) {}

    public function handle(UpdateDiscoveryPreferenceCommand $command): ClientDiscoveryPreferenceModel
    {
        return $this->resolver->resolve($command);
    }
}
