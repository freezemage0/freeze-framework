<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RoutingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $path = $request->getUri()->getPath();
        if ($path !== '/') {
            return $response->withStatus(404, 'Not Found');
        }

        $response->getBody()->write('Hello World!');

        return $response;
    }
}
