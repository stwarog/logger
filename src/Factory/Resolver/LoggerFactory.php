<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Resolver;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Psr\Log\LoggerInterface as Logger;

final class LoggerFactory implements FactoryInterface
{
    private string $environment;
    private Logger $defaultLogger;
    private Logger $null;
    private Logger $external;
    private Logger $file;

    public function __construct(
        string $environment,
        Logger $defaultLogger,
        Logger $null,
        Logger $external,
        Logger $file
    ) {
        $this->environment = $environment;
        $this->defaultLogger = $defaultLogger;
        $this->null = $null;
        $this->external = $external;
        $this->file = $file;
    }

    public function create(): Logger
    {
        switch ($this->environment) {
            case 'development':
                return $this->file;
            case 'production':
            case 'sandbox':
            case 'staging':
                return $this->external;
            case 'testing':
                return $this->null;
            default:
                return $this->defaultLogger;
        }
    }
}
