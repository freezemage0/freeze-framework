<?php

declare(strict_types=1);

namespace Freeze\Framework\Test;

use Freeze\Framework\Kernel\Factory\StreamFactory;
use Http\Psr7Test\StreamIntegrationTest;
use Psr\Http\Message\StreamInterface;

final class StreamTest extends StreamIntegrationTest
{
    public function createStream($data): StreamInterface
    {
        return (new StreamFactory())->createStreamFromResource($data);
    }
}
