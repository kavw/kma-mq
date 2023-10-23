<?php

declare(strict_types=1);

use App\Domain\Services\FileLinkProvider;
use App\Domain\Services\LinkPublisher;
use App\Domain\Services\SleepDelay;
use App\Infra\DataFormats;
use App\Infra\LoggerFactory;
use App\Infra\MessageBus\RabbitMQ\ConnectionFactory;
use App\Infra\MessageBus\RabbitMQ\MessageHeaders;
use App\Infra\MessageBus\RabbitMQ\Publisher;
use App\Infra\Secrets\EnvSecrets;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/env.php';

(function() {

    $messageMap = require_once __DIR__ . '/../src/Settings/message_map.php';

    $logger = (new LoggerFactory())->create('kma-link-consumer');

    $bus = new Publisher(
        new ConnectionFactory(
            new EnvSecrets()
        ),
        new Serializer(
            [new ObjectNormalizer()],
            [new JsonEncoder()]
        ),
        new DataFormats(),
        $messageMap,
        new MessageHeaders(),
    );

    $publisher = new LinkPublisher(
        new FileLinkProvider(__DIR__ . '/../data/links.txt'),
        $bus,
        $logger,
        new SleepDelay(10, 100)
    );

    $publisher->publish();

})();




