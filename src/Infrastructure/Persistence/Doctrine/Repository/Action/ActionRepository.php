<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Action;

use App\Domain\Action\Repository\ActionRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Action\Entity\Action;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;

class ActionRepository extends AbstractRepository implements ActionRepositoryInterface
{

    /**
     * @return string
     */
    protected function getEntityRepositoryClass(): string
    {
        return Action::class;
    }

    /**
     * @return iterable | Action[]
     */
    public function findAll(): iterable
    {
        return parent::findAll();
    }

    /**
     * @param array $criteria
     * @return Action|null
     */
    public function findOneByCriteria(array $criteria): ?Action
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @param Uuid $uuid
     * @return Action|null
     */
    public function findOneById(Uuid $uuid): ?Action
    {
        return parent::findOneBy(['uuid'=> $uuid]);
    }

    /**
     * @param Action $action
     * @return Action
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Action $action): Action
    {
        $this->_em->persist($action);
        $this->_em->flush();
        return $action;
    }

    /**
     * @param Action $action
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Action $action): void
    {
        $this->_em->remove($action);
        $this->_em->flush();
    }


    /**
     * @param array $criteria
     * @return iterable | Action[]
     */
    public function findAllByCriteria(array $criteria): iterable
    {
        return parent::findBy($criteria);
    }

    /**
     * @param Uuid $userUuid
     * @return int
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     * @throws MappingException
     */
    public function getNumberOfTaskForTheUserWithUuid(Uuid $userUuid): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT count(*) as number_of_actions
            FROM actions
            WHERE responsible = :user_uuid";

        //lock for write access during count, this prevents you cannot insert any actions inside even when they belong or not to the
        //user that you are requesting VS task relationship where you can insert into tasks during the check of the number of task for the user if they
        //don't belong to the same user

//        $sql = "SELECT count(*) as number_of_actions
//            FROM actions
//            WHERE responsible = :user_uuid FOR UPDATE";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(
            ['user_uuid' => $userUuid->value()]
        );
        $res = $resultSet->fetchAllAssociative()[0];
        $this->getEntityManager()->clear();
        return intval($res['number_of_actions']);
    }
}
