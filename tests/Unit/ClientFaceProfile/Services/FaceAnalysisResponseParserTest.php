<?php

declare(strict_types=1);

use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\Services\FaceAnalysisResponseParser;

it('parses a valid analysis payload', function () {
    $outcome = app(FaceAnalysisResponseParser::class)->parse(json_encode([
        'face_shape' => 'oval',
        'confidence' => 0.92,
        'features' => [
            'forehead_width' => 110,
            'jaw_angle' => 75,
            'cheekbone_prominence' => 'high',
        ],
    ]));

    expect($outcome->detectedFaceShape)->toBe(DetectedFaceShapeEnum::Oval);
    expect($outcome->confidenceScore)->toBe(0.92);
    expect($outcome->analysisSource)->toBe(AnalysisSourceEnum::ThirdPartyApi);
    expect($outcome->analysisVersion)->toBe('openai-vision-v1');
    expect($outcome->detectedFeatures->foreheadWidth())->toBe(110);
    expect($outcome->detectedFeatures->jawAngle())->toBe(75);
    expect($outcome->detectedFeatures->cheekboneProminence())->toBe('high');
});

it('parses data-wrapped payloads', function () {
    $outcome = app(FaceAnalysisResponseParser::class)->parse(json_encode([
        'data' => [
            'face_shape' => 'round',
            'confidence' => 0.84,
            'features' => ['jaw_angle' => 88],
        ],
    ]));

    expect($outcome->detectedFaceShape)->toBe(DetectedFaceShapeEnum::Round);
    expect($outcome->confidenceScore)->toBe(0.84);
});

it('clamps confidence to the 0..1 range', function () {
    $high = app(FaceAnalysisResponseParser::class)->parse(json_encode([
        'face_shape' => 'oval',
        'confidence' => 1.7,
    ]));

    $low = app(FaceAnalysisResponseParser::class)->parse(json_encode([
        'face_shape' => 'oval',
        'confidence' => -0.3,
    ]));

    expect($high->confidenceScore)->toBe(1.0);
    expect($low->confidenceScore)->toBe(0.0);
});

it('defaults missing features to safe values', function () {
    $outcome = app(FaceAnalysisResponseParser::class)->parse(json_encode([
        'face_shape' => 'square',
        'confidence' => 0.5,
    ]));

    expect($outcome->detectedFeatures->foreheadWidth())->toBe(0);
    expect($outcome->detectedFeatures->cheekboneProminence())->toBe('medium');
});

it('throws when the analyzer returns an unsupported face shape', function () {
    app(FaceAnalysisResponseParser::class)->parse(json_encode(['face_shape' => 'rhombus']));
})->throws(InvalidArgumentException::class);

it('throws when the analyzer returns invalid JSON', function () {
    app(FaceAnalysisResponseParser::class)->parse('not-json');
})->throws(JsonException::class);
