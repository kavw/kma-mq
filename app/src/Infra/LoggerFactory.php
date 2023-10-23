<?php

namespace App\Infra;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public function create(string $name): LoggerInterface
    {
        $logger = new Logger('link-consumer');
        $stream = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'wb');
        $logger->pushHandler(
            new StreamHandler($stream, $_ENV['APP_DEBUG'] ? Level::Debug : Level::Info)
        );
        return $logger;
    }
}
