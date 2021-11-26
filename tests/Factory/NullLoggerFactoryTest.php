<?php

declare(strict_types=1);

namespace Tests\Factory;

use Efficio\Logger\Factory\NullLoggerFactory;
use Efficio\Logger\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class NullLoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new NullLoggerFactory();
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateNullLoggerInstance(): void
    {
        // Given factory
        $sut = new NullLoggerFactory();

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(NullLogger::class, $actual);
    }
}
