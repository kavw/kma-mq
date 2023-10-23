<?php

namespace App\Infra\MessageBus\RabbitMQ;

use App\Infra\DataFormats;
use App\Infra\MessageBus\MessageBus;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;

final class Publisher extends Driver implements MessageBus
{
    public function __construct(
        ConnectionFactory $connectionFactory,
        readonly private SerializerInterface $serializer,
        readonly private DataFormats $dataFormats,
        readonly private MessageMap $map,
        readonly private MessageHeaders $headersFactory,
    ) {
        parent::__construct($connectionFactory);
    }

    public function dispatch(object $message): void
    {
        $definition = $this->map->get($message);
        if (!$definition) {
            $type = get_class($message);
            throw new \RuntimeException("There is no definition for message type {$type}");
        }

        $data = $this->serializer->serialize($message, $definition->format);
        $format = $this->dataFormats->getByName($definition->format);
        $message = new AMQPMessage($data, [
            'content_type' => $format->contentType,
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $headers = $this->headersFactory->create($definition->type);
        $message->set('application_headers', $headers);

        $chan = $this->prepare($definition->queue, $definition->exchange);
        $chan->basic_publish($message, $definition->exchange);
    }
}
