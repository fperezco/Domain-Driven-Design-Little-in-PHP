<?php
declare(strict_types=1);

namespace App\Domain\User\Dtos;


use App\Domain\Shared\Exception\DomainException;

class UserCreationAttributesDTO
{
    private ?string $firstname;
    private ?string $lastname;
    private ?string $email;
    private ?iterable $roles;
    private ?iterable $departments;
    private ?string $uuid;
    private bool $belongToAllDepartmentsFlag;

    /**
     * @throws DomainException
     */
    public function __construct(string $uuid, ?string $firstname, ?string $lastname, ?string $email, ?iterable $departments, bool $belongToAllDepartmentsFlag = false)
    {
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setEmail($email);
        $this->setDepartments($departments);
        $this->setUuid($uuid);
        $this->setBelongToAllDepartmentsFlag($belongToAllDepartmentsFlag);
    }

    /**
     * @return string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return iterable
     */
    public function getRoles(): ?iterable
    {
        return $this->roles;
    }

    /**
     * @return iterable
     */
    public function getDepartments(): ?iterable
    {
        return $this->departments;
    }


    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $firstname
     * @throws DomainException
     */
    public function setFirstname(?string $firstname): void
    {

        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     * @throws DomainException
     */
    public function setLastname(?string $lastname): void
    {

        $this->lastname = $lastname;
    }

    /**
     * @param string $email
     * @throws DomainException
     */
    public function setEmail(?string $email): void
    {

        $this->email = $email;
    }

    /**
     * @param iterable | Role[] $roles
     * @throws DomainException
     */
    public function setRoles(?iterable $roles): void
    {

        $this->roles = $roles;
    }

    /**
     * @param iterable |Department[] $departments
     * @throws DomainException
     */
    public function setDepartments(?iterable $departments): void
    {

        $this->departments = $departments;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return bool
     */
    public function isBelongToAllDepartmentsFlag(): bool
    {
        return $this->belongToAllDepartmentsFlag;
    }

    /**
     * @param bool $belongToAllDepartmentsFlag
     */
    public function setBelongToAllDepartmentsFlag(bool $belongToAllDepartmentsFlag): void
    {
        $this->belongToAllDepartmentsFlag = $belongToAllDepartmentsFlag;
    }

}