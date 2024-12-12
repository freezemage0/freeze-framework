<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Contract;

use Psr\Http\Message\ResponseInterface;

interface ResponseEmitterInterface
{
    public function emit(ResponseInterface $response): void;
}
