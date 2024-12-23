<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    protected HeaderCollection $headerCollection;

    public function __construct(
            protected string $version,
            protected StreamInterface $body
    ) {
        $this->headerCollection = new HeaderCollection();
    }

    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($version === $this->version) {
            return $this;
        }

        $message = clone $this;
        $message->version = $version;

        return $message;
    }

    public function getHeaders(): array
    {
        return $this->headerCollection->toArray();
    }

    public function hasHeader(string $name): bool
    {
        return $this->headerCollection->get($name) !== null;
    }

    public function getHeader(string $name): array
    {
        return $this->headerCollection->get($name)->value ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return (string) $this->headerCollection->get($name)?->getLine();
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        if (!\is_array($value)) {
            $value = [$value];
        }

        $message = clone $this;
        $message->headerCollection = $this->headerCollection->set($name, $value);

        return $message;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $message = clone $this;
        $message->headerCollection = $this->headerCollection->append($name, (array) $value);

        return $message;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $message = clone $this;
        $message->headerCollection = $this->headerCollection->remove($name);

        return $message;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $message = clone $this;
        $message->body = $body;

        return $message;
    }
}
