<?php

declare(strict_types=1);

namespace Efficio\Logger\Sentry;

use DateTimeZone;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Efficio\Logger\Sentry\Logger as Decorator;
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
    private ?DateTimeZone $timezone;

    public function __construct(string $dsn, array $config = [], DateTimeZone $zone = null)
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->timezone = $zone;
    }

    public function create(): LoggerInterface
    {
        $monolog = new MonologLogger(self::LOGGER_NAME, [], [], $this->timezone);

        $config = array_merge(['dsn' => $this->dsn], $this->config);
        $client = ClientBuilder::create($config)->getClient();

        $handler = new Handler(
            new Hub($client),
            $config['level'] ?? LogLevel::ERROR
        );

        $monolog->pushHandler($handler);

        return new Decorator($monolog);
    }
}
