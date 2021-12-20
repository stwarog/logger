<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Efficio\Logger\Decorator;
use Efficio\Logger\File\Config;
use Efficio\Logger\File\ConfigInterface;
use Efficio\Logger\File\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/** @covers \Efficio\Logger\File\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    /** @dataProvider provideConstructor */
    public function testConstructor($config): void
    {
        $sut = new LoggerFactory($config);
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    public function provideConstructor(): array
    {
        return [
            'by array' => [['path' => '/']],
            'by config class' => [new Config('path')],
        ];
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
        $this->assertInstanceOf(Decorator::class, $actual);
    }
}
