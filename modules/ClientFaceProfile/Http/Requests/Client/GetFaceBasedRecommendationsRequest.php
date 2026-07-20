<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Requests\Client;

use Modules\ClientFaceProfile\CQRS\Query\GetFaceBasedRecommendationsQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class GetFaceBasedRecommendationsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(): GetFaceBasedRecommendationsQuery
    {
        return new GetFaceBasedRecommendationsQuery(
            clientId: (string) $this->user()->id,
        );
    }
}
