<?php

declare(strict_types=1);

namespace Normalizer\Custom;

use Efficio\Logger\Normalizer\Custom\ExceptionNormalizer;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/** @covers \Efficio\Logger\Normalizer\Custom\ExceptionNormalizer */
final class ExceptionNormalizerTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new ExceptionNormalizer();
        $this->assertInstanceOf(NormalizerInterface::class, $sut);
    }

    public function testNormalizeException(): void
    {
        // Given SUT
        $sut = new ExceptionNormalizer();

        // And an Exception
        $exception = new Exception('some message');

        // When normalized
        $actual = $sut->normalize($exception);

        // Then it should be as expected
        $this->assertArrayHasKey('message', $actual);
        $this->assertArrayHasKey('file', $actual);
        $this->assertArrayHasKey('line', $actual);
        $this->assertArrayHasKey('trace', $actual);
    }

    /** @dataProvider provideSupportsNormalization */
    public function testSupportsNormalization(object $object, bool $supports): void
    {
        $sut = new ExceptionNormalizer();
        $this->assertSame($supports, $sut->supportsNormalization($object));
    }

    public function provideSupportsNormalization(): Generator
    {
        yield 'throwable' => [
            'object' => new AccessException(),
            'supports' => true,
        ];

        yield 'exception' => [
            'object' => new Exception(),
            'supports' => true,
        ];

        yield 'none exception or throwable' => [
            'object' => new stdClass(),
            'supports' => false,
        ];
    }
}
