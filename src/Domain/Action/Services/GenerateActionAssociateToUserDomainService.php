<?php

declare(strict_types=1);

namespace App\Domain\Action\Services;

use App\Domain\Action\Entity\Action;
use App\Domain\Action\ValueObject\ActionTitle;
use App\Domain\Shared\Builder\ConstructorTrait;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\User\Entity\User;

/**
 * A domain class that orchestrates relationship between User and Actions, prevent create an Action
 * that points to a fake or invalid User, due to we have a concrete save method for Actions
 */
class GenerateActionAssociateToUserDomainService
{
    use ConstructorTrait;

    /**
     * @param User $userOwner
     * @param ActionTitle $actionTitle
     * @param int $tasksThatBelongsToTheCurrentUser
     * @return Action
     * @throws DomainException
     * @throws \ReflectionException
     */
    public function __invoke(User $userOwner, ActionTitle $actionTitle, int $tasksThatBelongsToTheCurrentUser): Action
    {
        $this->guardCanAddMoreActionsToThisUser($tasksThatBelongsToTheCurrentUser);
        /** @var Action $action */
        $action = $this->createObject(Action::class,[$userOwner->getUuid(),$actionTitle]);
        return $action;
    }

    /**
     * Business rule inside Domain Layer but outside Domain Object
     * @throws DomainException
     */
    private function guardCanAddMoreActionsToThisUser(int $tasksThatBelongsToTheCurrentUser)
    {
        if ($tasksThatBelongsToTheCurrentUser >= 3) {
            throw  ExceptionsFactory::maxNumberOfActionsAllowedReached();
        }
    }
}