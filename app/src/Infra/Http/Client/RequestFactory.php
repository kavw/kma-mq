<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(
        string $method,
        string|UriInterface $uri,
        array $headers = [],
        mixed $body = null,
        string $version = '1.1'
    ): RequestInterface {
        return new Request(
            $method,
            $uri,
            $headers,
            $body,
            $version
        );
    }
}
