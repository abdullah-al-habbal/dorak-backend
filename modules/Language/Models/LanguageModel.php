<?php
// modules/Language/Models/LanguageModel.php
declare(strict_types=1);

namespace Modules\Language\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'languages';

    protected $fillable = [
        'code', 'name', 'direction', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'name'       => 'array',
            'is_default' => 'boolean',
        ];
    }
}
