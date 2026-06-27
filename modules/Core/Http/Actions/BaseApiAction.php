<?php
declare(strict_types=1);

namespace Modules\Core\Http\Actions;

use Modules\Core\Helpers\ApiResponseTrait;

abstract class BaseApiAction
{
    use ApiResponseTrait;

    abstract public function __invoke(): mixed;
}
