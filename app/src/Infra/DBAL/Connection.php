<?php

namespace App\Infra\DBAL;

interface Connection
{
    public function fetch(callable $cb): FetchQuery;

    public function insert(callable $cb): ModificationQuery;
}
