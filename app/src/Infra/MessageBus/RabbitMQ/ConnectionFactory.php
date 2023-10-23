<?php

namespace App\Infra\MessageBus\RabbitMQ;

use App\Infra\Secrets\SecretsProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final readonly class ConnectionFactory
{
    public function __construct(
        private SecretsProvider $secrets
    ) {
    }

    public function create(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $this->secrets->get('RABBITMQ_HOST') ?? 'rabbitmq',
                (int) $this->secrets->get('RABBITMQ_PORT') ?? 5672,
                $this->secrets->get('RABBITMQ_USER') ?? throw new \RuntimeException("Needs RABBITMQ_USER"),
                $this->secrets->get('RABBITMQ_PASS') ?? throw new \RuntimeException("Needs RABBITMQ_PASS"),
        );
    }
}
