<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Freeze\Framework\Kernel\Message\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private readonly StreamFactoryInterface $streamFactory
    ) {
    }

    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response(
            '',
            $this->streamFactory->createStream(),
            $code,
            $reasonPhrase
        );
    }
}
