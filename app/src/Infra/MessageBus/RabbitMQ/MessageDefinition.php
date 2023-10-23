<?php

namespace App\Infra\MessageBus\RabbitMQ;

use App\Infra\DataFormats;

final readonly class MessageDefinition
{
    public function __construct(
        public string $type,
        public string $queue,
        public string $exchange,
        public string $format = DataFormats::JSON
    ) {
    }
}
