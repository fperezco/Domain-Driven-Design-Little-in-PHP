<?php
declare(strict_types=1);

namespace App\Domain\User\Dtos;

use App\Domain\User\Entity\User;

class UserRequestCreationDTO
{
    private User $userRequestCreation;

    private UserCreationAttributesDTO $userCreationAttributesDTO;
    private bool $userEmailIsExisting;

    private iterable $departmentsInSystem;

    public function __construct(User $userRequestCreation, UserCreationAttributesDTO $userCreationDTO, iterable $departmentsInSystem,bool $userEmailIsExisting)
    {

        $this->userRequestCreation = $userRequestCreation;
        $this->userCreationAttributesDTO = $userCreationDTO;
        $this->userEmailIsExisting = $userEmailIsExisting;
        $this->departmentsInSystem = $departmentsInSystem;
    }

    /**
     * @return User
     */
    public function getUserRequestCreation(): User
    {
        return $this->userRequestCreation;
    }

    /**
     * @return iterable
     */
    public function getRolesInSystem(): iterable
    {
        return $this->rolesInSystem;
    }

    /**
     * @return UserCreationAttributesDTO
     */
    public function getUserCreationAttributesDTO(): UserCreationAttributesDTO
    {
        return $this->userCreationAttributesDTO;
    }

    /**
     * @return bool
     */
    public function isUserEmailIsExisting(): bool
    {
        return $this->userEmailIsExisting;
    }

    /**
     * @return iterable
     */
    public function getDomainsInSystem(): iterable
    {
        return $this->domainsInSystem;
    }

    /**
     * @return iterable
     */
    public function getDepartmentsInSystem(): iterable
    {
        return $this->departmentsInSystem;
    }

}