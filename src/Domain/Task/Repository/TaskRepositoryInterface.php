<?php

namespace App\Domain\Task\Repository;

use App\Domain\Task\Entity\Task;
use App\Domain\Shared\ValueObject\Uuid;

/**
 * Due to task is part of the User aggregateRoot due to invariant there is no possibility to add new tasks outside user but
 * maybe, we need to list them without having to iterate along all the users.. or maybe this behaviour will be part of
 * another bounded context....
 */
interface TaskRepositoryInterface
{
    public function findOneByCriteria(array $criteria): ?Task;
    public function findAllByCriteria(array $criteria): iterable;
    public function findOneById(Uuid $uuid): ?Task;
    public function findAll(): iterable;
}