<?php
declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class UserLastName extends StringValueObject
{
    private const LASTNAME_MAX_CHARACTERS = 50;
    private const LASTNAME_MIN_CHARACTERS = 2;
    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinLength($value,self::LASTNAME_MIN_CHARACTERS);
        $this->ensureMaxLength($value,self::LASTNAME_MAX_CHARACTERS);
        parent::__construct($value);
    }
}