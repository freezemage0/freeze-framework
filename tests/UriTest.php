<?php

declare(strict_types=1);

namespace Freeze\Framework\Test;

use Freeze\Framework\Kernel\Factory\UriFactory;
use Http\Psr7Test\UriIntegrationTest;
use Psr\Http\Message\UriInterface;

final class UriTest extends UriIntegrationTest
{
    public function createUri($uri): UriInterface
    {
        return (new UriFactory())->createUri($uri);
    }
}
