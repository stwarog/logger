<?php

declare(strict_types=1);

namespace Tests\Factory\Monolog;

use Efficio\Logger\Factory\Monolog\Config;
use Efficio\Logger\Factory\Monolog\LoggerFactory;
use Monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $config = $this->createStub(Config::class);
        $config->method('getLevel')->willReturn('DEBUG');
        $sut = new LoggerFactory($config);
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function testCreateMonologInstance(): void
    {
        // Given factory
        $config = $this->createStub(Config::class);
        $config->method('getLevel')->willReturn('DEBUG');
        $sut = new LoggerFactory($config);

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(MonologLogger::class, $actual);
    }
}
