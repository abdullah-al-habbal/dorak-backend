<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\ClientModel;
use Modules\ClientFaceProfile\Eloquent\Casts\FaceAnalysisFeaturesCast;
use Modules\ClientFaceProfile\Eloquent\Casts\RecommendedCatalogItemIdsCast;
use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisFeaturesValueObject;
use Modules\ClientFaceProfile\ValuesObjects\RecommendedCatalogItemIdsValueObject;

#[Fillable([
    'client_id', 'face_profile_id', 'analysis_version', 'analysis_source',
    'detected_face_shape', 'confidence_score', 'detected_features',
    'recommended_catalog_item_ids', 'computed_at',
])]
class ClientFaceAnalysisResultModel extends Model
{
    use HasUuids;

    protected $table = 'client_face_analysis_results';

    protected function casts(): array
    {
        return [
            'detected_face_shape' => DetectedFaceShapeEnum::class,
            'analysis_source' => AnalysisSourceEnum::class,
            'confidence_score' => 'decimal:2',
            'detected_features' => FaceAnalysisFeaturesCast::class,
            'recommended_catalog_item_ids' => RecommendedCatalogItemIdsCast::class,
            'computed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function faceProfile(): BelongsTo
    {
        return $this->belongsTo(ClientFaceProfileModel::class, 'face_profile_id');
    }
}
