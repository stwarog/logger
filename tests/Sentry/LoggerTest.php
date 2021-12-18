<?php

declare(strict_types=1);

namespace Tests\Sentry;

use Efficio\Logger\Normalizer\Custom\ExceptionNormalizer;
use Efficio\Logger\Sentry\Logger;
use Exception;
use Monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/** @covers \Efficio\Logger\Sentry\Logger */
final class LoggerTest extends TestCase
{
    public function testConstructor(): void
    {
        $monolog = $this->createMock(MonologLogger::class);
        $sut = new Logger($monolog);
        $this->assertInstanceOf(Logger::class, $sut);
        $this->assertInstanceOf(LoggerInterface::class, $sut);
    }

    /** @dataProvider provideMethods */
    public function testLogContextHasOneArgAsExceptionShouldBeMappedToException(string $method): void
    {
        // Given Logger that decorated File Logger
        $monolog = $this->createMock(MonologLogger::class);
        $sut = new Logger($monolog);

        // And Context with one argument as exception
        $exception = new Exception('some message');
        $context = [$exception];

        // Then context should be parsed
        $expectedContext = ['exception' => $exception];
        $monolog->expects($this->once())->method($method)->with('message', $expectedContext);

        // When logged
        $sut->$method('message', $context);
    }


    /** @dataProvider provideMethods */
    public function testLogContextHasManyArgAsExceptionShouldNotBeMapped(string $method): void
    {
        // Given Logger that decorated File Logger
        $monolog = $this->createMock(MonologLogger::class);
        $sut = new Logger($monolog);

        // And Context with one argument as exception
        $exception1 = new Exception('some message');
        $exception2 = new Exception('some message');
        $context = [$exception1, $exception2];

        // Then context should be parsed
        $errorNormalizer = new ExceptionNormalizer();
        $expectedContext = [
            'extra' => [
                $errorNormalizer->normalize($exception1),
                $errorNormalizer->normalize($exception2)
            ]
        ];
        $monolog->expects($this->once())->method($method)->with('message', $expectedContext);

        // When logged
        $sut->$method('message', $context);
    }

    public function provideMethods(): array
    {
        return [
            'emergency' => ['emergency'],
            'alert' => ['alert'],
            'critical' => ['critical'],
            'error' => ['error'],
            'warning' => ['warning'],
            'notice' => ['notice'],
            'info' => ['info'],
            'debug' => ['debug'],
        ];
    }
}
