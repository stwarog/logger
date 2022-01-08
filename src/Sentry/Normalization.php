<?php

declare(strict_types=1);

namespace Efficio\Logger\Sentry;

use Efficio\Logger\Normalizer\Factory;
use Monolog\Processor\ProcessorInterface;

/** @internal */
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

        $normalized = $this->normalize($record['context']);
        $record['context'] = [
            'extra' => $normalized
        ];

        if ($exception) {
            $record['context']['exception'] = $exception;
        }

        return $record;
    }

    private function normalize(array $data = []): array
    {
        $normalizer = Factory::create();

        $normalized = $normalizer->normalize($data);

        return is_array($normalized) ? $normalized : [$normalized];
    }
}
