<?php

declare(strict_types=1);

namespace UI\Http\Rest\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class OpenApi extends JsonResponse
{
    protected $charset = 'UTF-8';

    private function __construct(mixed $data = null, int $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct($data, $status, $headers);
    }

    public static function fromPayload(mixed $data = null, int $status = self::HTTP_OK, array $headers = []): self
    {
        return new self($data, $status, $headers);
    }
}
