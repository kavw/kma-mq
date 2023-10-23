<?php

namespace App\Infra\DBAL;

interface FetchQuery
{
    /**
     * @return array<string, string>|null
     */
    public function one(): ?array;


    /**
     * @return iterable<array<string, string>>
     */
    public function all(): iterable;
}
