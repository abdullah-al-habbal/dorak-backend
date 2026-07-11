<?php

declare(strict_types=1);

namespace Modules\Core\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Core\Helpers\ApiResponseTrait;

abstract class BaseApiFormRequest extends FormRequest
{
    use ApiResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): never
    {
        $response = $this->unprocessable(
            errors: $validator->errors()
        );

        throw new HttpResponseException($response);
    }

    protected function failedAuthorization(): never
    {
        $response = $this->forbidden();

        throw new HttpResponseException($response);
    }
}
