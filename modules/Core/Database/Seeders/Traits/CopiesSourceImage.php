<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait CopiesSourceImage
{
    protected function copySourceImage(string $sourcePath, string $basePath, ?string $identifier = null, string $fileSuffix = '', string $outputFormat = ''): string
    {
        if (! File::exists($sourcePath)) {
            throw new \RuntimeException("Required source image not found at: {$sourcePath}");
        }

        $now = now();
        $year = $now->format('Y');
        $month = $now->format('m');
        $day = $now->format('d');
        $timestamp = $now->timestamp;
        $ext = $outputFormat !== '' ? $outputFormat : pathinfo($sourcePath, PATHINFO_EXTENSION);

        $filename = $fileSuffix ? "{$timestamp}-{$fileSuffix}" : (string) $timestamp;
        $folder = $identifier ? "{$basePath}/{$identifier}" : $basePath;
        $relativePath = "{$folder}/{$year}/{$month}/{$day}/{$filename}.{$ext}";

        Storage::disk('public')->put($relativePath, $this->readImageBytes($sourcePath, $outputFormat));

        return $relativePath;
    }

    private function readImageBytes(string $sourcePath, string $outputFormat): string
    {
        if ($outputFormat === '') {
            return File::get($sourcePath);
        }

        $image = Image::fromPath($sourcePath);

        return match ($outputFormat) {
            'webp' => $image->toWebp()->toBytes(),
            'jpg' => $image->toJpg()->toBytes(),
            default => throw new \RuntimeException("Unsupported image output format: {$outputFormat}"),
        };
    }
}
