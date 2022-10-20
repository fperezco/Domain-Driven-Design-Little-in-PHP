<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\DeliveryCompany;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DeliveryCompanyRepository extends AbstractRepository implements DeliveryCompanyRepositoryInterface
{

    /**
     * @return string
     */
    protected function getEntityRepositoryClass(): string
    {
        return DeliveryCompany::class;
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @return DeliveryCompany
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(DeliveryCompany $deliveryCompany): DeliveryCompany
    {
        $this->_em->persist($deliveryCompany);
        $this->_em->flush();
        return $deliveryCompany;
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(DeliveryCompany $deliveryCompany): void
    {
        $this->_em->flush();
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(DeliveryCompany $deliveryCompany): void
    {
        $this->_em->remove($deliveryCompany);
        $this->_em->flush();
    }


    /**
     * @return iterable | DeliveryCompany[]
     */
    public function findAll(): iterable{
        return parent::findBy(array(), array('uuid' => 'ASC'));
    }

    /**
     * @param array $criteria
     * @return DeliveryCompany|null
     */
    public function findOneByCriteria(array $criteria): ?DeliveryCompany
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @param array $criteria
     * @return iterable | DeliveryCompany[]
     */
    public function findAllByCriteria(array $criteria): iterable
    {
        return parent::findBy($criteria);
    }

    /**
     * @param Uuid $uuid
     * @return DeliveryCompany|null
     */
    public function findOneById(Uuid $uuid): ?DeliveryCompany
    {
        return parent::findOneBy(['uuid'=> $uuid]);
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @return int
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAmountOfTodoTasksInsideThisDeliveryCompany(DeliveryCompany $deliveryCompany): int
    {
        $deliveryCompanyUuid = $deliveryCompany->getUuid()->value();
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT count(*) as total
            FROM users,tasks 
            WHERE deletionDate is NULL
            AND users.deliveryCompany = :delivery_company_uuid
            AND tasks.responsible = users.uuid
            AND tasks.status = :task_status";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(
            ['delivery_company_uuid' => $deliveryCompanyUuid, "task_status" => TaskStatus::TODO]
        );

        $res = $resultSet->fetchAllAssociative()[0];
        return intval($res['total']);
    }
}
