<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Sentry;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Sentry\ClientBuilder;
use Sentry\Monolog\Handler;
use Sentry\State\Hub;

final class LoggerFactory implements FactoryInterface
{
    private const LOGGER_NAME = 'sentry';

    private string $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function create(): LoggerInterface
    {
        $monolog = new MonologLogger(self::LOGGER_NAME);

        $client = ClientBuilder::create(['dsn' => $this->dsn])->getClient();

        $handler = new Handler(
            new Hub($client)
        );

        $monolog->pushHandler($handler);

        return new Logger($monolog);
    }
}
