<?php

namespace App\Infra\DBAL\PDO;

use App\Infra\DBAL\ModificationQuery;

final class InsertQuery implements ModificationQuery
{
    private ?string $lastInsertId = null;

    public function __construct(
        readonly private Query $query
    ) {
    }

    public function exec(): string
    {
        if ($this->lastInsertId !== null) {
            return $this->lastInsertId;
        }

        $this->query->exec();
        return $this->lastInsertId = $this->query->getLastInsertId();
    }
}
