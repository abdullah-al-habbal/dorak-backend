<?php
declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

final class LoggerService
{
    private LoggerInterface $logger;

    public function __construct(?LogManager $logManager = null)
    {
        $this->logger = ($logManager ?? Log::channel('daily'));
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $this->enrichContext($context));
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $this->enrichContext($context));
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $this->enrichContext($context));
    }

    private function enrichContext(array $context): array
    {
        $request = request();

        if ($request?->attributes->has('request_uuid')) {
            $context['request_uuid'] = $request->attributes->get('request_uuid');
        }

        return $context;
    }
}
