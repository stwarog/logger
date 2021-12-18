<?php

declare(strict_types=1);

namespace Efficio\Logger;

use Efficio\Logger\Normalizer\Factory;
use Efficio\Logger\Normalizer\Normalizer;
use Psr\Log\LoggerInterface;

class Decorator implements LoggerInterface
{
    protected LoggerInterface $logger;
    protected Normalizer $normalizer;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->normalizer = Factory::create();
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $this->parseContext($context));
    }

    protected function parseContext(array $context): array
    {
        return $this->normalizer->normalize($context);
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
