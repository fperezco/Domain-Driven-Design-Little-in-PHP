<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsers;


use App\Application\Shared\Mapper\User\UserResponseConverter;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUsersHandler implements QueryHandlerInterface
{

    private UserRepositoryInterface $userRepository;
    private UserResponseConverter $userResponseConverter;


    public function __construct(
        UserRepositoryInterface $userRepository,
        UserResponseConverter $userResponseConverter
    ) {
        $this->userRepository = $userRepository;
        $this->userResponseConverter = $userResponseConverter;
    }


    /**
     * @param GetUsersQuery $getUsersQuery
     * @return array
     */
    public function __invoke(GetUsersQuery $getUsersQuery): array
    {
        $users = $this->userRepository->findAll();
        return $this->userResponseConverter->map($users);
    }
}
