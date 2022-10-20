<?php

declare(strict_types=1);

namespace App\Tests\Application\User;


use App\Application\Shared\Mapper\DeliveryCompany\DeliveryCompanyResponseConverter;
use App\Application\Shared\Mapper\User\UserResponseConverter;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Application\TestCaseWithFactory;
use PHPUnit\Framework\MockObject\MockObject;

abstract class UserTestCase extends TestCaseWithFactory
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

    protected function getUserResponseConverterMock()
    {
        return $this->createMock(UserResponseConverter::class);
    }

    protected function getDateTimeProviderMock()
    {
        return $this->createMock(DateTimeProvider::class);
    }

}