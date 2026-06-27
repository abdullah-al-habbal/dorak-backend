<?php
declare(strict_types=1);

namespace Modules\Core\Enums;

use Illuminate\Http\Response;

enum SuccessCodeEnum: string
{
    case SUCCESS = 'SUCCESS';
    case CREATED = 'CREATED';
    case UPDATED = 'UPDATED';
    case DELETED = 'DELETED';

    public function getStatusCode(): int
    {
        return match ($this) {
            self::SUCCESS => Response::HTTP_OK,
            self::CREATED => Response::HTTP_CREATED,
            self::UPDATED => Response::HTTP_OK,
            self::DELETED => Response::HTTP_OK,
        };
    }

    public function getMessageKey(): string
    {
        return match ($this) {
            self::SUCCESS => 'core::messages.success',
            self::CREATED => 'core::messages.created',
            self::UPDATED => 'core::messages.updated',
            self::DELETED => 'core::messages.deleted',
        };
    }
}
