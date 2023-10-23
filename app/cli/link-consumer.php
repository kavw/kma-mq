<?php

declare(strict_types=1);

use App\Domain\MessageBus\LinksMessageHandler;
use App\Domain\Services\LinkProcessor;
use App\Infra\Clock;
use App\Infra\DataFormats;
use App\Infra\DBAL\PDO\Connection;
use App\Infra\Http\Client\ClientFactory;
use App\Infra\Http\Client\RequestFactory;
use App\Infra\LoggerFactory;
use App\Infra\MessageBus\RabbitMQ\ConnectionFactory;
use App\Infra\MessageBus\RabbitMQ\Consumer;
use App\Infra\Secrets\EnvSecrets;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/env.php';
require_once __DIR__ . '/../src/Settings/message_map.php';

(function() {
    $consumerTag = 'consumer';

    $logger = (new LoggerFactory())->create('kma-link-consumer');

    $secrets = new EnvSecrets();

    $consumer = new Consumer(
        new ConnectionFactory(
            $secrets
        ),
        new Serializer(
            [new ObjectNormalizer()],
            [new JsonEncoder()]
        ),
        new DataFormats(),
        [
            new LinksMessageHandler(
                new LinkProcessor(
                    new Connection(
                        $secrets,
                        'MARIADB_DSN',
                        'MARIADB_USER',
                        'MARIADB_PASSWORD',
                    ),
                    new ClientFactory(),
                    new RequestFactory(),
                    new Clock(),
                    $logger
                ),
                $logger
            ),
        ]
    );

    $consumer->consume(
        QUEUE_LINKS,
        EXCHANGE_ROUTER,
        $consumerTag
    );

})();

