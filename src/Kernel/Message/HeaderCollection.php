<?php

declare(strict_types=1);

namespace Freeze\Framework\Kernel\Message;

use InvalidArgumentException;

final class HeaderCollection
{
    /**
     * @param array<int, Header> $headers
     */
    public function __construct(private readonly array $headers = [])
    {
    }

    /**
     * Sets a new value (overriding the previous value) in a collection.
     * Performs bare-minimum copying of a collection if header was found.
     * Returns the original instance if header wasn't found.
     *
     * @param string $name
     * @param array<scalar> $value
     * @return HeaderCollection
     */
    public function set(string $name, array $value): HeaderCollection
    {
        if (empty($name) || empty($value)) {
            throw new InvalidArgumentException('Header name or value is invalid.');
        }

        foreach ($value as $v) {
            if (!\is_string($v) && !\is_int($value)) {
                throw new InvalidArgumentException('Header name or value is invalid.');
            }
        }

        $headers = $this->headers;
        foreach ($headers as $index => $header) {
            if (\strcasecmp($header->name, $name) === 0) {
                $headers[$index] = new Header($header->name, $value);

                return new HeaderCollection($headers);
            }
        }

        $headers[] = new Header($name, $value);
        return new HeaderCollection($headers);
    }

    /**
     * @param string $name
     * @param array<scalar> $value
     * @return HeaderCollection
     */
    public function append(string $name, array $value): HeaderCollection
    {
        if (empty($name) || empty($value)) {
            throw new InvalidArgumentException('Header name or value is invalid.');
        }

        foreach ($value as $v) {
            if (!\is_string($v) && !\is_int($value)) {
                throw new InvalidArgumentException('Header name or value is invalid.');
            }
        }

        $headers = $this->headers;

        foreach ($headers as $index => $header) {
            if (\strcasecmp($header->name, $name) === 0) {
                $headers[$index] = new Header($header->name, \array_merge(
                        \array_values($header->value),
                        \array_values($value)
                ));

                return new HeaderCollection($headers);
            }
        }

        $headers[] = new Header($name, $value);
        return new HeaderCollection($headers);
    }

    public function remove(string $name): HeaderCollection
    {
        $headers = $this->headers;

        foreach ($headers as $index => $header) {
            if (\strcasecmp($header->name, $name) === 0) {
                unset($headers[$index]);
                return new HeaderCollection($headers);
            }
        }

        return $this;
    }

    public function get(string $name): ?Header
    {
        foreach ($this->headers as $header) {
            if (\strcasecmp($header->name, $name) === 0) {
                return $header;
            }
        }

        return null;
    }

    /**
     * @return array<string, array<scalar>>
     */
    public function toArray(): array
    {
        $headers = [];
        foreach ($this->headers as $header) {
            $headers[$header->name] = $header->value;
        }

        return $headers;
    }
}
