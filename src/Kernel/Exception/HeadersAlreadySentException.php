<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Exception;

use Freeze\Framework\Kernel\Contract\ExceptionInterface;
use RuntimeException;

final class HeadersAlreadySentException extends RuntimeException implements ExceptionInterface
{
}
