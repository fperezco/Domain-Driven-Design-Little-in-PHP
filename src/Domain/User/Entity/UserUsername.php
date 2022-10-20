<?php
declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class UserUsername extends StringValueObject
{
    private const USERNAME_MAX_CHARACTERS = 50;
    private const USERNAME_MIN_CHARACTERS = 8;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinLength($value,self::USERNAME_MIN_CHARACTERS);
        $this->ensureMaxLength($value,self::USERNAME_MAX_CHARACTERS);
        parent::__construct($value);
    }


}