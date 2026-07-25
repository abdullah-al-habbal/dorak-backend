<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Modules\Barber\CQRS\Command\Barber\DeletePortfolioPhotoCommand;
use Modules\Barber\Repositories\DeletePortfolioPhotoEloquentResolver;

final class DeletePortfolioPhotoHandler
{
    public function __construct(
        private readonly DeletePortfolioPhotoEloquentResolver $resolver,
    ) {}

    public function handle(DeletePortfolioPhotoCommand $command): void
    {
        $this->resolver->resolve($command);
    }
}
