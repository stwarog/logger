<?php

namespace Efficio\Logger;

use Psr\Log\LoggerInterface;

interface LoggerFactory
{
    public function create(): LoggerInterface;
}
