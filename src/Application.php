<?php

declare(strict_types=1);

namespace Freeze\Framework;

use Freeze\Framework\Kernel\MiddlewareStack;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

final class Application
{
    public function __construct(
            private readonly ServerRequestFactoryInterface $serverRequestFactory,
            private readonly MiddlewareStack $stack
    ) {
    }

    final public function prependMiddleware(MiddlewareInterface $middleware): Application
    {
        $this->stack->prepend($middleware);
        return $this;
    }

    final public function appendMiddleware(MiddlewareInterface $middleware): Application
    {
        $this->stack->append($middleware);
        return $this;
    }

    final public function run(): void
    {
        $response = $this->stack->handle(
                $this->serverRequestFactory->createServerRequest(
                        $_SERVER['REQUEST_METHOD'],
                        $_SERVER['REQUEST_URI'],
                        $_SERVER
                )
        );

        \header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}");
        foreach ($response->getHeaders() as $header => $value) {
            $value = \implode(', ', $value);
            \header("{$header}: {$value}");
        }

        echo $response->getBody();
    }
}
