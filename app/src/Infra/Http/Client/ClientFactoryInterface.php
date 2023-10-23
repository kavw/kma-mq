<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use Psr\Http\Client\ClientInterface;

interface ClientFactoryInterface
{
    public function create(): ClientInterface;
}
