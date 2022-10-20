<?php

declare(strict_types=1);

namespace App\Domain\DeliveryCompany\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class DeliveryCompanyName extends StringValueObject
{
    private const COMPANY_NAME_MAX_CHARACTERS = 50;
    private const COMPANY_NAME_MIN_CHARACTERS = 2;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinLength($value,self::COMPANY_NAME_MIN_CHARACTERS);
        $this->ensureMaxLength($value,self::COMPANY_NAME_MAX_CHARACTERS);
        parent::__construct($value);
    }

}