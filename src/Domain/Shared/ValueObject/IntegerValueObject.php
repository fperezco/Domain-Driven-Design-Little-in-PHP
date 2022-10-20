<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\ExceptionsFactory;

abstract class IntegerValueObject
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    function getObjectName(): string
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function ensureNotEmpty(int $value): void
    {
        if((!$value ||$value =="") && $value != 0){
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." must not be empty", 400);
        }
    }

    public function ensureMinValue(int $value, int $min): void
    {
        if($value < $min){
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." must be higher that $min", 400);
        }
    }

    public function ensureMaxValue(int $value, int $max): void
    {
        if( $value > $max){
            throw ExceptionsFactory::wrongArgument($this->getObjectName()." must be lower that $max", 400);
        }
    }
}