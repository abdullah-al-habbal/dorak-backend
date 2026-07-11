<?php

declare(strict_types=1);

namespace Modules\Core\Handlers;

abstract class BaseHandler
{
    abstract public function handle(object $payload): mixed;
}
