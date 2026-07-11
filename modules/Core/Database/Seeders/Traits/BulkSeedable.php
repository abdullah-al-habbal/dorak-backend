<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait BulkSeedable
{
    protected function bulkInsertOrIgnore(string $table, array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        DB::table($table)->insertOrIgnore(
            array_map(fn (array $row): array => $this->ensureId($row), $rows),
        );
    }

    protected function bulkUpsert(string $table, array $rows, array $uniqueBy, array $updateColumns): void
    {
        if (empty($rows)) {
            return;
        }

        DB::table($table)->upsert(
            array_map(fn (array $row): array => $this->ensureId($row), $rows),
            $uniqueBy,
            $updateColumns,
        );
    }

    private function ensureId(array $row): array
    {
        if (! isset($row['id'])) {
            $row['id'] = (string) Str::uuid();
        }

        return array_map(
            fn (mixed $value): mixed => is_array($value) ? json_encode($value) : $value,
            $row,
        );
    }
}
