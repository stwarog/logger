<?php

declare(strict_types=1);

namespace Tests\Unit\Sentry;

use Efficio\Logger\Sentry\Normalization;
use Exception;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/** @covers \Efficio\Logger\Sentry\Normalization */
final class NormalizationTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new Normalization();
        $this->assertInstanceOf(ProcessorInterface::class, $sut);
    }

    public function testInvokeNormalizesAndMovesContextToExtraKey(): void
    {
        // Given sut
        $sut = new Normalization();

        // And some Record array with context
        $context = [123, 'asd'];

        $record = [
            'message' => 'message',
            'level' => LogLevel::ERROR,
            'level_name' => LogLevel::ERROR,
            'context' => $context,
            'channel' => 'channel',
            'extra' => [],
        ];

        // When invoked
        $result = $sut($record);

        // Then context should be moved to extra key (this is the requirement from Sentry Handler)
        $expected = [
            'message' => 'message',
            'level' => 'error',
            'level_name' => 'error',
            'context' =>
                [
                    'extra' =>
                        [
                            0 => 123,
                            1 => 'asd',
                        ],
                ],
            'channel' => 'channel',
            'extra' => []
        ];

        $this->assertSame($expected, $result);
    }

    public function testNormalizeShouldNotAffectExceptionContextKeyIfPresent(): void
    {
        // Given record with exception in
        $exception = new Exception();
        $sut = new Normalization();

        $record = [
            'message' => 'message',
            'level' => LogLevel::ERROR,
            'level_name' => LogLevel::ERROR,
            'context' => [
                'extra' => [
                    'some-field' => 123,
                ],
                'exception' => $exception,
            ],
            'channel' => 'channel',
            'extra' => [],
        ];

        // When normalized
        $actual = $sut($record);

        // Then exception should not be affected
        $this->assertSame($exception, $actual['context']['exception']);
    }
}
