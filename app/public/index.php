<?php

declare(strict_types=1);

use App\Domain\Services\ClickhouseStats;
use App\Domain\Services\MariaDbStats;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Infra\DBAL\PDO\Connection;
use App\Infra\Secrets\EnvSecrets;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/env.php';

(function() {

    $loader = new FilesystemLoader(__DIR__ . '/../src/templates');
    $twig = new Environment($loader, [
        'cache' => __DIR__ . '/../var/twig',
        'debug' => (bool) $_ENV['APP_DEBUG']
    ]);

    $secrets = new EnvSecrets();

    $mariaDbStats = new MariaDbStats(
        new Connection(
            $secrets,
            'MARIADB_DSN',
            'MARIADB_USER',
            'MARIADB_PASSWORD',
        ),
    );

    $clickhouseStats = new ClickhouseStats(
        new Connection(
            $secrets,
            'CLICKHOUSE_DSN',
            'CLICKHOUSE_USER',
            'CLICKHOUSE_PASSWORD',
        ),
    );

    $template = $twig->load('index.twig');
    echo $template->render([
        'mariaDbStats' => $mariaDbStats->get(),
        'clickhouseStats' => $clickhouseStats->get(),
    ]);

})();

