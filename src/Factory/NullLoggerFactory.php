<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class NullLoggerFactory implements FactoryInterface
{
    public function create(): LoggerInterface
    {
        return new NullLogger();
    }
}
