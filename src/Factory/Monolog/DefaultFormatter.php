<?php

declare(strict_types=1);

namespace Efficio\Logger\Factory\Monolog;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;

final class DefaultFormatter extends LineFormatter implements FormatterInterface
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        ?string $format = null,
        ?string $dateFormat = self::DATE_FORMAT,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false
    ) {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    public static function create(): self
    {
        return new self();
    }
}
