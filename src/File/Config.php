<?php

declare(strict_types=1);

namespace Efficio\Logger\File;

use Psr\Log\LogLevel;

final class Config implements ConfigInterface
{
    private const DEFAULT_PERMISSION = 0777;
    private const DEFAULT_FILE_NAME = 'app.txt';

    private string $path;
    private string $fileName;
    private int $permission;
    private string $level;

    public function __construct(
        string $path,
        string $fileName = 'app.txt',
        int $permission = self::DEFAULT_PERMISSION,
        string $level = LogLevel::DEBUG
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

    public static function fromArray(array $config): self
    {
        return new self(
            $config['path'],
            $config['file_name'] ?? self::DEFAULT_FILE_NAME,
            $config['permission'] ?? self::DEFAULT_PERMISSION,
            $config['level'] ?? LogLevel::DEBUG,
        );
    }
}
