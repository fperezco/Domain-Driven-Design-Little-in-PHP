<?php
declare(strict_types=1);

namespace App\Application\UseCase\DeliveryCompany\Shared;

use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\DeliveryCompany\Entity\DeliveryCompany;


class DeliveryCompanyFinder
{
    private DeliveryCompanyRepositoryInterface $deliveryCompanyRepository;

    public function __construct(DeliveryCompanyRepositoryInterface $deliveryCompanyRepository)
    {
        $this->deliveryCompanyRepository = $deliveryCompanyRepository;
    }

    /**
     * @throws DomainException
     */
    public function __invoke(Uuid $uuid): DeliveryCompany
    {
        $deliveryCompany = $this->deliveryCompanyRepository->findOneById($uuid);
        if(!$deliveryCompany){
            throw ExceptionsFactory::deliveryCompanyNotFound();
        }
        return $deliveryCompany;
    }
}