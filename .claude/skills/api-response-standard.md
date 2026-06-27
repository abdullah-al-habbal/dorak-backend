# API response standard

## Unified JSON shape

```json
{
    "success": true,
    "statusCode": 200,
    "code": "SUCCESS",
    "message": "Success",
    "timestamp": "2026-06-27T12:00:00.000Z",
    "data": {},
    "meta": {},
    "errors": null
}
```

`success`, `statusCode`, `code`, `message`, `timestamp` always present. `data`, `meta`, `errors` appear only when non-null.

## ApiResponseBody

ValueObject at `Modules\Core\ValuesObjects\ApiResponseBody`. Fields: `success`, `statusCode`, `code`, `message`, `timestamp`, `data`, `meta`, `errors`.

## ApiResponseTrait

Trait at `Modules\Core\Helpers\ApiResponseTrait`. Mixed into `BaseApiAction` and `BaseApiFormRequest`.

### Success methods

| Method | Default code | Default status |
|--------|-------------|----------------|
| `success($data, $code, $message, $status, $meta)` | `SUCCESS` | 200 |
| `created($data, $code, $message, $meta)` | `CREATED` | 201 |
| `updated($data, $code, $message, $meta)` | `UPDATED` | 200 |
| `deleted($data, $code, $message)` | `DELETED` | 200 |
| `noContent($code, $message)` | `SUCCESS` | 200 |
| `paginated($paginator, $resourceClass, $code, $message)` | `SUCCESS` | 200 |

### Error methods

| Method | Default code | Default status |
|--------|-------------|----------------|
| `error($code, $message, $status, $errors)` | `BAD_REQUEST` | 400 |
| `validationError($errors, $message, $code)` | `VALIDATION_FAILED` | 422 |
| `notFound($message, $code)` | `RESOURCE_NOT_FOUND` | 404 |
| `unauthorized($message, $code)` | `UNAUTHORIZED` | 401 |
| `forbidden($message, $code)` | `FORBIDDEN` | 403 |
| `unprocessable($message, $errors, $code)` | `UNPROCESSABLE_ENTITY` | 422 |
| `tooManyRequests($message, $code)` | `TOO_MANY_REQUESTS` | 429 |
| `businessError($code, $message, $errors)` | varies | from enum |

## Enums

`SuccessCodeEnum`: `SUCCESS`, `CREATED`, `UPDATED`, `DELETED` — each maps to HTTP status + translation key.

`ErrorCodeEnum`: `BAD_REQUEST`, `VALIDATION_FAILED`, `UNAUTHORIZED`, `FORBIDDEN`, `RESOURCE_NOT_FOUND`, `UNPROCESSABLE_ENTITY`, `TOO_MANY_REQUESTS`, `SERVER_ERROR` — maps to HTTP status + translation key.

Both enums have `getStatusCode(): int` and `getMessageKey(): string`.
