<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Eloquent\Resolvers\Barber;

final class BarberAlreadyAffiliatedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Barber already affiliated to a branch.');
    }
}
