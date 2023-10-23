<?php

namespace App\Domain\Services;

use App\Infra\DBAL\PDO\Connection;
use App\Infra\DBAL\PDO\Ctx;

final readonly class ClickhouseStats
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
                toStartOfMinute(sent_at) AS minute,
                count(id) AS count,
                avg(content_length) AS avg,
                min(sent_at) as min,
                max(sent_at) as max
            FROM links
            GROUP BY minute
            ORDER BY minute DESC
            SQL
        )->all();
    }
}
