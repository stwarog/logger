<?php

declare(strict_types=1);

namespace Tests\Resolver;

use Efficio\Logger\Environment;
use Efficio\Logger\Resolver\LoggerFactory;
use Efficio\Logger\Sentry\LoggerFactory as SentryFactory;
use Generator;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/** @covers \Efficio\Logger\Resolver\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory(
            new Environment(Environment::STAGING),
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
        $sut = new LoggerFactory(new Environment($environment), ...array_values($loggers));

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf(get_class($loggers[$expectedLogger]), $actual);
    }

    public function providerForCreateResolverInstance(): Generator
    {
        yield 'environment = development should use local (file)' => ['development', 'file'];
        yield 'environment = production should use external (sentry)' => ['production', 'external'];
        yield 'environment = sandbox should use external (sentry)' => ['sandbox', 'external'];
        yield 'environment = staging should use external (sentry)' => ['staging', 'external'];
        yield 'environment = testing should use empty' => ['testing', 'null'];
        yield 'environment = any other should use default' => ['any-other', 'default'];
    }
}
