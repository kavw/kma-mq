<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{
    public function create(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
