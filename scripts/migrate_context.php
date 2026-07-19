#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Context-Driven Namespacing migration tool.
 *
 * Usage:
 *   php scripts/migrate_context.php <old-file> <new-file> <old-namespace> <new-namespace>
 *
 * Example:
 *   php scripts/migrate_context.php \
 *     modules/Marketing/Handlers/GetFloorPlanDemoHandler.php \
 *     modules/Marketing/Handlers/Shared/GetFloorPlanDemoHandler.php \
 *     "Modules\Marketing\Handlers" \
 *     "Modules\Marketing\Handlers\Shared"
 *
 * What it does:
 *   1. Creates target directory and moves the file
 *   2. Updates the namespace inside the moved file
 *   3. Updates all `use` import statements in modules/, bootstrap/, config/
 *
 * LIMITATION: Does NOT update use-statements in tests/ (out of scope).
 * After all migrations, grep tests/ for old namespace references and fix manually:
 *   grep -rn "use Modules\\" tests/ | grep -E "(Actions|Handlers|Resolvers|CQRS|Requests|Resources)\\\"
 */

$oldFile = $argv[1] ?? null;
$newFile = $argv[2] ?? null;
$oldNamespace = $argv[3] ?? null;
$newNamespace = $argv[4] ?? null;

if (!$oldFile || !$newFile || !$oldNamespace || !$newNamespace) {
    fwrite(STDERR, "Usage: php scripts/migrate_context.php <old-file> <new-file> <old-ns> <new-ns>\n");
    exit(1);
}

$className = pathinfo($oldFile, PATHINFO_FILENAME);

// Step 1: Create directory and move file
$newDir = dirname($newFile);
if (!is_dir($newDir)) {
    mkdir($newDir, 0777, true);
    echo "  Created directory: $newDir\n";
}

if (!rename($oldFile, $newFile)) {
    fwrite(STDERR, "  ERROR: Failed to move $oldFile -> $newFile\n");
    exit(1);
}
echo "  Moved: $oldFile -> $newFile\n";

// Step 2: Update namespace inside moved file
$content = file_get_contents($newFile);
$oldNsDecl = "namespace $oldNamespace;";
$newNsDecl = "namespace $newNamespace;";

if (!str_contains($content, $oldNsDecl)) {
    fwrite(STDERR, "  WARNING: Namespace '$oldNamespace' not found in $newFile\n");
    // Try to find what namespace is actually there
    if (preg_match('/^namespace\s+(.+?);/m', $content, $matches)) {
        fwrite(STDERR, "  Found actual namespace: {$matches[1]}\n");
    }
}

$content = str_replace($oldNsDecl, $newNsDecl, $content);
file_put_contents($newFile, $content);
echo "  Updated namespace: $oldNamespace -> $newNamespace\n";

// Step 3: Update use statements in all PHP files under modules/
$oldUse = "use $oldNamespace\\$className;";
$newUse = "use $newNamespace\\$className;";

$filesUpdated = 0;
$rootDir = realpath(__DIR__ . '/..');
$searchDirs = [$rootDir . '/modules'];

// Also search in bootstrap/ and config/ for any class references
$searchDirs[] = $rootDir . '/bootstrap';
$searchDirs[] = $rootDir . '/config';

foreach ($searchDirs as $searchDir) {
    if (!is_dir($searchDir)) continue;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($searchDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') continue;
        if (str_contains($file->getPathname(), '/vendor/')) continue;

        $fileContent = @file_get_contents($file->getPathname());
        if ($fileContent === false) continue;

        if (str_contains($fileContent, $oldUse)) {
            $fileContent = str_replace($oldUse, $newUse, $fileContent);
            file_put_contents($file->getPathname(), $fileContent);
            $filesUpdated++;
            echo "  Updated import in: {$file->getPathname()}\n";
        }
    }
}

echo "  Updated $filesUpdated files with new import statements.\n";
echo "✓ Migrated: $className\n";
