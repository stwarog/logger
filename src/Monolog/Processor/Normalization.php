<?php

declare(strict_types=1);

namespace Efficio\Logger\Monolog\Processor;

use Efficio\Logger\Normalizer\Factory;
use Monolog\Processor\ProcessorInterface;

/**
 * Class Normalization
 * This is Monolog Processor implementation, that provides our normalization from Normalizer/Factory.
 * It should be used by all Monolog loggers in our project to have consistent mapping.
 */
final class Normalization implements ProcessorInterface
{
    /** @inheritdoc */
    public function __invoke(array $record): array
    {
        $exception = null;

        if (isset($record['context']['exception'])) {
            $exception = $record['context']['exception'];
            unset($record['context']['exception']);
        }

        $record['context'] = $this->normalize($record['context']);

        if ($exception) {
            $record['context']['exception'] = $exception;
        }

        $record['extra'] = $this->normalize($record['extra']);


        return $record;
    }

    private function normalize(array $data = []): array
    {
        $normalizer = Factory::create();

        $normalized = $normalizer->normalize($data);

        return is_array($normalized) ? $normalized : [$normalized];
    }
}
