<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class ClientFactory implements ClientFactoryInterface
{
    public function create(
        float $timeout = 3.14,
        bool $debug = false
    ): ClientInterface
    {
        return new Client([
            'timeout' => $timeout,
            'debug' => $debug
        ]);
    }
}
