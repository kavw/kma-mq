<?php

namespace App\Infra\DBAL\PDO;

final class Ctx implements \App\Infra\DBAL\Ctx
{
    /**
     * @var array<string, scalar|array<scalar>>
     */
    private array $params = [];

    /**
     * @param  scalar ...$params
     * @return string
     */
    public function __invoke(...$params): string
    {
        $name = ':p' . \count($this->params);
        $this->params[$name] = \count($params) === 1 ? $params[0] : $params;
        return $name;
    }

    /**
     * @return array<string, scalar|array<scalar>>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
