<?php

declare(strict_types=1);

namespace App\Infra\DBAL\PDO;

final class Query
{
    private ?\PDOStatement $stmt = null;

    public function __construct(
        readonly private \PDO $conn,
        readonly private string $queryString,
        readonly private Ctx $ctx,
    ) {
    }

    /**
     * @return \PDOStatement
     */
    public function exec(): \PDOStatement
    {
        if ($this->stmt != null) {
            return $this->stmt;
        }

        $stmt = $this->conn->prepare($this->queryString);
        if (!$stmt) {
            throw new \RuntimeException(
                $this->getError()
            );
        }

        $params = $this->ctx->getParams();

        $v = [];
        foreach ($params as $k => $val) {
            $v[$k] = (is_array($val))
                ? implode(', ', array_map(fn ($i) => (string) $i, $val))
                : $val;

            $stmt->bindParam(
                $k,
                $v[$k],
                match ($v[$k]) {
                    is_bool($v[$k]) => \PDO::PARAM_BOOL,
                    is_int($v[$k]) => \PDO::PARAM_INT,
                    is_null($v[$k]) => \PDO::PARAM_NULL,
                    default => \PDO::PARAM_STR
                }
            );
        }

        $result = $stmt->execute();
        if (!$result) {
            throw new \RuntimeException($this->getError());
        }

        return $this->stmt = $stmt;
    }

    public function getLastInsertId(): string
    {
        return $this->conn->lastInsertId();
    }

    public function getAffectedRows(): ?int
    {
        return $this->stmt?->rowCount();
    }

    public function getError(): string
    {
        return implode(' ', $this->conn->errorInfo());
    }
}
