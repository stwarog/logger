<?php

declare(strict_types=1);

namespace Efficio\Logger\Normalizer\Custom;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

final class ExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param Throwable|mixed $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'message' => $object->getMessage(),
            'file' => $object->getFile(),
            'line' => $object->getLine(),
            'trace' => $object->getTrace(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Throwable;
    }
}
