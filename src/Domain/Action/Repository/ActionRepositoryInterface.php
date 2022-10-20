<?php

namespace App\Domain\Action\Repository;

use App\Domain\Action\Entity\Action;
use App\Domain\Shared\ValueObject\Uuid;

interface ActionRepositoryInterface
{
    public function findOneById(Uuid $uuid): ?Action;
    public function findAll(): iterable;
    public function findAllByCriteria(array $criteria): iterable;
    public function save(Action $Action): Action;
    public function delete(Action $Action): void;
    public function getNumberOfTaskForTheUserWithUuid(Uuid $userUuid): int;
}