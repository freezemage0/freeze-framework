<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel;

use Freeze\Framework\Kernel\Contract\MiddlewareProcessorInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareProcessor implements RequestHandlerInterface, MiddlewareProcessorInterface
{
    /** @var array<array-key, MiddlewareInterface> */
    private array $stack = [];

    public function __construct(
            private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function append(MiddlewareInterface $middleware): void
    {
        $this->stack[] = $middleware;
    }

    public function prepend(MiddlewareInterface $middleware): void
    {
        \array_unshift($this->stack, $middleware);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->stack)) {
            return $this->responseFactory->createResponse()->withProtocolVersion($request->getProtocolVersion());
        }

        $middleware = \array_shift($this->stack);
        $response = $middleware->process($request, $this);
        $this->stack[] = $middleware;

        return $response;
    }
}
