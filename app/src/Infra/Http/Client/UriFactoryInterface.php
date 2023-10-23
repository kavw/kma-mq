<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use Psr\Http\Message\UriInterface;

interface UriFactoryInterface
{
    public function create(string $uri = ''): UriInterface;
}
