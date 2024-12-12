<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel;

use Freeze\Framework\Kernel\Exception\HeadersAlreadySentException;
use Psr\Http\Message\ResponseInterface;

final class ResponseEmitter implements Contract\ResponseEmitterInterface
{
    public function emit(ResponseInterface $response): void
    {
        if (\headers_sent($filename, $line)) {
            throw new HeadersAlreadySentException("Headers already sent from {$filename}:{$line}");
        }

        \header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}");
        foreach ($response->getHeaders() as $header => $value) {
            $value = \implode(', ', $value);
            \header("{$header}: {$value}");
        }

        echo $response->getBody();
    }
}
