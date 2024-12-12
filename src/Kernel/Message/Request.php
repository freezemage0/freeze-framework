<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    protected ?string $target = null;

    public function __construct(
            protected Uri $uri,
            protected string $method,
            string $version,
            StreamInterface $body
    ) {
        parent::__construct($version, $body);
    }

    public function getRequestTarget(): string
    {
        return $this->target ?? $this->uri->getPath();
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if ($this->target === $requestTarget) {
            return $this;
        }

        $request = clone $this;
        $request->target = $requestTarget;

        return $request;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        if ($this->method === $method) {
            return $this;
        }

        $request = clone $this;
        $request->method = $method;

        return $request;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $request = clone $this;

        if (!$uri->getHost()) { // Host is not present in URI, do not modify Host header.
            $request->uri = $uri;
            return $request;
        }

        // Host is present in URI
        if (!$preserveHost || !$request->hasHeader('Host')) {
            $request->headerCollection = $this->headerCollection->set('Host', [$uri->getHost()]);
        }

        return $request;
    }
}
