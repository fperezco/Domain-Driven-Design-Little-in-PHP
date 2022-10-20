<?php

declare(strict_types=1);

namespace App\Tests\Domain\Action\Entity;

use App\Domain\Action\Services\GenerateActionAssociateToUserDomainService;
use App\Domain\Action\ValueObject\ActionTitle;
use App\Domain\Action\Entity\Action;
use App\Domain\Shared\Exception\DomainException;
use App\Tests\Application\TestCaseWithFactory;

class ActionTest extends TestCaseWithFactory
{

    /** @test */
    public function action_cannot_be_created_using_directly_the_constructor(): void
    {
        //GIVEN
        $userUuid = $this->fakerFactory->getValidUUid();
        $actionTitle = new ActionTitle("title blabla");

        //THEN
        $this->expectException(\Throwable::class);

        //WHEN
        $action = new Action($userUuid,$action);
    }

    /** @test */
    public function action_can_be_created_using_the_domain_builder_service_GenerateActionAssociateToUserDomainService(): void
    {
        //GIVEN
        $userOwner = $this->fakerFactory->getValidUser();
        $actionTitle = new ActionTitle("title blabla");
        $numberOfCurrentActionsForThisUser = 2;
        $generateActionAssociateToUserDomainService = new GenerateActionAssociateToUserDomainService();

        //WHEN
        $action = $generateActionAssociateToUserDomainService->__invoke($userOwner,$actionTitle,$numberOfCurrentActionsForThisUser);

        //THEN
        $this->assertEquals(get_class($action),Action::class);
        $this->assertEquals($action->getTitle(),$actionTitle);
    }

    /** @test */
    public function action_cannot_be_created_if_the_current_user_has_already_3_actions(): void
    {
        //GIVEN
        $userOwner = $this->fakerFactory->getValidUser();
        $actionTitle = new ActionTitle("title blabla");
        $numberOfCurrentActionsForThisUser = 3;
        $generateActionAssociateToUserDomainService = new GenerateActionAssociateToUserDomainService();

        //THEN
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("max.number.of.actions.reached");

        //WHEN
        $action = $generateActionAssociateToUserDomainService->__invoke($userOwner,$actionTitle,$numberOfCurrentActionsForThisUser);
    }

}