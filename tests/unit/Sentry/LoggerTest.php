<?php

declare(strict_types=1);

namespace Tests\Unit\Sentry;

use Efficio\Logger\Sentry\Logger;
use Exception;
use Monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;

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
    public function testLogContextHasArgumentAsExceptionShouldThrowException(string $method): void
    {
        // Expect exception
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid exception handling, exception should be passed as "exception" key');

        // Given Logger that decorated File Logger
        $monolog = $this->createMock(MonologLogger::class);
        $sut = new Logger($monolog);

        // And Context with one argument as exception
        $exception = new Exception('some message');
        $context = [$exception];

        $monolog->expects($this->never())->method($method);

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
