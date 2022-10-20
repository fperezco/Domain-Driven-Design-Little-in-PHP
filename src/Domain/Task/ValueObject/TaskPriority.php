<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\IntegerValueObject;

class TaskPriority extends IntegerValueObject
{
    private const TASK_PRIORITY_MAX = 100;
    private const TASK_PRIORITY_MIN = 0;

    /**
     * @throws DomainException
     */
    public function __construct($value)
    {
        $this->ensureNotEmpty($value);
        $this->ensureMinValue($value,self::TASK_PRIORITY_MIN);
        $this->ensureMaxValue($value,self::TASK_PRIORITY_MAX);
        parent::__construct($value);
    }

}
{

}