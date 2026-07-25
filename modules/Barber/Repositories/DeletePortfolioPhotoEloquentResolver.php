<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\CQRS\Command\Barber\DeletePortfolioPhotoCommand;
use Modules\Barber\Models\BarberPortfolioPhotoModel;
use Illuminate\Support\Facades\Storage;

final class DeletePortfolioPhotoEloquentResolver
{
    public function resolve(DeletePortfolioPhotoCommand $command): void
    {
        $photo = BarberPortfolioPhotoModel::where('barber_id', $command->barberId)
            ->findOrFail($command->photoId);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();
    }
}
