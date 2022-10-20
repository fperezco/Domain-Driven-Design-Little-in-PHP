<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;


use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;

abstract class StringValueObject
{
    protected string $value;

    function getObjectName(): string
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws DomainException
     */
    public function ensureNotEmpty(?string $value): void
    {
        if (!$value || $value == "") {
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." must not be empty");
        }
    }

    /**
     * @throws DomainException
     */
    public function ensureValueIsInArray(?string $value, array $arrayOfPossibleValues): void{
        if (!in_array($value, $arrayOfPossibleValues)) {
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." invalid value");
        }
    }

    /**
     * @throws DomainException
     */
    public function ensureMinLength(string $value, int $length): void
    {
        if (strlen($value) < $length) {
            throw ExceptionsFactory::wrongArgument($this->getObjectName()."  must have at least $length characters");
        }
    }

    /**
     * @throws DomainException
     */
    public function ensureMaxLength(string $value, int $length): void
    {
        if (grapheme_strlen($value) > $length) { //due to emojis presence
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." must have a max of $length characters");
        }
    }

}
