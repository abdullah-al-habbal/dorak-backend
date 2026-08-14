<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Services;

use Laravel\Ai\Files\StoredImage;
use Modules\ClientFaceProfile\Ai\FaceShapeAnalyzerAgent;
use Modules\ClientFaceProfile\Models\ClientFaceProfileModel;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisOutcomeValueObject;

final class FaceAnalysisService
{
    public function __construct(
        private readonly FaceShapeAnalyzerAgent $agent,
        private readonly FaceAnalysisResponseParser $parser,
    ) {}

    public function analyze(ClientFaceProfileModel $profile): FaceAnalysisOutcomeValueObject
    {
        $response = $this->agent->prompt(
            prompt: 'Analyze the client face photo attached to this request and return your findings as JSON.',
            attachments: [new StoredImage($this->storedPath($profile->image_url), 'public')],
        );

        return $this->parser->parse($response->text);
    }

    private function storedPath(string $imageUrl): string
    {
        $path = parse_url($imageUrl, PHP_URL_PATH) ?: $imageUrl;

        return (string) preg_replace('#^/storage/#', '', $path);
    }
}
