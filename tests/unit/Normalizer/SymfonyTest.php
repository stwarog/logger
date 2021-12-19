<?php

declare(strict_types=1);

namespace Tests\Unit\Normalizer;

use DateTime;
use Efficio\Logger\Normalizer\Normalizer;
use Efficio\Logger\Normalizer\Symfony;
use Generator;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use stdClass;

/** @covers \Efficio\Logger\Normalizer\Symfony */
final class SymfonyTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new Symfony();
        $this->assertInstanceOf(Normalizer::class, $sut);
    }

    /** @dataProvider provideNormalize */
    public function testNormalize(array $data, array $expected): void
    {
        // Given SUT
        $sut = new Symfony();

        // When normalized
        $actual = $sut->normalize($data);

        // Then result should be as expected
        $this->assertSame($expected, $actual);
    }

    public function provideNormalize(): Generator
    {
        # todo: add some assetion for it
//        yield 'exception => array' => [
//            'data' => [
//                [new Exception('some message')]
//            ],
//            'expected' => []
//        ];

        yield 'empty array => empty array' => [
            'data' => [],
            'expected' => []
        ];

        $jsonSerializableClass = new class () implements JsonSerializable {

            public function jsonSerialize()
            {
                return ['field' => 1, 'field2' => 'abc'];
            }
        };

        yield 'json serializable' => [
            'data' => [$jsonSerializableClass, $jsonSerializableClass],
            'expected' => [
                ['field' => 1, 'field2' => 'abc'],
                ['field' => 1, 'field2' => 'abc'],
            ]
        ];

        $objectClass = new stdClass();
        $objectClass->field1 = 'field1';
        $objectClass->nested = new DateTime('yesterday');

        yield 'object serializable with nested date' => [
            'data' => [$objectClass],
            'expected' => [
                [
                    'field1' => 'field1',
                    'nested' => (new DateTime('yesterday'))->format(\DateTime::RFC3339),
                ]
            ]
        ];
    }
}
