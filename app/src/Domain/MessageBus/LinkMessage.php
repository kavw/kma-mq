<?php

declare(strict_types=1);

namespace App\Domain\MessageBus;

final readonly class LinkMessage
{
    public function __construct(
        public string $url
    ) {
    }
}
