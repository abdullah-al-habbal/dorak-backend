<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Handlers;

use Modules\BarberAffiliation\CQRS\Command\CreateAffiliationCommand;
use Modules\BarberAffiliation\Eloquent\Resolvers\CreateAffiliationEloquentResolver;
use Modules\BarberAffiliation\ValuesObjects\CreateAffiliationResult;

final class CreateAffiliationHandler
{
    public function __construct(
        private readonly CreateAffiliationEloquentResolver $resolver,
    ) {}

    public function handle(CreateAffiliationCommand $command): CreateAffiliationResult
    {
        return new CreateAffiliationResult(
            affiliation: $this->resolver->resolve($command),
        );
    }
}
