<?php

declare(strict_types=1);

namespace App\Service\Nbrb;

use App\Domain\ValueObject\Currency;
use App\Service\Nbrb\Exception\NotFoundException;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function http_build_query;
use function json_decode;

final class Client
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * @throws Throwable
     */
    public function getRates(string $currency, DateTimeInterface $date): Currency
    {
        $response = $this->client->request(
            'GET',
            "https://www.nbrb.by/api/exrates/rates/{$currency}?" . http_build_query([
                'parammode' => 2,
                'ondate' => $date->format('Y-m-d'),
            ])
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw NotFoundException::fromDate($date);
        }

        /**
         * @var array{Cur_ID: int, Date: string, Cur_Abbreviation: string, Cur_OfficialRate: float} $data
         */
        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return Currency::fromPayload(
            $data['Cur_ID'],
            $data['Cur_Abbreviation'],
            $data['Cur_OfficialRate'],
            new DateTimeImmutable($data['Date'])
        );
    }
}
