<?php

namespace App\Infra\DBAL\PDO;

use App\Infra\DBAL\FetchQuery;
use App\Infra\DBAL\ModificationQuery;
use App\Infra\Secrets\SecretsProvider;

final class Connection implements \App\Infra\DBAL\Connection
{
    private ?\PDO $conn = null;

    public function __construct(
        readonly private SecretsProvider $secrets,
        readonly string $dsnKey,
        readonly string $userKey,
        readonly string $passKey
    ) {
    }

    public function fetch(callable $cb): FetchQuery
    {
        return new \App\Infra\DBAL\PDO\FetchQuery($this->makeQuery($cb));
    }

    public function insert(callable $cb): ModificationQuery
    {
        return new InsertQuery($this->makeQuery($cb));
    }

    private function makeQuery(callable $cb): Query
    {
        $context = new Ctx();
        $queryString = [];
        foreach ($cb($context) as $str) {
            $queryString[] = $str;
        }

        return new Query(
            $this->getConnection(),
            implode(' ', $queryString),
            $context,
        );
    }

    private function getConnection(): \PDO
    {
        if ($this->conn) {
            return $this->conn;
        }

        return $this->conn = new \PDO(
            $this->secrets->get($this->dsnKey),
            $this->secrets->get($this->userKey),
            $this->secrets->get($this->passKey),
        );
    }
}
