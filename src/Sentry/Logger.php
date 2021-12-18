<?php

declare(strict_types=1);

namespace Efficio\Logger\Sentry;

use Efficio\Logger\Decorator;
use Psr\Log\LoggerInterface;
use Throwable;

final class Logger extends Decorator implements LoggerInterface
{
    protected function parseContext(array $context): array
    {
        // If we have only one argument passed as exception e.g. [new Exception()]
        // then we wish to have it captured as native SDK capture exception function does.
        // To achieve this by File handlers, we have to use syntax ['exception' => new Exception()].

        $hasOneArgumentAsException = count(array_filter($context, fn($i) => $i instanceof Throwable)) === 1;

        if ($hasOneArgumentAsException) {
            return ['exception' => $context[0]];
        }

        // There is also no easy way to add a context, so we have to map it here.

        return ['extra' => $this->normalizer->normalize($context)];
    }
}
