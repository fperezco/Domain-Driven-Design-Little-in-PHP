<?php

declare(strict_types=1);

namespace App\Domain\Action\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class ActionTitle extends StringValueObject
{
    private const ACTIONTITLE_MAX_CHARACTERS = 50;
    private const ACTIONTITLE_MIN_CHARACTERS = 2;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinLength($value,self::ACTIONTITLE_MIN_CHARACTERS);
        $this->ensureMaxLength($value,self::ACTIONTITLE_MAX_CHARACTERS);
        parent::__construct($value);
    }

}