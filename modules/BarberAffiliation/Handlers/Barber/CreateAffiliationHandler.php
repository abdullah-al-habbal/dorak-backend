<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Handlers\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\CreateAffiliationCommand;
use Modules\BarberAffiliation\Eloquent\Resolvers\Barber\CreateAffiliationEloquentResolver;
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
