<?php
declare(strict_types=1);

namespace Modules\Core\ValuesObjects;

final class ApiResponseBody
{
    public function __construct(
        public readonly bool $success,
        public readonly int $statusCode,
        public readonly string $code,
        public readonly string $message,
        public readonly string $timestamp,
        public readonly mixed $data = null,
        public readonly array $meta = [],
        public readonly mixed $errors = null,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'success' => $this->success,
            'statusCode' => $this->statusCode,
            'code' => $this->code,
            'message' => $this->message,
            'timestamp' => $this->timestamp,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        }

        if (!empty($this->meta)) {
            $payload['meta'] = $this->meta;
        }

        if ($this->errors !== null) {
            $payload['errors'] = $this->errors;
        }

        return $payload;
    }
}
