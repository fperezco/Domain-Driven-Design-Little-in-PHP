<?php

declare(strict_types=1);

namespace App\Application\UseCase\DeliveryCompany\GetDeliveryCompanies;

use App\Application\Shared\Mapper\DeliveryCompany\DeliveryCompanyResponseConverter;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;


class GetDeliveryCompaniesHandler implements QueryHandlerInterface
{
    private DeliveryCompanyResponseConverter $deliveryCompanyResponseConverter;
    private DeliveryCompanyRepositoryInterface $deliveryCompanyRepository;


    public function __construct(
        DeliveryCompanyRepositoryInterface $deliveryCompanyRepository,
        DeliveryCompanyResponseConverter $deliveryCompanyResponseConverter
    ) {
        $this->deliveryCompanyResponseConverter = $deliveryCompanyResponseConverter;
        $this->deliveryCompanyRepository = $deliveryCompanyRepository;
    }


    public function __invoke(GetDeliveryCompaniesQuery $getDeliveryCompaniesQuery): array
    {
        $deliveryCompanies = $this->deliveryCompanyRepository->findAll();
        return $this->deliveryCompanyResponseConverter->map($deliveryCompanies);
    }
}
