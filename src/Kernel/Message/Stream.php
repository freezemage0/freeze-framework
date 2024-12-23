<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class Stream implements StreamInterface
{
    /** @var resource|null */
    private $stream;

    /**
     * @param resource|null $stream
     */
    public function __construct($stream) {
        if (!\is_resource($stream)) {
            throw new InvalidArgumentException('Stream must be of resource type');
        }

        $this->stream = $stream;
    }

    public function __toString(): string
    {
        $this->rewind();
        return $this->getContents();
    }

    public function close(): void
    {
        \fclose($this->stream());
    }

    public function detach()
    {
        $stream = $this->stream();
        $this->stream = null;

        return $stream;
    }

    public function getSize(): ?int
    {
        $stats = \fstat($this->stream());
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        return \ftell($this->stream());
    }

    public function eof(): bool
    {
        return \feof($this->stream());
    }

    public function isSeekable(): bool
    {
        return (bool) $this->getMetadata('seekable');
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        \fseek($this->stream(), $offset, $whence);
    }

    public function rewind(): void
    {
        \fseek($this->stream(), 0);
    }

    public function write(string $string): int
    {
        return \fwrite($this->stream(), $string);
    }

    public function read(int $length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('Unreadable stream');
        }

        return \fread($this->stream(), $length);
    }

    public function getContents(): string
    {
        return \stream_get_contents($this->stream());
    }

    public function getMetadata(?string $key = null): mixed
    {
        $meta = \stream_get_meta_data($this->stream());

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }

    private function stream(): mixed
    {
        return $this->stream ?? throw new RuntimeException('Stream is in unusable state');
    }
    public function isWritable(): bool
    {
        $mode = $this->getMetadata('mode');
        if (\str_contains($mode, '+')) {
            return true;
        }

        return $mode !== 'r' && $mode !== 'rb' && $mode !== 'rt';
    }

    public function isReadable(): bool
    {
        $mode = $this->getMetadata('mode');
        if (\str_contains($mode, '+')) {
            return true;
        }

        return $mode === 'r' || $mode === 'rb' || $mode === 'rt';
    }
}
