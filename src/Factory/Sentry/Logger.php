<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Sentry;

use Efficio\Logger\Normalizer\Factory;
use Efficio\Logger\Normalizer\Normalizer;
use Psr\Log\LoggerInterface;
use Throwable;

final class Logger implements LoggerInterface
{
    private LoggerInterface $logger;
    private Normalizer $normalizer;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->normalizer = Factory::create();
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $this->parseContext($context));
    }

    private function parseContext(array $context): array
    {
        // If we have only one argument passed as exception e.g. [new Exception()]
        // then we wish to have it captured as native SDK capture exception function does.
        // To achieve this by File handlers, we have to use syntax ['exception' => new Exception()].

        $hasOneArgumentAsException = count(array_filter($context, fn($i) => $i instanceof Throwable)) === 1;

        if ($hasOneArgumentAsException) {
            return ['exception' => $context[0]];
        }

        // There is also no easy way to add a context, so we have to map it here.

        return ['extra' => $this->normalizer->normalize($context)];
    }

    public function emergency($message, array $context = []): void
    {
        $this->logger->emergency($message, $this->parseContext($context));
    }

    public function alert($message, array $context = []): void
    {
        $this->logger->alert($message, $this->parseContext($context));
    }

    public function critical($message, array $context = []): void
    {
        $this->logger->critical($message, $this->parseContext($context));
    }

    public function error($message, array $context = []): void
    {
        $this->logger->error($message, $this->parseContext($context));
    }

    public function warning($message, array $context = []): void
    {
        $this->logger->warning($message, $this->parseContext($context));
    }

    public function notice($message, array $context = []): void
    {
        $this->logger->notice($message, $this->parseContext($context));
    }

    public function info($message, array $context = []): void
    {
        $this->logger->info($message, $this->parseContext($context));
    }

    public function debug($message, array $context = []): void
    {
        $this->logger->debug($message, $this->parseContext($context));
    }
}
