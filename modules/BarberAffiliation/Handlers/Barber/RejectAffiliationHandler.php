<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Handlers\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\RejectAffiliationCommand;
use Modules\BarberAffiliation\Eloquent\Resolvers\Barber\RejectAffiliationEloquentResolver;
use Modules\BarberAffiliation\ValuesObjects\RejectAffiliationResult;

final class RejectAffiliationHandler
{
    public function __construct(
        private readonly RejectAffiliationEloquentResolver $resolver,
    ) {}

    public function handle(RejectAffiliationCommand $command): RejectAffiliationResult
    {
        return $this->resolver->resolve($command);
    }
}
