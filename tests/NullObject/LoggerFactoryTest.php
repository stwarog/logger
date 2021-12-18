<?php

declare(strict_types=1);

namespace Tests\NullObject;

use Efficio\Logger\NullObject\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/** @covers \Efficio\Logger\NullObject\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory();
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateNullLoggerInstance(): void
    {
        // Given factory
        $sut = new LoggerFactory();

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(NullLogger::class, $actual);
    }
}
