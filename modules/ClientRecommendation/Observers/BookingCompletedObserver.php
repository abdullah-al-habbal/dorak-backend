<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Observers;

use Modules\Barber\Models\BarberModel;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\Client\Models\ClientModel;
use Modules\ClientRecommendation\Enums\EdgeTypeEnum;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;

final class BookingCompletedObserver
{
    public function updated(BookingModel $booking): void
    {
        if (! $booking->wasChanged('status')) {
            return;
        }

        if ($booking->status !== BookingStatus::Completed) {
            return;
        }

        RecommendationEdgeModel::create([
            'source_type' => ClientModel::class,
            'source_id' => $booking->client_id,
            'target_type' => BarberModel::class,
            'target_id' => $booking->barber_id,
            'edge_type' => EdgeTypeEnum::History->value,
            'weight' => 0.7,
        ]);
    }
}
