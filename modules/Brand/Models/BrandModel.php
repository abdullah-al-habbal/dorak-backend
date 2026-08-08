<?php

// modules/Brand/Models/BrandModel.php
declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Branch\Models\BranchModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\Preference\Models\PreferenceModel;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['owner_id', 'name', 'description', 'logo', 'base_currency_id'])]
#[Table('brands')]
#[Translatable([
    'name',
    'description',
])]
class BrandModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'name' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'owner_id');
    }

    public function baseCurrency(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'base_currency_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(BranchModel::class, 'brand_id');
    }

    public function affiliations(): MorphMany
    {
        return $this->morphMany(BarberAffiliationModel::class, 'affiliable');
    }

    public function services(): MorphMany
    {
        return $this->morphMany(OfferedServiceModel::class, 'serviceable');
    }

    public function preference(): MorphOne
    {
        return $this->morphOne(PreferenceModel::class, 'preferenceable');
    }
}
