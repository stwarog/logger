<?php

declare(strict_types=1);

namespace Efficio\Logger\Normalizer;

/**
 * @internal
 *
 * This class is used across the whole library, to generate Normalizer.
 * Change the implementation to adjust default Normalizer behaviour.
 */
final class Factory
{
    public static function create(): Normalizer
    {
        return new Symfony();
    }
}
