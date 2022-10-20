<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

/**
 * Because we don't have a native type UUid in php we use this from Ramsey, not a real
 * dependency over infrastructure because we can just go Ramsey and copy code
 * Class Uuid
 * @package App\Domain\Shared\ValueObject
 */
class Uuid implements Stringable
{
    protected string $value;

    /**
     * @throws DomainException
     */
    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    /**
     * @throws DomainException
     */
    public static function random(): self
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    /**
     * @throws DomainException
     */
    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw ExceptionsFactory::wrongArgument("Invalid Uuid");
        }
    }
}
