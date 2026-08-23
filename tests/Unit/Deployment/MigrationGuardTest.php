<?php

declare(strict_types=1);

use Symfony\Component\Process\Process;

/**
 * Tests for scripts/check_migrations.php — the expand/contract guard.
 *
 * The guard is CI-blocking logic that gates every migration we ship. If it
 * silently stops detecting violations the policy evaporates without anyone
 * noticing, so the guard itself needs coverage.
 *
 * Deliberately NOT tested here: whether down() actually reverses a migration.
 * down() is not the production recovery path — after a deployment rollback the
 * restored release no longer contains the new migration file, and Laravel's
 * Migrator skips it ("Migration not found", Migrator.php:333) while rolling
 * back the rest of the batch and exiting 0. Testing down() would imply it is a
 * usable recovery mechanism. It is not. See docs/16_deployment-operations.md.
 */
function guardScript(): string
{
    return base_path('scripts/check_migrations.php');
}

/** Build a throwaway tree that looks like the repo, and run the guard in it. */
function runGuard(array $migrations, array $args = ['--all'], ?callable $setup = null): array
{
    $root = sys_get_temp_dir().'/mig-guard-'.bin2hex(random_bytes(6));
    $migDir = $root.'/modules/Client/Database/Migrations';
    mkdir($migDir, 0o777, true);
    mkdir($root.'/scripts', 0o777, true);
    copy(guardScript(), $root.'/scripts/check_migrations.php');

    foreach ($migrations as $name => $up) {
        file_put_contents($migDir.'/'.$name, migrationSource($up));
    }

    if ($setup !== null) {
        $setup($root, $migDir);
    }

    $p = new Process(array_merge(['php', 'scripts/check_migrations.php'], $args), $root);
    $p->run();

    $out = $p->getOutput().$p->getErrorOutput();
    exec('rm -rf '.escapeshellarg($root));

    return ['exit' => $p->getExitCode(), 'output' => $out];
}

function migrationSource(string $up): string
{
    return <<<PHP
    <?php

    declare(strict_types=1);

    use Illuminate\\Database\\Migrations\\Migration;
    use Illuminate\\Database\\Schema\\Blueprint;
    use Illuminate\\Support\\Facades\\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
    {$up}
        }

        public function down(): void
        {
            Schema::dropIfExists('whatever');
        }
    };
    PHP;
}

function gitInit(string $root): void
{
    foreach ([
        ['git', 'init', '-q'],
        ['git', 'config', 'user.email', 'guard@test.local'],
        ['git', 'config', 'user.name', 'guard'],
        ['git', 'add', '-A'],
        ['git', 'commit', '-qm', 'base'],
    ] as $cmd) {
        (new Process($cmd, $root))->mustRun();
    }
}

// ── expand operations are allowed ───────────────────────────────────────────

it('allows a new table', function () {
    $r = runGuard(['2030_01_01_000001_create_thing.php' => <<<'UP'
            Schema::create('thing', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('required_column');
            });
    UP]);

    expect($r['exit'])->toBe(0)
        ->and($r['output'])->toContain('Migration guard passed');
});

it('allows nullable and defaulted columns on an existing table', function () {
    $r = runGuard(['2030_01_01_000002_expand.php' => <<<'UP'
            Schema::table('clients', function (Blueprint $table) {
                $table->string('nickname')->nullable();
                $table->string('tier')->default('basic');
                $table->softDeletes();
                $table->foreignId('other_id')->nullable()->constrained('clients')->nullOnDelete();
            });
    UP]);

    expect($r['exit'])->toBe(0);
});

// ── contract operations are rejected ────────────────────────────────────────

it('rejects dropColumn in up()', function () {
    $r = runGuard(['2030_01_01_000003_drop.php' => <<<'UP'
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
    UP]);

    expect($r['exit'])->toBe(1)
        ->and($r['output'])->toContain('drops a column');
});

it('rejects a column type change in up()', function () {
    $r = runGuard(['2030_01_01_000004_change.php' => <<<'UP'
            Schema::table('clients', function (Blueprint $table) {
                $table->string('email', 100)->change();
            });
    UP]);

    expect($r['exit'])->toBe(1)
        ->and($r['output'])->toContain('alters an existing column');
});

it('rejects a NOT NULL column with no default on an existing table', function () {
    $r = runGuard(['2030_01_01_000005_notnull.php' => <<<'UP'
            Schema::table('clients', function (Blueprint $table) {
                $table->string('nickname');
            });
    UP]);

    expect($r['exit'])->toBe(1)
        ->and($r['output'])->toContain('NOT NULL column without a default');
});

it('rejects renameColumn in up()', function () {
    $r = runGuard(['2030_01_01_000006_rename.php' => <<<'UP'
            Schema::table('clients', function (Blueprint $table) {
                $table->renameColumn('phone', 'telephone');
            });
    UP]);

    expect($r['exit'])->toBe(1)
        ->and($r['output'])->toContain('renames a column');
});

// ── the waiver ──────────────────────────────────────────────────────────────

it('allows a destructive migration that carries an explicit waiver', function () {
    $r = runGuard(['2030_01_01_000007_waived.php' => <<<'UP'
            // @contract-migration: legacy column, unreferenced since v2.3
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
    UP]);

    expect($r['exit'])->toBe(0)
        ->and($r['output'])->toContain('WAIVED')
        ->and($r['output'])->toContain('unreferenced since v2.3');
});

// ── editing a shipped migration (the 8a07a89 pattern) ───────────────────────

it('detects an edited shipped migration against the parent commit', function () {
    $root = sys_get_temp_dir().'/mig-guard-git-'.bin2hex(random_bytes(6));
    $migDir = $root.'/modules/Client/Database/Migrations';
    mkdir($migDir, 0o777, true);
    mkdir($root.'/scripts', 0o777, true);
    copy(guardScript(), $root.'/scripts/check_migrations.php');

    $file = $migDir.'/2030_01_01_000009_shipped.php';
    file_put_contents($file, migrationSource(<<<'UP'
            Schema::create('shipped', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('universe')->default('neutral');
            });
    UP));

    gitInit($root);
    $base = trim((new Process(['git', 'rev-parse', 'HEAD'], $root))->mustRun()->getOutput());

    file_put_contents($file, str_replace("'neutral'", "'men'", file_get_contents($file)));
    (new Process(['git', 'add', '-A'], $root))->mustRun();
    (new Process(['git', 'commit', '-qm', 'edit shipped migration'], $root))->mustRun();

    $p = new Process(['php', 'scripts/check_migrations.php', '--base='.$base], $root);
    $p->run();
    $out = $p->getOutput().$p->getErrorOutput();
    exec('rm -rf '.escapeshellarg($root));

    expect($p->getExitCode())->toBe(1)
        ->and($out)->toContain('already shipped was modified');
});

// ── regression canary against the real repository ───────────────────────────

it('passes against every migration currently in the repository', function () {
    $p = new Process(['php', 'scripts/check_migrations.php', '--all'], base_path());
    $p->run();

    expect($p->getExitCode())->toBe(0, $p->getOutput().$p->getErrorOutput());
});
