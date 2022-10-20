<?php

declare(strict_types=1);

namespace App\Application\UseCase\Action\CreateAction;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\UseCase\User\Shared\UserFinder;
use App\Domain\Action\Repository\ActionRepositoryInterface;
use App\Domain\Action\Services\GenerateActionAssociateToUserDomainService;
use App\Domain\Action\ValueObject\ActionTitle;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateActionHandler implements CommandHandlerInterface
{
    private ActionRepositoryInterface $actionRepository;
    private UserFinder $userFinder;
    private GenerateActionAssociateToUserDomainService $generateActionAssociateToUserDomainService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ActionRepositoryInterface $actionRepository

    ) {
        $this->userFinder = new UserFinder($userRepository);
        $this->actionRepository = $actionRepository;
        $this->generateActionAssociateToUserDomainService = new GenerateActionAssociateToUserDomainService();
    }

    /**
     * @throws DomainException
     * @throws \ReflectionException
     */
    public function __invoke(CreateActionCommand $assignActionToUserCommand): void
    {
        $userOwnerUuid = new Uuid($assignActionToUserCommand->getUserUuid());
        $actionTitle = new ActionTitle($assignActionToUserCommand->getActionTitle());
        $userOwner = $this->userFinder->__invoke($userOwnerUuid);
        $numberOfActionsThatBelongsToTheCurrentUser = $this->actionRepository->getNumberOfTaskForTheUserWithUuid($userOwnerUuid);
        $action = $this->generateActionAssociateToUserDomainService->__invoke(
            $userOwner,
            $actionTitle,
            $numberOfActionsThatBelongsToTheCurrentUser
        );
        $this->actionRepository->save($action);
    }
}