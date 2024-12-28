<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

final class Uri implements UriInterface
{
    private ?string $uri = null;

    private const MAX_PORT_VALUE = 65356;

    private const HTTP_PORT = 80;
    private const HTTPS_PORT = 443;

    private const STANDARD_PORTS = [
            'http' => Uri::HTTP_PORT,
            'https' => Uri::HTTPS_PORT,
    ];

    public function __construct(
            private string $scheme,
            private string $host,
            private string $user,
            private string $pass,
            private ?int $port,
            private string $path,
            private string $query,
            private string $fragment
    ) {
    }

    public function __clone(): void
    {
        $this->uri = null;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->getUserInfo();
        if (!empty($authority)) {
            $authority .= '@';
        }
        $authority .= $this->host;

        $port = $this->getPort();
        if ($port !== null) {
            $authority .= ':' . $port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        $userInfo = $this->encode($this->user);
        if ($this->pass !== '') {
            $userInfo .= ':' . $this->encode($this->pass);
        }

        return $userInfo;
    }

    public function getHost(): string
    {
        return \strtolower($this->host);
    }

    public function getPort(): ?int
    {
        $standardPort = Uri::STANDARD_PORTS[$this->getScheme()] ?? null;

        if ($this->port === null || $standardPort === $this->port) {
            return null;
        }

        return $this->port;
    }

    public function getPath(): string
    {
        $path = \implode('/', \array_map($this->encode(...), \explode('/', $this->path)));

        if (\str_starts_with($path, '/')) {
            $path = '/' . \ltrim($path, '/');
        }

        return $path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): UriInterface
    {
        if (\is_numeric($scheme)) {
            throw new InvalidArgumentException();
        }

        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $uri = clone $this;
        $uri->user = $user;

        if ($uri->user === '') {
            $uri->pass = '';
            return $uri;
        }

        if ($password !== null) {
            $uri->pass = $password;
        }

        return $uri;
    }

    public function withHost(string $host): UriInterface
    {
        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    public function withPort(?int $port): UriInterface
    {
        if ($port < 0 || $port > Uri::MAX_PORT_VALUE) {
            throw new InvalidArgumentException('Invalid port value');
        }

        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    public function withPath(string $path): UriInterface
    {
        $path = \implode(
                '/',
                \array_map(
                        $this->encode(...),
                        \explode('/', $path)
                )
        );

        $uri = clone $this;
        $uri->path = $path;

        return $uri;
    }

    public function withQuery(string $query): UriInterface
    {
        $uri = clone $this;
        $uri->query = $query;

        return $uri;
    }

    public function withFragment(string $fragment): UriInterface
    {
        $uri = clone $this;
        $uri->fragment = $fragment;

        return $uri;
    }

    public function __toString(): string
    {
        if ($this->uri === null) {
            $uri = '';
            $scheme = $this->getScheme();
            if ($scheme !== '') {
                $uri .= $scheme . ':';
            }

            $authority = $this->getAuthority();

            if ($authority !== '') {
                $uri .= '//' . $authority;
            }

            $path = $this->path;
            if (!\str_starts_with($path, '/')) {
                $path .= '/' . $path;
            }

            $uri .= $path;

            $query = $this->getQuery();
            if ($query !== '') {
                $uri .= '?' . $query;
            }

            $fragment = $this->getFragment();
            if ($fragment !== '') {
                $uri .= '#' . $fragment;
            }

            $this->uri = $uri;
        }

        return $this->uri;
    }

    private function encode(string $string): string
    {
        return \preg_match('/[^A-Za-z0-9-_\.~%]/', $string) ? \rawurlencode($string) : $string;
    }
}
