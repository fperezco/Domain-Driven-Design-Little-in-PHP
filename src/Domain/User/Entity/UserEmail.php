<?php
declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\Shared\ValueObject\StringValueObject;

final class UserEmail extends StringValueObject
{
    private const EMAIL_MAX_CHARACTERS = 50;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureValidEmailFormat($value);
        $this->ensureNotEmpty($value);
        $this->ensureMaxLength($value, self::EMAIL_MAX_CHARACTERS);
        parent::__construct($value);
    }

    /**
     * @throws DomainException
     */
    private function ensureValidEmailFormat(?string $value): void
    {
        if (!$value) {
            throw ExceptionsFactory::invalidEmail();
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw ExceptionsFactory::wrongArgument($value . " is not a valid User Email");
        }
    }
}