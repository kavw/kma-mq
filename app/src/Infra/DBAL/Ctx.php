<?php

namespace App\Infra\DBAL;

interface Ctx
{
    /**
     * @param  scalar ...$params
     * @return string
     */
    public function __invoke(...$params): string;

    /**
     * @return array<string, scalar|array<scalar>>
     */
    public function getParams(): array;
}
