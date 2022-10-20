<?php

declare(strict_types=1);

namespace App\Domain\DeliveryCompany\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class DeliveryCompanyVIPLevel extends StringValueObject
{
    public const BASE = "BASE";
    public const BRONZE = "BRONZE";
    public const SILVER = "SILVER";
    public const GOLD = "GOLD";
    private array $values = [self::BASE,self::BRONZE,self::SILVER,self::GOLD];

    /**
     * @throws DomainException
     */
    public function __construct(string $value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureValueIsInArray($value, $this->values);
        parent::__construct($value);
    }

}