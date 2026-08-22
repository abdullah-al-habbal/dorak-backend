#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Expand/contract migration guard.
 *
 * Deployments run `artisan migrate` BEFORE the atomic swap, so between the
 * migration and the swap the PREVIOUS release serves traffic against the NEW
 * schema. That is only safe for backward-compatible ("expand") migrations.
 * If a later step fails, the trap restores the old code but the schema stays
 * migrated — application rollback is not database rollback.
 *
 * This guard fails the build on two things:
 *
 *   (a) destructive operations in the up() of a NEW migration
 *   (b) modification of a migration that has already shipped
 *
 * (b) matters because `migrate` never re-runs an applied migration. Editing a
 * shipped one makes fresh installs and existing databases diverge silently and
 * permanently — exactly what commit 8a07a89 did to the `preferred_universe`
 * column default.
 *
 * Deliberate contract releases opt out per-file:
 *
 *   // @contract-migration: drop legacy column, no code references it since v2.3
 *
 * Usage:
 *   php scripts/check_migrations.php [--base=<git-ref>] [--all]
 *
 *   --base=<ref>  compare against this ref (default: origin/dev)
 *   --all         scan every migration in the repo instead of a diff
 */
const EXIT_OK = 0;
const EXIT_VIOLATION = 1;
const EXIT_USAGE = 2;

const MIGRATION_GLOBS = [
    'modules/*/Database/Migrations/*.php',
    'database/migrations/*.php',
];

/** Destructive schema operations. Safe in down(); never in up() without opt-out. */
const DESTRUCTIVE = [
    'dropColumn' => 'drops a column',
    'dropForeign' => 'drops a foreign key',
    'dropIndex' => 'drops an index',
    'dropUnique' => 'drops a unique constraint',
    'dropPrimary' => 'drops a primary key',
    'dropConstrainedForeignId' => 'drops a constrained foreign id',
    'renameColumn' => 'renames a column',
    '->change()' => 'alters an existing column (type/nullability)',
    'Schema::rename' => 'renames a table',
    'Schema::drop(' => 'drops a table',
];

const OPT_OUT = '@contract-migration:';

/** Blueprint calls that are not real columns, or are inherently nullable. */
const NON_COLUMN_CALLS = [
    'softDeletes', 'timestamps', 'rememberToken', 'index', 'unique', 'primary',
    'foreign', 'references', 'on', 'onDelete', 'onUpdate', 'constrained',
    'nullOnDelete', 'cascadeOnDelete', 'restrictOnDelete', 'comment', 'after',
    'change', 'spatialIndex', 'fullText', 'rawIndex',
    // Removals and renames are reported by the DESTRUCTIVE scan. Without these
    // here, `dropColumn('phone')` also gets misreported as a NOT NULL addition.
    'dropColumn', 'dropForeign', 'dropIndex', 'dropUnique', 'dropPrimary',
    'dropConstrainedForeignId', 'dropSoftDeletes', 'dropTimestamps',
    'dropRememberToken', 'dropMorphs', 'renameColumn', 'renameIndex',
];

function fail(string $msg): void
{
    fwrite(STDERR, $msg.PHP_EOL);
}

function migrationFiles(): array
{
    $out = [];
    foreach (MIGRATION_GLOBS as $glob) {
        foreach (glob($glob) ?: [] as $f) {
            $out[] = $f;
        }
    }
    sort($out);

    return $out;
}

function isMigrationPath(string $path): bool
{
    return (bool) preg_match('#^(modules/[^/]+/Database/Migrations/|database/migrations/).+\.php$#', $path);
}

function git(string $args): array
{
    exec("git {$args} 2>/dev/null", $lines, $code);

    return [$code, $lines];
}

/** Extract the body of up(). Returns '' when it cannot be located. */
function upBody(string $src): string
{
    if (! preg_match('/function\s+up\s*\([^)]*\)\s*(?::\s*\w+\s*)?\{/', $src, $m, PREG_OFFSET_CAPTURE)) {
        return '';
    }
    $start = $m[0][1] + strlen($m[0][0]);
    $depth = 1;
    $len = strlen($src);
    for ($i = $start; $i < $len; $i++) {
        $c = $src[$i];
        if ($c === '{') {
            $depth++;
        } elseif ($c === '}') {
            $depth--;
            if ($depth === 0) {
                return substr($src, $start, $i - $start);
            }
        }
    }

    return substr($src, $start);
}

/** Columns added to an EXISTING table must be nullable or defaulted. */
function notNullAdditions(string $up): array
{
    $problems = [];
    // Only Schema::table(...) blocks alter existing tables.
    if (! preg_match_all('/Schema::table\s*\(\s*[\'"]([^\'"]+)[\'"].*?\n(\s*)\}\);/s', $up, $blocks, PREG_SET_ORDER)) {
        return $problems;
    }
    foreach ($blocks as $b) {
        $table = $b[1];
        foreach (preg_split('/;\s*\n/', $b[0]) as $stmt) {
            if (! str_contains($stmt, '$table->')) {
                continue;
            }
            if (! preg_match('/\$table->(\w+)\s*\(\s*[\'"]?([^\'",)]*)/', $stmt, $m)) {
                continue;
            }
            [$_, $call, $col] = $m;
            if (in_array($call, NON_COLUMN_CALLS, true)) {
                continue;
            }
            // `->change()` modifies an existing column rather than adding one;
            // the DESTRUCTIVE scan already reports it.
            if (str_contains($stmt, '->change()')) {
                continue;
            }
            $safe = str_contains($stmt, '->nullable()')
                || str_contains($stmt, '->default(')
                || str_contains($stmt, '->useCurrent()')
                || str_contains($stmt, '->autoIncrement()');
            if (! $safe) {
                $problems[] = sprintf('adds NOT NULL column without a default: %s.%s (%s)', $table, $col, $call);
            }
        }
    }

    return $problems;
}

function scan(string $file): array
{
    $src = @file_get_contents($file);
    if ($src === false) {
        return ['optOut' => null, 'problems' => ["unreadable: {$file}"]];
    }

    $optOut = null;
    if (preg_match('/'.preg_quote(OPT_OUT, '/').'\s*(.+)/', $src, $m)) {
        $optOut = trim($m[1]);
    }

    $up = upBody($src);
    if ($up === '') {
        return ['optOut' => $optOut, 'problems' => []];
    }

    $problems = [];
    foreach (DESTRUCTIVE as $needle => $desc) {
        if (str_contains($up, $needle)) {
            $problems[] = "{$desc} — `{$needle}` in up()";
        }
    }
    foreach (notNullAdditions($up) as $p) {
        $problems[] = $p;
    }

    return ['optOut' => $optOut, 'problems' => $problems];
}

// ── args ────────────────────────────────────────────────────────────────────
$base = 'origin/dev';
$all = false;
foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--base=')) {
        $base = substr($arg, 7);
    } elseif ($arg === '--all') {
        $all = true;
    } elseif ($arg === '-h' || $arg === '--help') {
        fwrite(STDOUT, "Usage: php scripts/check_migrations.php [--base=<ref>] [--all]\n");
        exit(EXIT_OK);
    } else {
        fail("Unknown argument: {$arg}");
        exit(EXIT_USAGE);
    }
}

// ── collect files ───────────────────────────────────────────────────────────
$added = [];
$modified = [];

if ($all) {
    $added = migrationFiles();
    echo '🔎 Scanning all '.count($added)." migrations (--all)\n";
} else {
    [$code] = git("rev-parse --verify --quiet {$base}");
    if ($code !== 0) {
        echo "⚠️  Base ref '{$base}' not found; falling back to --all\n";
        $added = migrationFiles();
        $all = true;
    } else {
        [, $lines] = git("diff --name-status --diff-filter=AM {$base}...HEAD");
        foreach ($lines as $line) {
            $parts = preg_split('/\t/', $line);
            if (count($parts) < 2) {
                continue;
            }
            [$status, $path] = $parts;
            if (! isMigrationPath($path)) {
                continue;
            }
            if ($status === 'A') {
                $added[] = $path;
            } elseif ($status === 'M') {
                $modified[] = $path;
            }
        }
        echo "🔎 Comparing against {$base}: ".count($added).' added, '.count($modified)." modified migration(s)\n";
    }
}

$violations = 0;
$waived = 0;

// ── (b) shipped migrations must never be edited ─────────────────────────────
foreach ($modified as $file) {
    $r = scan($file);
    if ($r['optOut'] !== null) {
        echo "  ⚠️  WAIVED  {$file}\n";
        echo "             a shipped migration was edited — waiver: {$r['optOut']}\n";
        $waived++;

        continue;
    }
    fail("  ❌ {$file}");
    fail('     A migration that has already shipped was modified.');
    fail('     `migrate` never re-runs an applied migration, so this changes fresh');
    fail('     installs only — existing databases keep the old schema, silently and');
    fail('     permanently. Add a NEW migration instead.');
    fail('     Deliberate? add:  // '.OPT_OUT.' <reason>');
    $violations++;
}

// ── (a) destructive operations in new migrations ────────────────────────────
foreach ($added as $file) {
    $r = scan($file);
    if ($r['problems'] === []) {
        continue;
    }
    if ($r['optOut'] !== null) {
        echo "  ⚠️  WAIVED  {$file}\n";
        foreach ($r['problems'] as $p) {
            echo "             - {$p}\n";
        }
        echo "             waiver: {$r['optOut']}\n";
        $waived++;

        continue;
    }
    fail("  ❌ {$file}");
    foreach ($r['problems'] as $p) {
        fail("     - {$p}");
    }
    fail('     Migrations run BEFORE the atomic swap, so the previous release serves');
    fail('     traffic against this schema. Contract operations must ship in a later');
    fail('     release, after no running code references what is being removed.');
    fail('     Deliberate? add:  // '.OPT_OUT.' <reason>');
    $violations++;
}

echo "\n";
if ($violations > 0) {
    fail("❌ Migration guard: {$violations} violation(s)".($waived ? ", {$waived} waived" : '').'.');
    fail('   Policy: docs/16_deployment-operations.md');
    exit(EXIT_VIOLATION);
}

$scanned = count($added) + count($modified);
echo '✅ Migration guard passed'.($scanned ? " ({$scanned} migration(s) checked".($waived ? ", {$waived} waived" : '').')' : ' (no migrations changed)').".\n";
exit(EXIT_OK);
