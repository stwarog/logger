<?php

declare(strict_types=1);

namespace Efficio\Logger;

final class Environment
{
    public const DEV = 'development';
    public const PROD = 'production';
    public const TEST = 'testing';
    public const SANDBOX = 'sandbox';
    public const STAGING = 'staging';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
