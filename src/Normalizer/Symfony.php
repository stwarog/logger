<?php

declare(strict_types=1);

namespace Efficio\Logger\Normalizer;

use Efficio\Logger\Normalizer\Custom\ExceptionNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Serializer;

/** @internal */
final class Symfony implements Normalizer
{
    private Serializer $serializer;

    public function __construct()
    {
        $normalizers = [
            new JsonSerializableNormalizer(),
            new DateTimeNormalizer(),
            new ExceptionNormalizer(),
            # new GetSetMethodNormalizer(), # Investigate why failing on some classes (unknown __STATIC caller)
            # new ObjectNormalizer(),
        ];
        $this->serializer = new Serializer($normalizers, []);
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize(array $data): array
    {
        return $this->serializer->normalize($data);
    }
}
