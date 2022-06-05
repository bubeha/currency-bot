<?php

declare(strict_types=1);

namespace App\Service\Nbrb\Exception;

use DateTimeInterface;
use RuntimeException;

final class NotFoundException extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromDate(DateTimeInterface $dateTime): self
    {
        return new self("Can't find current for that day: {$dateTime->format('Y-m-d H:i:s')}");
    }
}
