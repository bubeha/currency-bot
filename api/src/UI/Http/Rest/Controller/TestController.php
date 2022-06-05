<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller;

use App\Service\Nbrb\Client;
use DateTimeImmutable;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use UI\Http\Rest\Response\OpenApi;

final class TestController
{
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @throws Throwable
     */
    #[Route(path: '/test', methods: ['GET', 'HEAD'])]
    public function test(): OpenApi
    {
        return OpenApi::fromPayload(
            $this->client->getRates(431, new DateTimeImmutable('+2 day'))
        );
    }
}
