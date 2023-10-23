<?php

namespace App\Domain\Services;

final readonly class SleepDelay implements Delay
{
    public function __construct(
        private int $min,
        private int $max
    ) {
    }

    /**
     * @template T
     * @template R
     * @param callable(T):R $cb
     * @param int|null $delay
     * @return R
     */
    public function run(callable $cb, int $delay = null): mixed
    {
        $delay = $delay ?? mt_rand($this->min, $this->max);
        sleep($delay);
        return $cb();
    }
}
