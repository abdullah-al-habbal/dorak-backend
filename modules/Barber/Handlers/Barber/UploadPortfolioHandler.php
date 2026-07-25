<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Modules\Barber\CQRS\Command\Barber\UploadPortfolioCommand;
use Modules\Barber\Models\BarberPortfolioPhotoModel;
use Modules\Barber\Repositories\UploadPortfolioEloquentResolver;

final class UploadPortfolioHandler
{
    public function __construct(
        private readonly UploadPortfolioEloquentResolver $resolver,
    ) {}

    public function handle(UploadPortfolioCommand $command): BarberPortfolioPhotoModel
    {
        return $this->resolver->resolve($command);
    }
}
