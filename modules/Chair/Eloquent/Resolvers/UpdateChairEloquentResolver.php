<?php

declare(strict_types=1);

namespace Modules\Chair\Eloquent\Resolvers;

use Modules\Chair\CQRS\Command\UpdateChairCommand;
use Modules\Chair\Models\ChairModel;

final class UpdateChairEloquentResolver
{
    public function resolve(UpdateChairCommand $command): ChairModel
    {
        $chair = ChairModel::with(['branch', 'barber'])->findOrFail($command->chairId);

        $data = [];

        if ($command->label !== null) {
            $data['label'] = $command->label;
        }

        if ($command->status !== null) {
            $data['status'] = $command->status;
        }

        if ($command->barberId !== null) {
            $data['barber_id'] = $command->barberId;
        }

        if ($command->uiMetadata !== null) {
            $data['ui_metadata'] = $command->uiMetadata;
        }

        $chair->update($data);
        $chair->load(['branch', 'barber']);

        return $chair;
    }
}
