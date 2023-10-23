<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

(function() {
    $env = new Dotenv();
    $env->load(__DIR__ . '/../env/.env');
    if (isset($_ENV['APP_ENV'])) {
        $appEnv = $_ENV['APP_ENV'];
        $path = __DIR__ . "/../env/.{$appEnv}.env";
        if (file_exists($path)) {
            $env->load($path);
        }
    }
})();



