<?php

declare(strict_types=1);

namespace Efficio\Logger\Sentry;

use DateTimeZone;
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
    private ?DateTimeZone $timezone;

    public function __construct(string $dsn, array $config = [], DateTimeZone $zone = null)
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->timezone = $zone;
    }

    public function create(): LoggerInterface
    {
        $monolog = new MonologLogger(self::LOGGER_NAME, [], [new Normalization()], $this->timezone);

        $level = $this->config['level'] ?? LogLevel::ERROR;

        if (isset($this->config['level'])) {
            unset($this->config['level']);
        }

        $config = array_merge(['dsn' => $this->dsn], $this->config);
        $client = ClientBuilder::create($config)->getClient();

        $handler = new Handler(
            new Hub($client),
            $level
        );

        $monolog->pushHandler($handler);

        return $monolog;
    }
}
