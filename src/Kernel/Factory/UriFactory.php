<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

final class UriFactory implements UriFactoryInterface
{
    private const URI_PATTERN = '';

    public function createUri(string $uri = ''): UriInterface
    {

    }
}
