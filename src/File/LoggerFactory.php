<?php

declare(strict_types=1);

namespace Efficio\Logger\File;

use DateTimeZone;
use Efficio\Logger\LoggerFactory as FactoryInterface;
use Efficio\Logger\Monolog\Processor\Normalization;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use const DIRECTORY_SEPARATOR;

final class LoggerFactory implements FactoryInterface
{
    private const LOGGER_NAME = 'file-logger';

    private ConfigInterface $config;

    private ?DateTimeZone $timezone;

    /**
     * @param ConfigInterface|array $config
     * @param DateTimeZone|null $timezone
     */
    public function __construct(
        $config,
        ?DateTimeZone $timezone = null
    ) {
        $this->config = is_array($config) ? Config::fromArray($config) : $config;
        $this->timezone = $timezone;
    }

    public function create(): LoggerInterface
    {
        return new Logger(
            self::LOGGER_NAME,
            $this->createHandlers(),
            [new Normalization()],
            $this->timezone
        );
    }

    private function createHandlers(): array
    {
        $c = $this->config;

        $rotatingFileHandler = new RotatingFileHandler(
            $c->getPath() . DIRECTORY_SEPARATOR . $c->getFileName(),
            0,
            /** @phpstan-ignore-next-line */
            $c->getLevel(),
            true,
            $c->getFilePermission()
        );

        $rotatingFileHandler->setFormatter(
            new LineFormatter()
        );

        return [$rotatingFileHandler];
    }
}
