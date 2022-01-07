<?php

declare(strict_types=1);

namespace Efficio\Logger\Sentry;

use Efficio\Logger\Decorator;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

final class Logger extends Decorator implements LoggerInterface
{
    protected function parseContext(array $context): array
    {
        /*
         * @see https://www.php-fig.org/psr/psr-3/
         * If an Exception object is passed in the context data, it MUST be in the 'exception' key. Logging exceptions
         * is a common pattern and this allows implementors to extract a stack trace from the exception when the log
         * backend supports it. Implementors MUST still verify that the 'exception' key is actually an Exception before
         * using it as such, as it MAY contain anything.
         */
        $hasSomeExceptionsIn = count(array_filter($context, fn($i) => $i instanceof Throwable)) > 0;
        $hasNotExceptionKey = !isset($context['exception']);

        if ($hasSomeExceptionsIn && $hasNotExceptionKey) {
            throw new RuntimeException(
                'Invalid exception handling, exception should be passed as "exception" key'
            );
        }

        // There is also no easy way to add a context, so we have to map it here.

        if ($hasNotExceptionKey) {
            return [$this->normalizer->normalize($context)];
        }

        $extra = $context;
        unset($extra['exception']);
        $extra = $this->normalizer->normalize($extra);

        $context = ['exception' => $context['exception']];

        return array_merge(
            $context,
            [
                'extra' => $extra
            ]
        );
    }
}
