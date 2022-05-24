<?php

declare(strict_types=1);

namespace App\Service\Nbrb;

use App\Domain\ValueObject\Currency;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    public function __construct(private HttpClientInterface $client)
    {

    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getRates(int $id, DateTimeInterface $dateTime): Currency
    {
        $response = $this->client->request(
            'GET',
            "https://www.nbrb.by/api/exrates/rates/{$id}?" . http_build_query(['ondate' => $dateTime->format('Y-m-d')])
        );

        try {
            /**
             * @var array{Cur_ID: int, Date: string, Cur_Abbreviation: string, Cur_OfficialRate: float} $data
             */
            $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

            return Currency::fromPayload(
                $data['Cur_ID'],
                $data['Cur_Abbreviation'],
                $data['Cur_OfficialRate'],
                new \DateTimeImmutable($data['Date'])
            );
        } catch (\Throwable $e) {
        }
    }
}
