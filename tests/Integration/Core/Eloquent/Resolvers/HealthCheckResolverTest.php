<?php

// tests/Integration/Core/Eloquent/Resolvers/HealthCheckResolverTest.php
declare(strict_types=1);

use Modules\Core\Eloquent\Resolvers\Shared\HealthCheckResolver;

it('returns ok with driver and latency when database is reachable', function () {
    $resolver = new HealthCheckResolver;
    $result = $resolver->checkDatabase();

    expect($result['status'])->toBe('ok');
    expect($result['driver'])->toBe(
        config('database.default'),
    );
    expect($result['latency_ms'])->toBeFloat();
    expect($result['latency_ms'])->toBeGreaterThan(0);
});
