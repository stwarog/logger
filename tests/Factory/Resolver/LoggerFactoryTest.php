<?php

declare(strict_types=1);

namespace Tests\Factory\Resolver;

use Efficio\Logger\Factory\Resolver\LoggerFactory;
use Efficio\Logger\Factory\Sentry\LoggerFactory as SentryFactory;
use Generator;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory(
            'env',
            $this->createStub(LoggerInterface::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(LoggerInterface::class),
        );
        $this->assertInstanceOf(LoggerFactory::class, $sut);
    }

    /** @dataProvider providerForCreateResolverInstance */
    public function testCreateResolverInstance(string $environment, string $expectedLogger): void
    {
        // Given factory
        $loggers['default'] = new NullLogger();
        $loggers['null'] = new NullLogger();
        $loggers['external'] = (new SentryFactory('https://public@sentry.example.com/1'))->create();
        $loggers['file'] = new Logger('name');
        $sut = new LoggerFactory($environment, ...array_values($loggers));

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(get_class($loggers[$expectedLogger]), $actual);
    }

    public function providerForCreateResolverInstance(): Generator
    {
        yield 'environment = development should use file' => ['development', 'file'];
        yield 'environment = production should use external' => ['production', 'external'];
        yield 'environment = sandbox should use external' => ['sandbox', 'external'];
        yield 'environment = staging should use external' => ['staging', 'external'];
        yield 'environment = testing should use empty' => ['testing', 'null'];
        yield 'environment = any other should use default' => ['any-other', 'default'];
    }
}
