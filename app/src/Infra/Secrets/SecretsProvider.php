<?php

namespace App\Infra\Secrets;

interface SecretsProvider
{
    public function get(string $key): ?string;
}
