<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Monolog;

final class Config implements ConfigInterface
{
    private string $path;
    private string $fileName;
    private int $permission;
    private string $level;

    public function __construct(
        string $path,
        string $fileName = 'app.txt',
        int $permission = 0777,
        string $level = 'DEBUG'
    ) {
        $this->path = $path;
        $this->fileName = $fileName;
        $this->permission = $permission;
        $this->level = $level;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFilePermission(): int
    {
        return $this->permission;
    }
}
