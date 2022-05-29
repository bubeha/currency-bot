<?php

declare(strict_types=1);

namespace App\Service\Nbrb;

use App\Domain\ValueObject\Currency;
use App\Service\Nbrb\Exception\NotFoundException;
use DateTimeImmutable;
use DateTimeInterface;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function http_build_query;
use function json_decode;

final class Client
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    /**
     * @throws JsonException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Exception
     */
    public function getRates(int $id, DateTimeInterface $date): Currency
    {
        $response = $this->client->request(
            'GET',
            "https://www.nbrb.by/api/exrates/rates/{$id}?" . http_build_query(['date' => $date->format('Y-m-d')])
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw NotFoundException::fromPayload($date);
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
