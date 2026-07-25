<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\CQRS\Command\Barber\UpdateScheduleCommand;
use Modules\Barber\Models\BarberScheduleModel;
use Illuminate\Support\Facades\DB;

final class UpdateScheduleEloquentResolver
{
    public function resolve(UpdateScheduleCommand $command): array
    {
        return DB::transaction(function () use ($command) {
            BarberScheduleModel::where('barber_id', $command->barberId)->delete();

            $records = [];

            foreach ($command->schedule as $entry) {
                $records[] = BarberScheduleModel::create([
                    'barber_id' => $command->barberId,
                    'day_of_week' => $entry['day_of_week'],
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'is_active' => $entry['is_active'],
                ]);
            }

            return $records;
        });
    }
}
