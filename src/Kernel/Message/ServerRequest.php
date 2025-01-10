<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class ServerRequest extends Request implements ServerRequestInterface
{
    /** @var array<array-key, mixed> */
    private array             $attributes = [];
    /**
     * @var array<array-key, mixed>|object|null
     */
    private array|null|object $parsedBody;
    /**
     * @var array<UploadedFile>
     */
    private array             $uploadedFiles = [];

    /**
     * @param array<string, mixed> $server
     * @param array<array-key, mixed> $cookies
     * @param array<array-key, mixed> $query
     * @param UriInterface $uri
     * @param string $method
     * @param string $version
     * @param StreamInterface $body
     */
    public function __construct(
        private readonly array $server,
        private array $cookies,
        private array $query,
        UriInterface $uri,
        string $method,
        string $version,
        StreamInterface $body
    ) {
        parent::__construct($uri, $method, $version, $body);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * @param array<array-key, mixed> $cookies
     * @return ServerRequestInterface
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->cookies = $cookies;

        return $serverRequest;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * @param array<array-key, mixed> $query
     * @return ServerRequestInterface
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->query = $query;

        return $serverRequest;
    }

    /**
     * @return UploadedFile[]
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * @param array<UploadedFile> $uploadedFiles
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->uploadedFiles = $uploadedFiles;

        return $serverRequest;
    }

    /**
     * @return object|array<array-key, mixed>|null
     */
    public function getParsedBody(): object|array|null
    {
        return $this->parsedBody;
    }

    /**
     * @param array<array-key, mixed>|object|null $data
     * @return ServerRequestInterface
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        $serverRequest = clone $this;
        $serverRequest->parsedBody = $data;

        return $serverRequest;
    }

    /**
     * @return array<array-key, mixed>
     */
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
