<?php

namespace App\Infra\Secrets;

final class EnvSecrets implements SecretsProvider
{
    public function get(string $key): ?string
    {
        return $_ENV[$key] ?? null;
    }
}
