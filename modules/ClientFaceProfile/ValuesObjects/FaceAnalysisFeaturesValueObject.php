<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\ValuesObjects;

final readonly class FaceAnalysisFeaturesValueObject
{
    private function __construct(
        private int $foreheadWidth,
        private int $jawAngle,
        private string $cheekboneProminence,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            foreheadWidth: isset($data['forehead_width']) ? (int) $data['forehead_width'] : 0,
            jawAngle: isset($data['jaw_angle']) ? (int) $data['jaw_angle'] : 0,
            cheekboneProminence: isset($data['cheekbone_prominence']) ? (string) $data['cheekbone_prominence'] : 'medium',
        );
    }

    public function toArray(): array
    {
        return [
            'forehead_width' => $this->foreheadWidth,
            'jaw_angle' => $this->jawAngle,
            'cheekbone_prominence' => $this->cheekboneProminence,
        ];
    }

    public function foreheadWidth(): int
    {
        return $this->foreheadWidth;
    }

    public function jawAngle(): int
    {
        return $this->jawAngle;
    }

    public function cheekboneProminence(): string
    {
        return $this->cheekboneProminence;
    }
}
