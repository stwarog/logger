<?php

declare(strict_types=1);

namespace Efficio\Logger\NullObject;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class LoggerFactory implements FactoryInterface
{
    public function create(): LoggerInterface
    {
        return new NullLogger();
    }
}
