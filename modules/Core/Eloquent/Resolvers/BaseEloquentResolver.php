<?php
declare(strict_types=1);

namespace Modules\Core\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentResolver
{
    abstract public function resolve(object $payload): Model|Collection|array|null;
}
