<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareStack implements RequestHandlerInterface
{
    /** @var array<array-key, MiddlewareInterface> */
    private array $stack = [];

    public function push(MiddlewareInterface $middleware): void
    {
        $this->stack[] = $middleware;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = \array_shift($this->stack);
        $response = $middleware->process($request, $this);
        $this->stack[] = $middleware;

        return $response;
    }
}
