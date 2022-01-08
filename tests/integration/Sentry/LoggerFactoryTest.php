<?php

declare(strict_types=1);

namespace Tests\Integration\Sentry;

use DateTime;
use Efficio\Logger\Sentry\LoggerFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/** @covers \Efficio\Logger\Sentry\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped(
            'Skipped playing ground integration test for Sentry, add Your dsn url and checkout the output manually.'
        );
    }

    public function testLogException(): void
    {
        $factory = new LoggerFactory(
            'https://6eaf4327739d4645b96210a5b1bd6fcb@o1092750.ingest.sentry.io/ur-dsn',
            ['level' => LogLevel::DEBUG]
        );
        $logger = $factory->create();

        $logger->emergency('emergency', ['exception' => new Exception('some message')]);

        // output should contain pretty exception with full track
    }

    public function testLogAdditionalData(): void
    {
        $factory = new LoggerFactory(
            'https://6eaf4327739d4645b96210a5b1bd6fcb@o1092750.ingest.sentry.io/ur-dsn',
            ['level' => LogLevel::DEBUG]
        );
        $logger = $factory->create();

        $logger->info('emergency', [
            'i am nested' => [
                'whatever' => 123,
                'has-date' => new DateTime()
            ]
        ]);

        // output should contain additional data with key named "i am nested" with pretty array
        // representation of provided context
    }
}
