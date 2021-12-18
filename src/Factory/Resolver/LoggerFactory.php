<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Resolver;

use Efficio\Logger\Environment;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Psr\Log\LoggerInterface as Logger;

final class LoggerFactory implements FactoryInterface
{
    private string $environment;
    private Logger $default;
    private Logger $null;
    private Logger $external;
    private Logger $local;

    public function __construct(
        Environment $environment,
        Logger $default,
        Logger $null,
        Logger $external,
        Logger $local
    ) {
        $this->environment = (string)$environment;
        $this->default = $default;
        $this->null = $null;
        $this->external = $external;
        $this->local = $local;
    }

    public function create(): Logger
    {
        switch ($this->environment) {
            case Environment::DEV:
                return $this->local;
            case Environment::PROD:
            case Environment::SANDBOX:
            case Environment::STAGING:
                return $this->external;
            case Environment::TEST:
                return $this->null;
            default:
                return $this->default;
        }
    }
}
