<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\CQRS\Command\Barber\UploadPortfolioCommand;
use Modules\Barber\Models\BarberModel;
use Modules\Barber\Models\BarberPortfolioPhotoModel;

final class UploadPortfolioEloquentResolver
{
    public function resolve(UploadPortfolioCommand $command): BarberPortfolioPhotoModel
    {
        $barber = BarberModel::findOrFail($command->barberId);

        $path = $command->photo->store('portfolio/'.$barber->id, 'public');

        $maxOrder = BarberPortfolioPhotoModel::where('barber_id', $barber->id)
            ->max('sort_order') ?? 0;

        return BarberPortfolioPhotoModel::create([
            'barber_id' => $barber->id,
            'path' => $path,
            'sort_order' => $maxOrder + 1,
        ]);
    }
}
