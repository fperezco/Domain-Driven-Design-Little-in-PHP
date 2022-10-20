<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\StringValueObject;

final class TaskStatus extends StringValueObject
{
    public const TODO = "TODO";
    public const REVIEW = "REVIEW";
    public const FINISHED = "FINISHED";
    private array $values = [self::TODO,self::REVIEW,self::FINISHED];

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