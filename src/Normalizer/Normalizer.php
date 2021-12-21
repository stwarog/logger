<?php

namespace Efficio\Logger\Normalizer;

use ArrayObject;

/** @internal */
interface Normalizer
{
    /**
     * @param mixed $data
     * @return array|string|int|float|bool|ArrayObject|null \ArrayObject
     */
    public function normalize($data);
}
