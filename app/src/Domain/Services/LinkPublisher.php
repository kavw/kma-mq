<?php

namespace App\Domain\Services;

use App\Domain\MessageBus\LinkMessage;
use App\Infra\MessageBus\MessageBus;
use Psr\Log\LoggerInterface;

final readonly class LinkPublisher
{
    public function __construct(
        private LinkProvider $linksProvider,
        private MessageBus $bus,
        private LoggerInterface $logger,
        private Delay $delay,
    ) {
    }

    public function publish(): void
    {
        $this->logger->info("Start looking for links");

        foreach ($this->linksProvider->getLinks() as $link) {
            $message = new LinkMessage($link);
            $this->delay->run(function () use ($message) {
                $this->logger->info("Dispatch message", ['message' => $message]);
                $this->bus->dispatch($message);
            });
        }

        $this->logger->info("Finish");
    }
}
