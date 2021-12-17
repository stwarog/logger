<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Sentry;

use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Throwable;

final class Logger implements LoggerInterface
{
    private MonologLogger $monolog;

    public function __construct(MonologLogger $monolog)
    {
        $this->monolog = $monolog;
    }

    public function log($level, $message, array $context = []): void
    {
        $this->monolog->log($level, $message, $this->parseContext($context));
    }

    private function parseContext(array $context): array
    {
        // If we have only one argument passed as exception e.g. [new Exception()]
        // then we wish to have it captured as native SDK capture exception does.
        // To achieve this by Monolog handlers, we have to use syntax ['exception' => new Exception()].

        $hasOneArgumentAsException = count(array_filter($context, fn($i) => $i instanceof Throwable)) === 1;
        if ($hasOneArgumentAsException) {
            return ['exception' => $context[0]];
        }

        // There is also no easy way to add a context, so we have to map it here.

        return ['extra' => $context];
    }

    public function emergency($message, array $context = []): void
    {
        $this->monolog->emergency($message, $this->parseContext($context));
    }

    public function alert($message, array $context = []): void
    {
        $this->monolog->alert($message, $this->parseContext($context));
    }

    public function critical($message, array $context = []): void
    {
        $this->monolog->critical($message, $this->parseContext($context));
    }

    public function error($message, array $context = []): void
    {
        $this->monolog->error($message, $this->parseContext($context));
    }

    public function warning($message, array $context = []): void
    {
        $this->monolog->warning($message, $this->parseContext($context));
    }

    public function notice($message, array $context = []): void
    {
        $this->monolog->notice($message, $this->parseContext($context));
    }

    public function info($message, array $context = []): void
    {
        $this->monolog->info($message, $this->parseContext($context));
    }

    public function debug($message, array $context = []): void
    {
        $this->monolog->debug($message, $this->parseContext($context));
    }
}
