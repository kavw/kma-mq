<?php

declare(strict_types=1);

require_once __DIR__.'/../../vendor/autoload.php';

const QUEUE_LINKS = 'links';
const EXCHANGE_ROUTER = 'headers_router';

return (new \App\Infra\MessageBus\RabbitMQ\MessageMap())
    ->add(new \App\Infra\MessageBus\RabbitMQ\MessageDefinition(
        type: \App\Domain\MessageBus\LinkMessage::class,
        queue: QUEUE_LINKS,
        exchange: EXCHANGE_ROUTER,
    ))
    ;
