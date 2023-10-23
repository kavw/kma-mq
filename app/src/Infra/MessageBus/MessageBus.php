<?php

namespace App\Infra\MessageBus;

interface MessageBus
{
    public function dispatch(object $message): void;
}
