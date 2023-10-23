<?php

namespace App\Infra\MessageBus\RabbitMQ;

final class MessageMap
{
    /**
     * @var array<string, MessageDefinition>
     */
    private array $map = [];

    public function add(MessageDefinition $definition): self
    {
        if (isset($this->map[$definition->type])) {
            throw new \LogicException(
                "Definition for the type {$definition->type} has been added already"
            );
        }

        $this->map[$definition->type] = $definition;
        return $this;
    }

    public function get(string|object $type): ?MessageDefinition
    {
        $type = is_string($type) ?: get_class($type);
        return $this->map[$type] ?? null;
    }
}
