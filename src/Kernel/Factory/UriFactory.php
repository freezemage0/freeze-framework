<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Factory;

use Freeze\Framework\Kernel\Message\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

final class UriFactory implements UriFactoryInterface
{
    private const URI_PATTERN = '~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~';

    public function createUri(string $uri = ''): UriInterface
    {
        if (!\preg_match(UriFactory::URI_PATTERN, $uri, $matches)) {
            throw new RuntimeException('Unable to parse URI');
        }

        $authority = $matches[4] ?? '';
        $authority = \explode('@', $authority);

        $host = \array_pop($authority);
        if (!empty($authority)) {
            $userinfo = \explode(':', \array_pop($authority));
            $user = \array_shift($userinfo);
            if (!empty($userinfo)) {
                $pass = \array_shift($userinfo);
            }
        }

        $hostInfo = \explode(':', $host);
        $host = \array_shift($hostInfo);
        if (!empty($hostInfo)) {
            $port = (int) \array_shift($hostInfo);
        }

        return new Uri(
                $matches[2] ?? '',
                $host,
                $user ?? '',
                $pass ?? '',
                $port ?? null,
                $matches[5] ?? '',
                $matches[7] ?? '',
                $matches[9] ?? ''
        );
    }
}
