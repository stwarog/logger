<?php

declare(strict_types=1);

namespace Efficio\Logger\Resolver;

use Efficio\Logger\Environment;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Psr\Log\LoggerInterface as Logger;

final class LoggerFactory implements FactoryInterface
{
    private string $environment;
    private FactoryInterface $default;
    private FactoryInterface $null;
    private FactoryInterface $external;
    private FactoryInterface $local;

    public function __construct(
        Environment $environment,
        FactoryInterface $default,
        FactoryInterface $null,
        FactoryInterface $external,
        FactoryInterface $local
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
                return $this->local->create();
            case Environment::PROD:
            case Environment::SANDBOX:
            case Environment::STAGING:
                return $this->external->create();
            case Environment::TEST:
                return $this->null->create();
            default:
                return $this->default->create();
        }
    }
}
