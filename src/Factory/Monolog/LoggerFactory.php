<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Monolog;

use DateTimeZone;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory implements FactoryInterface
{
    private const LOGGER_NAME = 'file-logger';

    private Config $config;

    /** @var HandlerInterface[] */
    private array $handlers;

    private ?DateTimeZone $timezone;

    public function __construct(
        Config $config,
        ?HandlerFactory $handlerFactory = null,
        ?DateTimeZone $timezone = null
    ) {
        $this->config = $config;
        $this->timezone = $timezone;
        $defaultHandlerFactory = new DefaultHandlerFactory($this->config);
        $this->handlers = $handlerFactory ? $handlerFactory->create() : $defaultHandlerFactory->create();
    }

    public function create(): LoggerInterface
    {
        return new Logger(self::LOGGER_NAME, $this->handlers, [], $this->timezone);
    }
}
