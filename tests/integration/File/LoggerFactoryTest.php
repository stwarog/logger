<?php

declare(strict_types=1);

namespace Tests\Integration\File;

use DateTime;
use Efficio\Logger\File\Config;
use Efficio\Logger\File\LoggerFactory;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

use const DIRECTORY_SEPARATOR;

/** @covers \Efficio\Logger\File\LoggerFactory */
final class LoggerFactoryTest extends TestCase
{
    private const PATH = __DIR__ . DIRECTORY_SEPARATOR . 'some-nested-path' . DIRECTORY_SEPARATOR;

    public function testLoggerCreateDirectoryWhenNotExists(): void
    {
        // Given logger factory
        $factory = $this->getLoggerFactory();

        // And expected file name should be
        $fileName = $this->getFileName();
        $expectedFullName = sprintf('%s%s', self::PATH, $fileName);

        // When log is created
        $logger = $factory->create();
        $logger->info('message');

        // Then path should be created
        $this->assertTrue(
            file_exists($expectedFullName),
            sprintf('Given path does not exist: %s', $expectedFullName)
        );
    }

    private function getLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory(
            new Config(
                self::PATH
            )
        );
    }

    private function getFileName(): string
    {
        return sprintf('app-%s.txt', (new DateTime())->format('Y-m-d'));
    }

    /** @dataProvider provideData */
    public function testLogShouldBeInExpectedFormat(
        array $context,
        string $expected,
        string $method
    ): void {
        // Given logger factory
        $factory = $this->getLoggerFactory();
        $logger = $factory->create();

        // When log is created
        $logger->$method('message', $context);

        // Then generated output should contain expected data
        $this->assertValidOutput($expected);
    }

    private function assertValidOutput(string $expected): void
    {
        $actualContent = (string)file_get_contents(self::PATH . $this->getFileName());
        $flatten = fn(string $actual) => str_replace([' ', PHP_EOL], ['', ''], $actual);
        $this->assertStringContainsString($flatten($expected), $flatten($actualContent));
    }

    public function provideData(): Generator
    {
        yield 'context is an empty array' => [
            'context' => [],
            'expected' => '
            {
                "message": "message",
                "context": {},
                "level": 200,
                "level_name": "INFO",
                "channel": "file-logger",
            ',
            'method' => 'warning',
        ];

        yield 'context is an simple array' => [
            'context' => [
                'field1' => 123.21,
                'field2' => [
                    'nested-value' => 'as string'
                ]
            ],
            'expected' => '
            {
                "message": "message",
                "context": {
                    "field1": 123.21,
                    "field2": {
                        "nested-value": "as string"
                    }
                },
                "level": 200,
                "level_name": "INFO",
                "channel": "file-logger",
            ',
            'method' => 'info',
        ];

        yield 'context has exception' => [
            'context' => [
                new Exception('Some message')
            ],
            'expected' => '
            {
            "message": "message",
            "context": [
                {
                    "message": "Some message",
                    "file": "/app/tests/integration/File/LoggerFactoryTest.php",
                    "line": 115,
                    "trace":
            ',
            'method' => 'error',
        ];
    }

    protected function tearDown(): void
    {
        self::cleanUp();
        parent::tearDown();
    }

    private static function cleanUp(): void
    {
        $path = self::PATH;

        if (file_exists($path)) {
            system("rm -rf " . escapeshellarg($path));
        }
    }
}
