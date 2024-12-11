<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

final class ServerRequest extends Request implements ServerRequestInterface
{
    private array             $attributes;
    private array|null|object $parsedBody;
    private array             $uploadedFiles = [];

    public function __construct(
            private readonly array $server,
            private array $cookies,
            private array $query,
            string $target,
            string $method,
            string $version,
            StreamInterface $body
    ) {
        parent::__construct($target, $method, $version, $body);
    }

    public function getServerParams(): array
    {
        return $this->server;
    }

    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->cookies = $cookies;

        return $serverRequest;
    }

    public function getQueryParams(): array
    {
        return $this->query;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->query = $query;

        return $serverRequest;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->uploadedFiles = $uploadedFiles;

        return $serverRequest;
    }

    public function getParsedBody(): object|array|null
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->parsedBody = $data;

        return $serverRequest;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->attributes[$name] = $value;

        return $serverRequest;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $serverRequest = clone $this;

        $index = \array_search($name, $serverRequest->attributes, true);
        if ($index !== false) {
            unset($serverRequest->attributes[$index]);
        }

        return $serverRequest;
    }
}
