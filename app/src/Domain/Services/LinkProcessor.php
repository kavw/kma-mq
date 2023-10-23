<?php

namespace App\Domain\Services;

use App\Infra\DBAL\Connection;
use App\Infra\DBAL\PDO\Ctx;
use App\Infra\Http\Client\ClientFactory;
use App\Infra\Http\Client\RequestFactory;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;

final readonly class LinkProcessor
{
    public function __construct(
        private Connection $connection,
        private ClientFactory $clientFactory,
        private RequestFactory $requestFactory,
        private ClockInterface $clock,
        private LoggerInterface $logger,
    ) {
    }

    public function process(string $url): void
    {
        $client = $this->clientFactory->create();
        $request = $this->requestFactory->create('GET', $url);

        $this->logger->info("Sending http request", ['request' => $request]);
        $response = $client->sendRequest($request);
        if ($response->getStatusCode() === 500) {
            return;
        }

        $contentLength = (int) $response->getBody()->getSize();
        $this->save($url, $contentLength);
    }

    private function save(string $url, int $contentLength): void
    {
        $this->logger->info("Saving sata", ['url' => $url, 'contentLength' => $contentLength]);

        $now = $this->clock->now()->format('Y-m-d H:i:s');
        $this->connection->insert(
            fn (Ctx $ctx) => yield <<<SQL
            INSERT INTO `links` (`url`, `content_length`, `sent_at`)
                VALUES ({$ctx($url)}, {$ctx($contentLength)}, {$ctx($now)})
            SQL
        )->exec();
    }
}
