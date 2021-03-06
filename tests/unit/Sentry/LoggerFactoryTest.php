<?php

declare(strict_types=1);

namespace Tests\Unit\Sentry;

use Efficio\Logger\Sentry\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/** @covers \Efficio\Logger\Sentry\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory('https://6eaf4327739d4645b96210a5b1bd6fcb@o1092750.ingest.sentry.io/6111433');
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateSentryInstance(): void
    {
        // Given factory
        $dsn = 'https://6eaf4327739d4645b96210a5b1bd6fcb@o1092750.ingest.sentry.io/6111433';
        $sut = new LoggerFactory($dsn);

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
    }
}
