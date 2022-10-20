<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;

use App\Domain\User\Entity\User;

class UserResponseConverter
{

    /**
     * @param iterable | User[] $users
     * @return array
     */
    public function map(iterable $users): array
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = $this->formatToResponse($user);
        }
        return $result;
    }

    /**
     * @param User $user
     * @return UserResponse
     */
    public function formatToResponse(User $user): UserResponse
    {
        return new UserResponse(
            $user->getDeliveryCompanyUuid()->value(),
            $user->getUuid()->value(),
            $user->getFirstname()->value(),
            $user->getLastname()->value(),
            $user->getUsername()->value(),
            $user->getEmail()->value()
        );
    }
}
