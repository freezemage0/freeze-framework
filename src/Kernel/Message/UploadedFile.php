<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

final class UploadedFile implements UploadedFileInterface
{
    private bool $moved = false;


    public function __construct(
            private readonly StreamInterface $stream,
            private readonly ?string $clientFilename,
            private readonly ?string $clientMediaType,
            private readonly ?int $size,
            private readonly int $error
    ) {
    }

    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    public function moveTo(string $targetPath): void
    {
        // TODO: Rework.
        if ($this->moved) {
            throw new RuntimeException('Uploaded file was already moved.');
        }

        if (\is_uploaded_file($this->clientFilename)) {
            // Environment that populates $_FILES -> use move_uploaded_file()
            if (!\move_uploaded_file($this->clientFilename, $targetPath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
        } else {
            if (!\rename($this->clientFilename, $targetPath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
        }

        $this->moved = true;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
