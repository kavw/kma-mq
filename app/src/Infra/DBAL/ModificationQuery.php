<?php

namespace App\Infra\DBAL;

interface ModificationQuery
{
    public function exec(): mixed;
}
