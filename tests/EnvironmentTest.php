<?php

declare(strict_types=1);

namespace Tests;

use Efficio\Logger\Environment;
use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testConstructor(): void
    {
        $sut = new Environment('env');
        $this->assertSame('env', (string)$sut);
    }
}
