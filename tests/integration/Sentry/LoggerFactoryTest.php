<?php

declare(strict_types=1);

namespace Tests\Integration\Sentry;

use Efficio\Logger\Sentry\LoggerFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/** @covers \Efficio\Logger\Sentry\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    public function testLogAdditionalData(): void
    {
        $factory = new LoggerFactory(
            'https://6eaf4327739d4645b96210a5b1bd6fcb@o1092750.ingest.sentry.io/6111433',
            ['level' => LogLevel::DEBUG]
        );
        $logger = $factory->create();

        $logger->emergency('emergency', ['exception' => new Exception('some message')]);
    }
}
