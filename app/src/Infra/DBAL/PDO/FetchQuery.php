<?php

namespace App\Infra\DBAL\PDO;

class FetchQuery implements \App\Infra\DBAL\FetchQuery
{
    /**
     * @var array<array<string, string>>|null
     */
    private ?array $result = null;

    public function __construct(
        readonly private Query $query
    ) {
    }

    /**
     * @return array<string, string>|null
     */
    public function one(): ?array
    {
        $res = $this->getResult();
        return $res[0] ?? null;
    }

    /**
     * @return iterable<array<string, string>>
     */
    public function all(): iterable
    {
        foreach ($this->getResult() as $row) {
            yield $row;
        }
    }

    /**
     * @return array<array<string, string>>
     */
    private function getResult(): array
    {
        if ($this->result !== null) {
            return $this->result;
        }

        $result = $this->query->exec()
            ->fetchAll(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new \RuntimeException($this->query->getError());
        }

        return $this->result = $result;
    }
}
