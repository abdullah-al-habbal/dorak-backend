<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Barber\CQRS\Command\Barber\DeletePortfolioPhotoCommand;
use Modules\Barber\Handlers\Barber\DeletePortfolioPhotoHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class DeletePortfolioPhotoAction extends BaseApiAction
{
    public function __construct(
        private readonly DeletePortfolioPhotoHandler $handler,
    ) {}

    public function __invoke(string $photo): JsonResponse
    {
        $this->handler->handle(new DeletePortfolioPhotoCommand(
            barberId: (string) Auth::guard('barber')->id(),
            photoId: $photo,
        ));

        return $this->deleted();
    }
}
