<?php

namespace App\Infra\MessageBus\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Symfony\Component\Serializer\SerializerInterface;

abstract class Driver
{
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    /**
     * @var array<string, true>
     */
    private array $queues = [];

    /**
     * @var array<string, true>
     */
    private array $exchanges = [];

    /**
     * @var array<array<string, string>>
     */
    private array $bindings = [];

    protected function __construct(
        readonly private ConnectionFactory $connectionFactory,
    ) {
    }

    protected function prepare(string $queue, string $exchange): AMQPChannel
    {
        $chan = $this->chan();
        if (!isset($this->queues[$queue])) {
            $chan->queue_declare(
                queue: $queue,
                passive: false,
                durable: true,
                exclusive: false,
                auto_delete: false
            );

            $this->queues[$queue] = true;
        }

        if (!isset($this->exchanges[$exchange])) {
            $chan->exchange_declare(
                exchange: $exchange,
                type: AMQPExchangeType::HEADERS,
                passive: false,
                durable: true,
                auto_delete: false
            );

            $this->queues[$exchange] = true;
        }

        if (!array_filter($this->bindings, fn(array $i) => $i[0] === $queue && $i[1] === $exchange)) {
            $chan->queue_bind($queue, $exchange);
        }

        return $chan;
    }

    protected function chan(): AMQPChannel
    {
        if (!$this->channel) {
            $this->connection = $this->connectionFactory->create();
            $this->channel = $this->connection->channel();
        }

        return $this->channel;
    }

    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
            $this->connection->close();
        }
    }
}
