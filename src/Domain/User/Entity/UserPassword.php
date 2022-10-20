<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\Shared\Security\UtilPassword;
use App\Domain\Shared\ValueObject\StringValueObject;

final class UserPassword extends StringValueObject
{
    private const EMAIL_MIN_CHARACTERS = 14;
    private const EMAIL_MAX_CHARACTERS = 50;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinLength($value, self::EMAIL_MIN_CHARACTERS);
        $this->ensureMaxLength($value, self::EMAIL_MAX_CHARACTERS);
        $this->ensureRespectRegex($value);
        $encodedPassword = UtilPassword::encodePassword($value);
        parent::__construct($encodedPassword);
    }

    /**
     * @throws DomainException
     */
    private function ensureRespectRegex(string $password): void
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $special = preg_match('/[^a-zA-Z\d]/', $password);

        if (!$uppercase || !$lowercase || !$number || !$special) {
            throw ExceptionsFactory::wrongArgument(
                $this->getObjectName() . " must respect minimum format (mayus,minus,number,especial characters)"
            );
        }
    }
}