<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\OpenApi;
use App\Service\Nbrb\Client;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route(path: "/test", methods: ["GET", "HEAD"])]
    public function test(): OpenApi
    {
        return OpenApi::fromPayload(
            $this->client->getRates(431, new \DateTimeImmutable('-1 day'))
        );
    }
}
