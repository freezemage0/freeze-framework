<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Freeze\Framework\Kernel\Message\Stream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = new Stream(\fopen('php://temp', 'r+'));

        if ($content !== '') {
            $stream->write($content);
            $stream->rewind();
        }

        return $stream;
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $stream = \fopen($filename, $mode);
        if (!$stream) {
            throw new RuntimeException('Failed to open file stream');
        }

        return new Stream($stream);
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        $stream = new Stream($resource);
        if (!$stream->isReadable()) {
            throw new RuntimeException('Underlying resource is not readable.');
        }

        return $stream;
    }
}
