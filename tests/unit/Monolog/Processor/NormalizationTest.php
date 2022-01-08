<?php

declare(strict_types=1);

namespace Tests\Unit\Monolog\Processor;

use Efficio\Logger\Monolog\Processor\Normalization;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/** @covers \Efficio\Logger\Monolog\Processor\Normalization */
final class NormalizationTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new Normalization();
        $this->assertInstanceOf(ProcessorInterface::class, $sut);
    }

    public function testInvokeNormalizesContextAndExtraUsingGenericNormalizerFactory(): void
    {
        // Given sut
        $sut = new Normalization();

        // And some Record array with context & extra keys
        $context = [123, 'asd'];
        $extra = [431, 'abc'];

        $record = [
            'message' => 'message',
            'level' => LogLevel::ERROR,
            'level_name' => LogLevel::ERROR,
            'context' => $context,
            'channel' => 'channel',
            'extra' => $extra,
        ];

        // When invoked
        $result = $sut($record);

        // Then result should be as expected
        $expected = array(
            'message' => 'message',
            'level' => 'error',
            'level_name' => 'error',
            'context' =>
                array(
                    0 => 123,
                    1 => 'asd',
                ),
            'channel' => 'channel',
            'extra' =>
                array(
                    0 => 431,
                    1 => 'abc',
                )
        );

        $this->assertSame($expected, $result);
    }
}
