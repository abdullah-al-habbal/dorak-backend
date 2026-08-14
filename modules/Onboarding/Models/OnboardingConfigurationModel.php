<?php

declare(strict_types=1);

namespace Modules\Onboarding\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $locale
 * @property string|null $season
 * @property string $hero_image_path
 * @property bool $is_active
 * @property int $sort_order
 */
#[Fillable(['locale', 'season', 'hero_image_path', 'is_active', 'sort_order'])]
#[Table('onboarding_configurations')]
class OnboardingConfigurationModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
