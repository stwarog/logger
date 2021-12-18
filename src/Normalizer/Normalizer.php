<?php

namespace Efficio\Logger\Normalizer;

/** @internal */
interface Normalizer
{
    public function normalize(array $data): array;
}
