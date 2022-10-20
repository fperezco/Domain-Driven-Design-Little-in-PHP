<?php
declare(strict_types=1);

namespace App\Application\UseCase\User\Shared;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserFinder
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Uuid $uuid
     * @return User
     * @throws DomainException
     */
    public function __invoke(Uuid $uuid): User
    {
        $user = $this->userRepository->findOneById($uuid);
        if(!$user){
            throw ExceptionsFactory::userNotFound();
        }
        return $user;
    }
}