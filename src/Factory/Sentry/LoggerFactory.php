<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Sentry;

use Efficio\Logger\LoggerFactory as FactoryInterface;
use Facile\Sentry\Log\Logger;
use Psr\Log\LoggerInterface;
use Raven_Client;

/**
 * @see https://github.com/facile-it/sentry-psr-log Adapter
 */
final class LoggerFactory implements FactoryInterface
{
    private string $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function create(): LoggerInterface
    {
        // # todo it requires Curl, consider finding better package for this purpose
        $ravenClient = new Raven_Client($this->dsn, []);

        return new Logger($ravenClient);
    }
}
