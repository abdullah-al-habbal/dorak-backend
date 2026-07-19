<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Handlers;

use Modules\ClientFaceProfile\CQRS\Command\StoreFaceAnalysisResultCommand;
use Modules\ClientFaceProfile\Eloquent\Resolvers\StoreFaceAnalysisResultEloquentResolver;
use Modules\ClientFaceProfile\Models\ClientFaceAnalysisResultModel;

final class StoreFaceAnalysisResultHandler
{
    public function __construct(
        private readonly StoreFaceAnalysisResultEloquentResolver $resolver,
    ) {}

    public function handle(StoreFaceAnalysisResultCommand $command): ClientFaceAnalysisResultModel
    {
        return $this->resolver->resolve($command);
    }
}
