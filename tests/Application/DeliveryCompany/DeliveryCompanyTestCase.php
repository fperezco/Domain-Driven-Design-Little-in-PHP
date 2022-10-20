<?php

declare(strict_types=1);

namespace App\Tests\Application\DeliveryCompany;


use App\Application\Shared\Mapper\User\DeliveryCompanyResponseConverter;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Application\TestCaseWithFactory;
use PHPUnit\Framework\MockObject\MockObject;

abstract class DeliveryCompanyTestCase extends TestCaseWithFactory
{
    /**
     * @return UserRepositoryInterface|MockObject
     */
    protected function getRepositoryMock()
    {
        return $this->createMock(UserRepositoryInterface::class);
    }

    protected function getUserRepositoryMock()
    {
        return $this->createMock(UserRepositoryInterface::class);
    }

    protected function getDeliveryCompanyRepositoryMock()
    {
        return $this->createMock(DeliveryCompanyRepositoryInterface::class);
    }

}