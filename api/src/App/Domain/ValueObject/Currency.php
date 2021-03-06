<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTimeImmutable;
use JsonSerializable;

final class Currency implements JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $abbreviation,
        private readonly float $rate,
        private readonly DateTimeImmutable $date
    ) {
    }

    public static function fromPayload(
        int $id,
        string $abbreviation,
        float $rate,
        DateTimeImmutable $date
    ): self {
        return new self($id, $abbreviation, $rate, $date);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'abbreviation' => $this->abbreviation,
            'rate' => $this->rate,
            'date' => $this->date,
        ];
    }
}
