<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Freeze\Framework\Kernel\Message\ServerRequest;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function __construct(
            private readonly UriFactoryInterface $uriFactory,
            private readonly StreamFactoryInterface $streamFactory
    ) {
    }

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (\is_string($uri)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        $protocol = \explode('/', $serverParams['SERVER_PROTOCOL'] ?? '');
        $version = $protocol[1] ?? '';

        return new ServerRequest(
                server: $serverParams,
                cookies: $_COOKIE,
                query: $_GET,
                uri: $uri,
                method: $method,
                version: $version,
                body: $this->streamFactory->createStream()
        );
    }
}
