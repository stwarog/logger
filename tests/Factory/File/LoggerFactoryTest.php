<?php

declare(strict_types=1);

namespace Tests\Factory\File;

use Efficio\Logger\Factory\File\ConfigInterface;
use Efficio\Logger\Factory\File\LoggerFactory;
use Monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $config = $this->createStub(ConfigInterface::class);
        $config->method('getLevel')->willReturn('DEBUG');
        $sut = new LoggerFactory($config);
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateMonologInstance(): void
    {
        // Given factory
        $config = $this->createStub(ConfigInterface::class);
        $config->method('getLevel')->willReturn('DEBUG');
        $sut = new LoggerFactory($config);

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(MonologLogger::class, $actual);
    }
}
