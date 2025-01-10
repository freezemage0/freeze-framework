<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

final class Header
{
    /**
     * @param string $name
     * @param array<array-key, string> $value
     */
    public function __construct(
        public readonly string $name,
        public readonly array $value = []
    ) {
    }

    public function getLine(): string
    {
        return \implode(', ', $this->value);
    }
}
