<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\File;

use Monolog\Handler\Handler;

interface HandlerFactory
{
    /**
     * @return array<Handler>
     */
    public function create(): array;
}
