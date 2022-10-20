<?php

declare(strict_types=1);

namespace App\Domain\Shared\DateTime;

class DateTimeProvider
{
    public function getCurrentDate(): \DateTime{
        return new \DateTime();
    }
}