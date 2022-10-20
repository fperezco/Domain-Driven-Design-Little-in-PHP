<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\DeliveryCompany;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;

class DeliveryCompanyResponseConverter
{

    /**
     * @param iterable | DeliveryCompany[] $deliveryCompanys
     * @return array
     */
    public function map(iterable $deliveryCompanys): array
    {
        $result = [];
        foreach ($deliveryCompanys as $deliveryCompany) {
            $result[] = $this->formatToResponse($deliveryCompany);
        }
        return $result;
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @return DeliveryCompanyResponse
     */
    public function formatToResponse(DeliveryCompany $deliveryCompany): DeliveryCompanyResponse
    {
        return new DeliveryCompanyResponse(
            $deliveryCompany->getUuid()->value(),
            $deliveryCompany->getName()->value(),
            $deliveryCompany->getNumberOfTaskInTODO(),
            $deliveryCompany->getVipLevel()->value(),
        );
    }
}
