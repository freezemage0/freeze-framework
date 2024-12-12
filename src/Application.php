<?php

declare(strict_types=1);

namespace Freeze\Framework;

use Freeze\Framework\Kernel\Contract\MiddlewareProcessorInterface;
use Freeze\Framework\Kernel\Contract\ResponseEmitterInterface;
use Freeze\Framework\Kernel\Factory\ResponseFactory;
use Freeze\Framework\Kernel\Factory\StreamFactory;
use Freeze\Framework\Kernel\MiddlewareProcessor;
use Freeze\Framework\Kernel\ResponseEmitter;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

final class Application
{
    public function __construct(
            private readonly ServerRequestFactoryInterface $serverRequestFactory,
            private readonly MiddlewareProcessorInterface $processor = new MiddlewareProcessor(new ResponseFactory(new StreamFactory())),
            private readonly ResponseEmitterInterface $responseEmitter = new ResponseEmitter()
    ) {
    }

    final public function prependMiddleware(MiddlewareInterface $middleware): Application
    {
        $this->processor->prepend($middleware);
        return $this;
    }

    final public function appendMiddleware(MiddlewareInterface $middleware): Application
    {
        $this->processor->append($middleware);
        return $this;
    }

    final public function run(): void
    {
        $response = $this->processor->handle(
                $this->serverRequestFactory->createServerRequest(
                        $_SERVER['REQUEST_METHOD'],
                        $_SERVER['REQUEST_URI'],
                        $_SERVER
                )
        );

        $this->responseEmitter->emit($response);
    }
}
