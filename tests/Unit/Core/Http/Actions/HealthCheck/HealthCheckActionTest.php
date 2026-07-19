<?php

// tests/Unit/Core/Http/Actions/HealthCheck/HealthCheckActionTest.php
declare(strict_types=1);

use Modules\Core\Handlers\Shared\HealthCheckHandler;
use Modules\Core\Http\Actions\Shared\HealthCheckAction;

beforeEach(function () {
    $this->handler = Mockery::mock(HealthCheckHandler::class);
    $this->action = new HealthCheckAction($this->handler);
});

it('returns 200 when status is ok', function () {
    $this->handler->shouldReceive('handle')->once()->andReturn([
        'status' => 'ok', 'timestamp' => now()->toIso8601String(), 'checks' => [],
    ]);

    $response = ($this->action)();

    expect($response->status())->toBe(200);
});

it('returns 200 when status is degraded', function () {
    $this->handler->shouldReceive('handle')->once()->andReturn([
        'status' => 'degraded', 'timestamp' => now()->toIso8601String(), 'checks' => [],
    ]);

    $response = ($this->action)();

    expect($response->status())->toBe(200);
});

it('returns 503 when status is down', function () {
    $this->handler->shouldReceive('handle')->once()->andReturn([
        'status' => 'down', 'timestamp' => now()->toIso8601String(), 'checks' => [],
    ]);

    $response = ($this->action)();

    expect($response->status())->toBe(503);
});

it('wraps response in api success envelope', function () {
    $this->handler->shouldReceive('handle')->once()->andReturn([
        'status' => 'ok', 'timestamp' => now()->toIso8601String(), 'checks' => [],
    ]);

    $response = ($this->action)();
    $body = $response->getData(true);

    expect($body)->toHaveKeys(['success', 'statusCode', 'code', 'message', 'timestamp', 'data']);
    expect($body['success'])->toBeTrue();
    expect($body['data']['status'])->toBe('ok');
});
