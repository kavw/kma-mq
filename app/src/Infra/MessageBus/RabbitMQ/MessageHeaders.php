<?php

namespace App\Infra\MessageBus\RabbitMQ;

use PhpAmqpLib\Wire\AMQPTable;

final class MessageHeaders
{
    public const MESSAGE_TYPE = 'message_type';

    public function create(
        string $messageType
    ): AMQPTable
    {
        return new AMQPTable([
            self::MESSAGE_TYPE => $messageType
        ]);
    }
}
