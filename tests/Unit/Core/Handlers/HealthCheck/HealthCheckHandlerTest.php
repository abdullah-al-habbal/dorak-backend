<?php

// tests/Unit/Core/Handlers/HealthCheck/HealthCheckHandlerTest.php
declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Modules\Core\Eloquent\Resolvers\Shared\HealthCheckResolver;
use Modules\Core\Handlers\Shared\HealthCheckHandler;

beforeEach(function () {
    $this->resolver = Mockery::mock(HealthCheckResolver::class);
    $this->handler = new HealthCheckHandler($this->resolver);
});

it('returns ok when all checks pass', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'ok', 'driver' => 'mysql', 'latency_ms' => 1.0,
    ]);

    Cache::shouldReceive('put')->once()->andReturn(true);
    Cache::shouldReceive('get')->once()->andReturn(true);
    Cache::shouldReceive('forget')->once()->andReturn(true);

    $result = $this->handler->handle();

    expect($result['status'])->toBe('ok');
    expect($result['checks']['database']['status'])->toBe('ok');
    expect($result['checks']['cache']['status'])->toBe('ok');
    expect($result['checks']['app']['status'])->toBe('ok');
    expect($result['checks']['php']['status'])->toBe('ok');
});

it('returns down when database fails', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'fail', 'driver' => 'mysql',
    ]);

    $result = $this->handler->handle();

    expect($result['status'])->toBe('down');
});

it('returns degraded when cache fails', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'ok', 'driver' => 'mysql', 'latency_ms' => 1.0,
    ]);

    Cache::shouldReceive('put')->once()->andThrow(new RuntimeException('cache down'));

    $result = $this->handler->handle();

    expect($result['status'])->toBe('degraded');
    expect($result['checks']['cache']['status'])->toBe('fail');
});

it('returns degraded on php extension warn', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'ok', 'driver' => 'mysql', 'latency_ms' => 1.0,
    ]);

    Cache::shouldReceive('put')->once()->andReturn(true);
    Cache::shouldReceive('get')->once()->andReturn(true);
    Cache::shouldReceive('forget')->once()->andReturn(true);

    // Override config so queue driver is something we can't test
    config()->set('queue.default', 'sync');

    $result = $this->handler->handle();

    expect($result['status'])->toBe('ok');
});

it('includes all required check keys', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'ok', 'driver' => 'mysql', 'latency_ms' => 1.0,
    ]);

    Cache::shouldReceive('put')->once()->andReturn(true);
    Cache::shouldReceive('get')->once()->andReturn(true);
    Cache::shouldReceive('forget')->once()->andReturn(true);

    $result = $this->handler->handle();

    expect($result)->toHaveKeys(['status', 'timestamp', 'checks']);
    expect($result['checks'])->toHaveKeys(['app', 'database', 'cache', 'php', 'queue']);
});

it('returns timestamp in ISO 8601 format', function () {
    $this->resolver->shouldReceive('checkDatabase')->once()->andReturn([
        'status' => 'ok', 'driver' => 'mysql', 'latency_ms' => 1.0,
    ]);

    Cache::shouldReceive('put')->once()->andReturn(true);
    Cache::shouldReceive('get')->once()->andReturn(true);
    Cache::shouldReceive('forget')->once()->andReturn(true);

    $result = $this->handler->handle();

    expect($result['timestamp'])->toMatch('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/');
});
