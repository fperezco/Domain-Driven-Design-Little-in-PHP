<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Task;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class TaskRepository extends AbstractRepository implements TaskRepositoryInterface
{

    /**
     * @return string
     */
    protected function getEntityRepositoryClass(): string
    {
        return Task::class;
    }

    /**
     * @return iterable | Task[]
     */
    public function findAll(): iterable
    {
        return parent::findAll();
    }

    /**
     * @param array $criteria
     * @return Task|null
     */
    public function findOneByCriteria(array $criteria): ?Task
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @param array $criteria
     * @return iterable | Task[]
     */
    public function findAllByCriteria(array $criteria): iterable
    {
        return parent::findBy($criteria);
    }

    /**
     * @param Uuid $uuid
     * @return Task|null
     */
    public function findOneById(Uuid $uuid): ?Task
    {
        return parent::findOneBy(['uuid'=> $uuid]);
    }
}
