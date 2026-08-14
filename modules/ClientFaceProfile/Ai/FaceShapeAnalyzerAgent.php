<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Ai;

use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::OpenAI)]
#[Timeout(90)]
final class FaceShapeAnalyzerAgent implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are a professional barbershop facial-analysis specialist. '
            .'You examine a client face photo and classify the face shape together with measured features. '
            .'Respond with valid JSON only, using exactly this structure: '
            .'{"face_shape":"oval|round|square|heart|diamond|oblong|triangle",'
            .'"confidence":0.0-1.0,'
            .'"features":{"forehead_width":<int>,"jaw_angle":<int>,"cheekbone_prominence":"low|medium|high"}}. '
            .'Do not include markdown fences, commentary, or trailing text.';
    }
}
