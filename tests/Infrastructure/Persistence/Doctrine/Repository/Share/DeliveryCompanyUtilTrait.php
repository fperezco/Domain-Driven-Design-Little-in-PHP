<?php

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository\Share;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;

trait DeliveryCompanyUtilTrait
{
    private DeliveryCompany $deliveryCompany1;
    private DeliveryCompany $deliveryCompany2;

    private function loadDeliveryCompanyFixtures(): void
    {
        $deliveryCompanyRepository = self::getContainer()->get(DeliveryCompanyRepositoryInterface::class);
        $this->deliveryCompany1 = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompanyRepository->save($this->deliveryCompany1);
        $this->deliveryCompany2 = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompanyRepository->save($this->deliveryCompany2);
    }
}