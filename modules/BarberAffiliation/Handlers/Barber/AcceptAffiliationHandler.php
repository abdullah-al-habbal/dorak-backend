<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Handlers\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\AcceptAffiliationCommand;
use Modules\BarberAffiliation\Eloquent\Resolvers\Barber\AcceptAffiliationEloquentResolver;
use Modules\BarberAffiliation\ValuesObjects\AcceptAffiliationResult;

final class AcceptAffiliationHandler
{
    public function __construct(
        private readonly AcceptAffiliationEloquentResolver $resolver,
    ) {}

    public function handle(AcceptAffiliationCommand $command): AcceptAffiliationResult
    {
        return $this->resolver->resolve($command);
    }
}
