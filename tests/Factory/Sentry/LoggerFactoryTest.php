<?php

declare(strict_types=1);

namespace Tests\Factory\Sentry;

use Efficio\Logger\Factory\Sentry\Config;
use Efficio\Logger\Factory\Sentry\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

//use Facile\Sentry\Log\Logger as SentryLogger;

final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory('https://public@sentry.example.com/1');
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateSentryInstance(): void
    {
        // Given factory
        $sut = new LoggerFactory('https://public@sentry.example.com/1');

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
//        $this->assertInstanceOf(SentryLogger::class, $actual);
        $this->assertInstanceOf(NullLogger::class, $actual);
    }
}
