<?php

namespace Efficio\Logger\Factory\File;

interface ConfigInterface
{
    /**
     * @see \Monolog\Logger constants:
     * 100|200|250|300|400|500|550|600|'ALERT'|'alert'|'CRITICAL'|'critical'|'DEBUG'|'debug'|'EMERGENCY'
     * |'emergency'|'ERROR'|'error'|'INFO'|'info'|'NOTICE'|'notice'|'WARNING'|'warning'
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * An absolute path where to store the logs output.
     * @return string
     */
    public function getPath(): string;

    /**
     * Generated log file e.g. app.txt
     * @return string
     */
    public function getFileName(): string;

    /**
     * todo: clarify purpose of this
     * must be writable, 0775 e.g.
     * @return int
     */
    public function getFilePermission(): int;
}
