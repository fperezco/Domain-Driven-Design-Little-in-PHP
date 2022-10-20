<?php

declare(strict_types=1);

namespace App\Tests\Resources\Factory\FactoryTraits;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskTitle;

trait TaskFactoryTrait
{

    /**
     * @throws DomainException
     */
    public function getValidTaskTitle(): TaskTitle
    {
        return new TaskTitle($this->faker->regexify('[a-z]{10}'));
    }

    /**
     * @throws DomainException
     */
    public function getValidTaskPriority(): TaskPriority
    {
        return new TaskPriority($this->faker->numberBetween(0, 100));
    }
}