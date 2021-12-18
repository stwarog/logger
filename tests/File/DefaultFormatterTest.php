<?php

declare(strict_types=1);

namespace Tests\File;

use Efficio\Logger\File\DefaultFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use PHPUnit\Framework\TestCase;

/** @covers \Efficio\Logger\File\DefaultFormatter */
final class DefaultFormatterTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new DefaultFormatter();
        $this->assertInstanceOf(LineFormatter::class, $sut);
        $this->assertInstanceOf(FormatterInterface::class, $sut);
        $this->assertDefaultConfig($sut);
    }

    public function testStaticConstructor(): void
    {
        $sut = DefaultFormatter::create();
        $this->assertInstanceOf(LineFormatter::class, $sut);
        $this->assertInstanceOf(FormatterInterface::class, $sut);
        $this->assertDefaultConfig($sut);
    }

    private function assertDefaultConfig(LineFormatter $f): void
    {
        $this->assertSame('Y-m-d H:i:s', $f->getDateFormat());
    }
}
