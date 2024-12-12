<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Freeze\Framework\Kernel\Message\Request;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class RequestFactory implements RequestFactoryInterface
{
    public function __construct(
            private readonly UriFactoryInterface $uriFactory,
            private readonly StreamFactoryInterface $streamFactory
    ) {
    }

    public function createRequest(string $method, $uri): RequestInterface
    {
        if (\is_string($uri)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        $protocol = $_SERVER['SERVER_PROTOCOL'];
        [$proto, $version] = \explode('/', $protocol);

        return new Request(
                $uri,
                $method,
                $proto === 'HTTP' ? $version : '',
                $this->streamFactory->createStream()
        );
    }
}
