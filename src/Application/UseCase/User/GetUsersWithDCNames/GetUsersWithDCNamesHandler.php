<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsersWithDCNames;


use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUsersWithDCNamesHandler implements QueryHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param GetUsersWithDCNamesQuery $getUsersQuery
     * @return iterable
     */
    public function __invoke(GetUsersWithDCNamesQuery $getUsersQuery): iterable
    {
        //options
        //1 you can maintain a query model listening events
        //2 you can create a query model on the fly using repo => required same database for both aggregates
        //3 you can create a query model using database mechanisms (triggers, view, etc..) => required same database for both aggregates
        //go for 2
        $usersPlainWithDCName = $this->userRepository->getUsersViewIncludingDCName();
        return $usersPlainWithDCName;
    }
}
