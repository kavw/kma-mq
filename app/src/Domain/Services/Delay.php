<?php

namespace App\Domain\Services;

interface Delay
{
    /**
     * @template T
     * @template R
     * @param callable(T):R $cb
     * @param int|null $delay
     * @return R
     */
    public function run(callable $cb, int $delay = null): mixed;
}
