<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function delete(User $user): void;

    public function findOneByCriteria(array $criteria): ?User;

    public function findAllByCriteria(array $criteria): iterable;

    public function findOneById(Uuid $uuid): ?User;

    public function findAll(): iterable;

    public function getUsersViewIncludingDCName(): iterable;
}