<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\CQRS\Command\Client\ChangePasswordCommand;
use Modules\Client\Repositories\ChangePasswordEloquentResolver;
use Modules\Client\ValuesObjects\ChangePasswordResult;

final class ChangePasswordHandler
{
    public function __construct(
        private readonly ChangePasswordEloquentResolver $resolver,
    ) {}

    public function handle(ChangePasswordCommand $command): ChangePasswordResult
    {
        $this->resolver->execute($command);

        return new ChangePasswordResult(success: true);
    }
}
