<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{

    /**
     * @return string
     */
    protected function getEntityRepositoryClass(): string
    {
        return User::class;
    }

    /**
     * @param User $user
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): User
    {
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    /**
     * @param User $user
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }


    /**
     * @return iterable | User[]
     */
    public function findAll(): iterable
    {
        return parent::findBy(array('deletionDate'=> NULL), array('uuid' => 'ASC'));
    }

    /**
     * @param array $criteria
     * @return User|null
     */
    public function findOneByCriteria(array $criteria): ?User
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @param array $criteria
     * @return iterable | User[]
     */
    public function findAllByCriteria(array $criteria): iterable
    {
        return parent::findBy($criteria);
    }


    /**
     * @param Uuid $uuid
     * @return User|null
     */
    public function findOneById(Uuid $uuid): ?User
    {
       return parent::findOneBy(['uuid' => $uuid]);
       //pessimist locking, prevent insert more tasks that belong to this user during the transaction ( you can insert to other users )
       //return parent::find($uuid, LockMode::PESSIMISTIC_WRITE);
    }


    /**
     * @return iterable
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getUsersViewIncludingDCName(): iterable
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT deliveryCompanies.name as deliveryCompany,users.uuid as uuid,users.firstname as firstname,users.lastname as lastname,users.email as email,
        users.username as username from deliveryCompanies,users where users.deliveryCompany = deliveryCompanies.uuid and users.deletionDate is NULL order by users.uuid";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $res = $resultSet->fetchAllAssociative();
        return $res;
    }


}
