<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Client\Models\ClientModel;
use Modules\ClientFaceProfile\Ai\FaceShapeAnalyzerAgent;
use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\Jobs\AnalyzeFacePhotoJob;
use Modules\ClientFaceProfile\Models\ClientFaceAnalysisResultModel;
use Modules\ClientFaceProfile\Models\ClientFaceProfileModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    Storage::fake('public');
});

function createFaceProfile(string $clientId): ClientFaceProfileModel
{
    $file = UploadedFile::fake()->image('face.jpg', 400, 400);
    $path = $file->store('face-profiles', 'public');

    return ClientFaceProfileModel::create([
        'client_id' => $clientId,
        'image_url' => Storage::disk('public')->url($path),
        'image_hash' => md5($file->get()),
        'is_primary' => true,
        'uploaded_at' => now(),
    ]);
}

it('analyzes a face photo and stores the result', function () {
    $profile = createFaceProfile($this->client->id);

    $matchedItem = ServiceCatalogItemModel::factory()->create([
        'is_active' => true,
        'face_shapes' => ['oval'],
    ]);
    ServiceCatalogItemModel::factory()->create([
        'is_active' => true,
        'face_shapes' => ['round'],
    ]);

    FaceShapeAnalyzerAgent::fake([
        [
            'face_shape' => 'oval',
            'confidence' => 0.91,
            'features' => [
                'forehead_width' => 110,
                'jaw_angle' => 75,
                'cheekbone_prominence' => 'medium',
            ],
        ],
    ]);

    AnalyzeFacePhotoJob::dispatchSync($profile->id, $this->client->id);

    $result = ClientFaceAnalysisResultModel::where('face_profile_id', $profile->id)->first();

    expect($result)->not->toBeNull();
    expect($result->client_id)->toBe($this->client->id);
    expect($result->detected_face_shape)->toBe(DetectedFaceShapeEnum::Oval);
    expect((float) $result->confidence_score)->toBe(0.91);
    expect($result->analysis_source)->toBe(AnalysisSourceEnum::ThirdPartyApi);
    expect($result->analysis_version)->toBe('openai-vision-v1');
    expect($result->detected_features->foreheadWidth())->toBe(110);
    expect($result->detected_features->jawAngle())->toBe(75);
    expect($result->recommended_catalog_item_ids->ids())->toContain($matchedItem->id);
});

it('does not prompt the analyzer when the face profile no longer exists', function () {
    AnalyzeFacePhotoJob::dispatchSync('00000000-0000-0000-0000-000000000000', $this->client->id);

    FaceShapeAnalyzerAgent::assertNeverPrompted();
    expect(ClientFaceAnalysisResultModel::count())->toBe(0);
});
