<?php
declare(strict_types=1);

namespace Modules\Core\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Services\TranslatorHandlerService;
use Modules\Core\ValuesObjects\ApiResponseBodyValueObject;

trait ApiResponseTrait
{
    protected function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        return app(TranslatorHandlerService::class)->translate($key, $replace, $locale);
    }

    public function paginated(
        LengthAwarePaginator $paginator,
        string $resourceClass,
        SuccessCodeEnum|string $code = SuccessCodeEnum::SUCCESS,
        ?string $message = null
    ): JsonResponse {
        return $this->success(
            data: $resourceClass::collection($paginator->items()),
            code: $code,
            message: $message,
            meta: [
                'pagination' => [
                    'total' => $paginator->total(),
                    'count' => $paginator->count(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'total_pages' => $paginator->lastPage(),
                ],
            ]
        );
    }

    protected function success(
        mixed $data = null,
        SuccessCodeEnum|string $code = SuccessCodeEnum::SUCCESS,
        ?string $message = null,
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        if ($code instanceof SuccessCodeEnum) {
            $message = $message ?? $this->trans($code->getMessageKey());
            $status = $status === 200 ? $code->getStatusCode() : $status;
            $codeString = $code->value;
        } else {
            $codeString = $code;
            $message = $message ?? $this->trans('core::messages.success');
        }

        $body = new ApiResponseBodyValueObject(
            success: true,
            statusCode: $status,
            code: $codeString,
            message: $message,
            timestamp: Carbon::now()->toISOString(),
            data: $data,
            meta: $meta,
        );

        return response()->json($body->toArray(), $body->statusCode);
    }

    protected function created(
        mixed $data = null,
        SuccessCodeEnum|string $code = SuccessCodeEnum::CREATED,
        ?string $message = null,
        array $meta = []
    ): JsonResponse {
        return $this->success($data, $code, $message, 201, $meta);
    }

    protected function updated(
        mixed $data = null,
        SuccessCodeEnum|string $code = SuccessCodeEnum::UPDATED,
        ?string $message = null,
        array $meta = []
    ): JsonResponse {
        return $this->success($data, $code, $message, 200, $meta);
    }

    protected function deleted(
        mixed $data = null,
        SuccessCodeEnum|string $code = SuccessCodeEnum::DELETED,
        ?string $message = null
    ): JsonResponse {
        return $this->success($data, $code, $message, 200);
    }

    protected function noContent(
        SuccessCodeEnum|string $code = SuccessCodeEnum::SUCCESS,
        ?string $message = null
    ): JsonResponse {
        return $this->success(null, $code, $message, 200);
    }

    protected function error(
        ErrorCodeEnum|string $code = ErrorCodeEnum::BAD_REQUEST,
        ?string $message = null,
        int $status = 400,
        mixed $errors = null,
        array $headers = []
    ): JsonResponse {
        if ($code instanceof ErrorCodeEnum) {
            $message = $message ?? $this->trans($code->getMessageKey());
            $status = $status === 400 ? $code->getStatusCode() : $status;
            $codeString = $code->value;
        } else {
            $codeString = $code;
            $message = $message ?? $this->trans('core::messages.error');
        }

        $body = new ApiResponseBodyValueObject(
            success: false,
            statusCode: $status,
            code: $codeString,
            message: $message,
            timestamp: Carbon::now()->toISOString(),
            errors: $errors,
        );

        return response()->json($body->toArray(), $body->statusCode, $headers);
    }

    protected function validationError(
        mixed $errors = null,
        ?string $message = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::VALIDATION_FAILED
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.validation_failed');
        return $this->error($code, $message, 422, $errors);
    }

    protected function notFound(
        ?string $message = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::RESOURCE_NOT_FOUND
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.not_found');
        return $this->error($code, $message, 404);
    }

    protected function unauthorized(
        ?string $message = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::UNAUTHORIZED
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.unauthorized');
        return $this->error($code, $message, 401);
    }

    protected function forbidden(
        ?string $message = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::FORBIDDEN
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.forbidden');
        return $this->error($code, $message, 403);
    }

    protected function unprocessable(
        ?string $message = null,
        mixed $errors = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::UNPROCESSABLE_ENTITY
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.unprocessable');
        return $this->error($code, $message, 422, $errors);
    }

    protected function tooManyRequests(
        ?string $message = null,
        ErrorCodeEnum|string $code = ErrorCodeEnum::TOO_MANY_REQUESTS
    ): JsonResponse {
        $message = $message ?? $this->trans('core::messages.too_many_requests');
        return $this->error($code, $message, 429);
    }

    protected function businessError(
        ErrorCodeEnum $code,
        ?string $message = null,
        mixed $errors = null
    ): JsonResponse {
        return $this->error($code, $message, $code->getStatusCode(), $errors);
    }
}
