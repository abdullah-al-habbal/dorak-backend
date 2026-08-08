<?php

// modules/Language/Models/LanguageModel.php
declare(strict_types=1);

namespace Modules\Language\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'direction', 'is_default'])]
#[Table('languages')]
class LanguageModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_default' => 'boolean',
        ];
    }
}
