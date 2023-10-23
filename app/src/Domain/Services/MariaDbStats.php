<?php

namespace App\Domain\Services;

use App\Infra\DBAL\PDO\Connection;
use App\Infra\DBAL\PDO\Ctx;

final readonly class MariaDbStats
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function get(): iterable
    {
        return $this->connection->fetch(
            fn (Ctx $ctx) => yield <<<SQL
            SELECT
                DATE_FORMAT(`sent_at`, '%Y-%m-%d %H:%i') AS `minute`,
                COUNT(`id`) AS `count`,
                AVG(`content_length`) AS `avg`,
                MIN(`sent_at`) as `min`,
                MAX(`sent_at`) as `max`
            FROM `links`
            GROUP BY DATE_FORMAT(`sent_at`, '%Y-%m-%d %H:%i')
            ORDER BY `minute` DESC
            SQL
        )->all();
    }
}
