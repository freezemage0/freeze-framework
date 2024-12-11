<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class Response extends Message implements ResponseInterface
{
    public function __construct(
            string $version,
            StreamInterface $body,
            protected int $statusCode = 200,
            protected string $reasonPhrase = '',
    ) {
        parent::__construct($version, $body);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        if ($this->statusCode === $code && $this->reasonPhrase === $reasonPhrase) {
            return $this;
        }

        $response = clone $this;
        $response->statusCode = $code;
        $response->reasonPhrase = $reasonPhrase;

        return $response;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
