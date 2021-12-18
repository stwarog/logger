<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Sentry;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sentry\ClientBuilder;
use Sentry\Monolog\Handler;
use Sentry\State\Hub;

final class LoggerFactory implements FactoryInterface
{
    private const LOGGER_NAME = 'sentry';

    private string $dsn;
    private array $config;

    public function __construct(string $dsn, array $config = [])
    {
        $this->dsn = $dsn;
        $this->config = $config;
    }

    public function create(): LoggerInterface
    {
        $monolog = new MonologLogger(self::LOGGER_NAME);

        $config = array_merge(['dsn' => $this->dsn], $this->config);
        $client = ClientBuilder::create($config)->getClient();

        $handler = new Handler(
            new Hub($client),
            $config['level'] ?? LogLevel::DEBUG
        );

        $monolog->pushHandler($handler);

        return new Logger($monolog);
    }
}
