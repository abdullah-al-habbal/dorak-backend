<?php
declare(strict_types=1);

namespace Modules\Core\Enums;

use Illuminate\Http\Response;

enum ErrorCodeEnum: string
{
    case BAD_REQUEST = 'BAD_REQUEST';
    case VALIDATION_FAILED = 'VALIDATION_FAILED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
    case FORBIDDEN = 'FORBIDDEN';
    case RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    case UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';
    case TOO_MANY_REQUESTS = 'TOO_MANY_REQUESTS';
    case SERVER_ERROR = 'SERVER_ERROR';

    public function getStatusCode(): int
    {
        return match ($this) {
            self::BAD_REQUEST => Response::HTTP_BAD_REQUEST,
            self::VALIDATION_FAILED => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::UNAUTHORIZED => Response::HTTP_UNAUTHORIZED,
            self::FORBIDDEN => Response::HTTP_FORBIDDEN,
            self::RESOURCE_NOT_FOUND => Response::HTTP_NOT_FOUND,
            self::UNPROCESSABLE_ENTITY => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::TOO_MANY_REQUESTS => Response::HTTP_TOO_MANY_REQUESTS,
            self::SERVER_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    public function getMessageKey(): string
    {
        return match ($this) {
            self::BAD_REQUEST => 'core::messages.bad_request',
            self::VALIDATION_FAILED => 'core::messages.validation_failed',
            self::UNAUTHORIZED => 'core::messages.unauthorized',
            self::FORBIDDEN => 'core::messages.forbidden',
            self::RESOURCE_NOT_FOUND => 'core::messages.not_found',
            self::UNPROCESSABLE_ENTITY => 'core::messages.unprocessable',
            self::TOO_MANY_REQUESTS => 'core::messages.too_many_requests',
            self::SERVER_ERROR => 'core::messages.server_error',
        };
    }
}
