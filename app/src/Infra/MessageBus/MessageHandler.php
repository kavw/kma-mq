<?php

namespace App\Infra\MessageBus;

interface MessageHandler
{
    /**
     * @template T
     * @param object<T> $message
     * @return callable(T):void|null
     */
    public function supports(object $message): ?callable;
}
