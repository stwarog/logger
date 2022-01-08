<?php

declare(strict_types=1);

namespace Efficio\Logger\File;

use DateTimeZone;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Efficio\Logger\Monolog\Processor\Normalization;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory implements FactoryInterface
{
    private const LOGGER_NAME = 'file-logger';

    private ConfigInterface $config;

    /** @var HandlerInterface[] */
    private array $handlers;

    private ?DateTimeZone $timezone;

    /**
     * @param ConfigInterface|array $config
     * @param HandlerFactory|null $handlerFactory
     * @param DateTimeZone|null $timezone
     */
    public function __construct(
        $config,
        ?HandlerFactory $handlerFactory = null,
        ?DateTimeZone $timezone = null
    ) {
        $this->config = is_array($config) ? Config::fromArray($config) : $config;
        $this->timezone = $timezone;
        $defaultHandlerFactory = new DefaultHandlerFactory($this->config);
        $this->handlers = $handlerFactory ? $handlerFactory->create() : $defaultHandlerFactory->create();
    }

    public function create(): LoggerInterface
    {
        $logger = new Logger(
            self::LOGGER_NAME,
            $this->handlers,
            [new Normalization()],
            $this->timezone
        );

        return $logger;
    }
}
