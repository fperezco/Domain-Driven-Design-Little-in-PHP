<?php

declare(strict_types=1);

namespace App\Tests\Domain\DeliveryCompany\Entity;

use App\Tests\Application\TestCaseWithFactory;

class DeliveryCompanyTest extends TestCaseWithFactory
{

    /** @test */
    public function it_should_register_a_user_inside_his_company(){
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $userUuid = $this->fakerFactory->getValidUUid();
        $userFirstName = $this->fakerFactory->getValidUserFirstName();
        $userLastName = $this->fakerFactory->getValidUserLastName();
        $userEmail = $this->fakerFactory->getValidUserEmail();
        $userUsername = $this->fakerFactory->getValidUserUsername();

        //WHEN
        $newUser = $deliveryCompany->registerANewUser($userUuid,$userFirstName,$userLastName,$userEmail,$userUsername);

        //THEN
        $this->assertEquals($newUser->getDeliveryCompanyUuid(),$deliveryCompany->getUuid());
        $this->assertEquals($newUser->getUuid(),$userUuid);
        $this->assertEquals($newUser->getFirstname(),$userFirstName);
        $this->assertEquals($newUser->getLastName(),$userLastName);
        $this->assertEquals($newUser->getEmail(),$userEmail);
        $this->assertEquals($newUser->getUsername(),$userUsername);
    }

}