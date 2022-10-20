<?php
declare(strict_types=1);

namespace App\Domain\DeliveryCompany\Repository;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\Shared\ValueObject\Uuid;

interface DeliveryCompanyRepositoryInterface
{
    public function save(DeliveryCompany $deliveryCompany): DeliveryCompany;
    public function update(DeliveryCompany $deliveryCompany): void;
    public function delete(DeliveryCompany $deliveryCompany): void;
    public function findOneByCriteria(array $criteria): ?DeliveryCompany;
    public function findAllByCriteria(array $criteria): iterable;
    public function findOneById(Uuid $uuid): ?DeliveryCompany;
    public function findAll(): iterable;
    public function getAmountOfTodoTasksInsideThisDeliveryCompany(DeliveryCompany $deliveryCompany): int;
}