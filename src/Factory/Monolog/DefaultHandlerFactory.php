<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Monolog;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;

use const DIRECTORY_SEPARATOR;

final class DefaultHandlerFactory implements HandlerFactory
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return HandlerInterface[]
     */
    public function create(): array
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

        $rotatingFileHandler->setFormatter(DefaultFormatter::create());

        return [$rotatingFileHandler];
    }
}
