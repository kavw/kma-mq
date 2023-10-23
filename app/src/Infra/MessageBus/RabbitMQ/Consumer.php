<?php

namespace App\Infra\MessageBus\RabbitMQ;

use App\Infra\DataFormats;
use App\Infra\MessageBus\MessageHandler;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;

final class Consumer extends Driver
{
    public function __construct(
        ConnectionFactory $connectionFactory,
        readonly private SerializerInterface $serializer,
        readonly private DataFormats $dataFormats,
        /** @var MessageHandler[] */
        readonly private array $handlers,
    ) {
        parent::__construct($connectionFactory);
    }

    public function consume(string $queue, string $exchange, string $consumerTag): void
    {
        $chan = $this->prepare($queue, $exchange);
        $chan->basic_consume(
            queue: $queue,
            consumer_tag: $consumerTag,
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: fn(AMQPMessage $m) => $this->processMessage($m)
        );
        $chan->consume();
    }

    private function processMessage(AMQPMessage $message): void
    {
        if ($message->body === 'quit') {
            $message->ack();
            $message->getChannel()->basic_cancel($message->getConsumerTag());
            return;
        }

        $contentType = $message->get_properties()['content_type'] ?? null;
        if (!$contentType) {
            throw new \RuntimeException("Can't get content type for a message: {$message->body}");
        }

        $headers = $message->get('application_headers')->getNativeData();
        $messageType = $headers[MessageHeaders::MESSAGE_TYPE]
            ?? throw new \RuntimeException("Can't determine message type");

        $appMessage = $this->serializer->deserialize(
            $message->body,
            $messageType,
            $this->dataFormats->getByType($contentType)->name
        );

        $this->handleMessage($appMessage);
        $message->ack();
    }

    private function handleMessage(object $message): void
    {
        $handler = array_filter(
            array_map(fn(MessageHandler $i) => $i->supports($message), $this->handlers),
            fn (?callable $i) => $i !== null
        )[0] ?? null;

        if (!$handler) {
            return;
        }

        $handler($message);
    }
}
