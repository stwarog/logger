<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory;

use Efficio\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class NullLoggerFactory implements LoggerFactory
{
    public function create(): LoggerInterface
    {
        return new NullLogger();
    }
}
