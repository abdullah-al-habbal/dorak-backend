<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class ChangePasswordResult
{
    public function __construct(
        public bool $success,
    ) {}
}
