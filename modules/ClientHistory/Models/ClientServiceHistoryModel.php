<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Barber\Models\BarberModel;
use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Client\Models\ClientModel;
use Modules\ClientHistory\Eloquent\Casts\ServiceHistoryMetadataCast;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

#[Fillable([
    'client_id',
    'booking_id',
    'barber_id',
    'branch_id',
    'offered_service_id',
    'catalog_item_id',
    'performed_at',
    'client_rating',
    'client_notes',
    'barber_notes',
    'metadata',
])]
class ClientServiceHistoryModel extends Model
{
    use HasUuids;

    protected $table = 'client_service_histories';

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'client_rating' => 'integer',
            'metadata' => ServiceHistoryMetadataCast::class,
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(BookingModel::class, 'booking_id');
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class, 'barber_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }

    public function offeredService(): BelongsTo
    {
        return $this->belongsTo(OfferedServiceModel::class, 'offered_service_id');
    }

    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalogItemModel::class, 'catalog_item_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ClientServiceHistoryMediaModel::class, 'history_id');
    }
}
