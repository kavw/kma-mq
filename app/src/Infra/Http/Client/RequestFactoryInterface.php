<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

interface RequestFactoryInterface
{
    /**
     * @param string $method
     * @param string|UriInterface $uri
     * @param array<string, string> $headers
     * @param StreamInterface|resource|string|null $body
     * @param string $version
     * @return RequestInterface
     */
    public function create(
        string $method,
        string|UriInterface $uri,
        array $headers = [],
        mixed $body = null,
        string $version = '1.1'
    ): RequestInterface;
}
