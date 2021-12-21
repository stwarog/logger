<?php

declare(strict_types=1);

namespace Tests\Unit\Resolver;

use Efficio\Logger\Decorator;
use Efficio\Logger\Environment;
use Efficio\Logger\File\Config;
use Efficio\Logger\File\LoggerFactory as FileFactory;
use Efficio\Logger\LoggerFactory as LoggerFactoryInterface;
use Efficio\Logger\LoggerTypes;
use Efficio\Logger\NullObject\LoggerFactory as NullObjectFactory;
use Efficio\Logger\Resolver\LoggerFactory;
use Efficio\Logger\Sentry\Logger;
use Efficio\Logger\Sentry\LoggerFactory as SentryFactory;
use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/** @covers \Efficio\Logger\Resolver\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new LoggerFactory(
            new Environment(Environment::STAGING),
            $this->createStub(LoggerFactoryInterface::class),
            $this->createStub(LoggerFactoryInterface::class),
            $this->createStub(LoggerFactoryInterface::class),
            $this->createStub(LoggerFactoryInterface::class),
        );
        $this->assertInstanceOf(LoggerFactoryInterface::class, $sut);
    }

    public function testCreateFromContainer(): void
    {
        // Given LoggerFactory classes in DI Container
        $env = Environment::STAGING;

        $default = $this->createStub(LoggerFactoryInterface::class);
        $null = $this->createStub(LoggerFactoryInterface::class);
        $external = $this->createStub(LoggerFactoryInterface::class);
        $local = $this->createStub(LoggerFactoryInterface::class);

        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->withConsecutive(
                ['environment'],
                [LoggerTypes::DEFAULT],
                [LoggerTypes::NULL],
                [LoggerTypes::EXTERNAL],
                [LoggerTypes::LOCAL],
            )
            ->willReturnOnConsecutiveCalls(
                $env,
                $default,
                $null,
                $external,
                $local
            );

        // When ResolverFactory is created from a given Container
        $sut = LoggerFactory::createFrom($container);

        // Then depending on environment,
        $this->assertInstanceOf(LoggerFactoryInterface::class, $sut);
    }

    /** @dataProvider providerForCreateResolverInstance */
    public function testCreateResolverInstance(string $environment, string $loggerFactoryName): void
    {
        // Given factory
        $loggers['default'] = new NullObjectFactory();
        $loggers['null'] = new NullObjectFactory();
        $loggers['external'] = new SentryFactory('https://public@sentry.example.com/1');
        $loggers['file'] = new FileFactory(new Config('/'));

        // And expected concrete loggers
        $expectedLoggerInstance[LoggerTypes::DEFAULT] = NullLogger::class;
        $expectedLoggerInstance[LoggerTypes::NULL] = NullLogger::class;
        $expectedLoggerInstance[LoggerTypes::EXTERNAL] = Logger::class;
        $expectedLoggerInstance[LoggerTypes::LOCAL] = Decorator::class;

        $sut = new LoggerFactory(new Environment($environment), ...array_values($loggers));

        // When created
        $actual = $sut->create();

        // Then newly created instance should be
        $this->assertInstanceOf(LoggerInterface::class, $actual);
        $this->assertInstanceOf($expectedLoggerInstance[$loggerFactoryName], $actual);
    }

    public function providerForCreateResolverInstance(): Generator
    {
        yield 'environment = development should use local (file)' => ['development', LoggerTypes::LOCAL];
        yield 'environment = production should use external (sentry)' => ['production', LoggerTypes::EXTERNAL];
        yield 'environment = sandbox should use external (sentry)' => ['sandbox', LoggerTypes::EXTERNAL];
        yield 'environment = staging should use external (sentry)' => ['staging', LoggerTypes::EXTERNAL];
        yield 'environment = testing should use empty' => ['testing', LoggerTypes::NULL];
        yield 'environment = any other should use default' => ['any-other', LoggerTypes::DEFAULT];
    }
}
