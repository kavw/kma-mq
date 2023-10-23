<?php

namespace App\Domain\Services;

interface LinkProvider
{
    /**
     * @return iterable<string>
     */
    public function getLinks(): iterable;
}
