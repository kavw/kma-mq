<?php

namespace App\Domain\MessageBus;

use App\Domain\Services\LinkProcessor;
use App\Infra\MessageBus\MessageHandler;
use Psr\Log\LoggerInterface;

final readonly class LinksMessageHandler implements MessageHandler
{
    public function __construct(
        private LinkProcessor $linkProcessor,
        private LoggerInterface $logger
    ) {
    }

    public function supports(object $message): ?callable
    {
        return $message instanceof LinkMessage ? $this : null;
    }

    public function __invoke(LinkMessage $message): void
    {
        $this->logger->info("Link handler has gotten a message", ['message' => $message]);
        $this->linkProcessor->process($message->url);
    }
}
