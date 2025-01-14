<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareProcessorInterface extends RequestHandlerInterface
{
    public function append(MiddlewareInterface $middleware): void;

    public function prepend(MiddlewareInterface $middleware): void;
}
