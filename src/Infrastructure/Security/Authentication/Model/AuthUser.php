<?php
declare(strict_types=1);

namespace App\Infrastructure\Security\Authentication\Model;

use App\Domain\Security\Role\Entity\Role;
use App\Domain\User\Entity\UserEmail;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Infrastructure class to use JWTTokenManagerInterface hability to create token from
 * classes which implements UserInterface
 * Class AuthUser
 * @package App\Infrastructure\Security\Authentication\Model
 */
class AuthUser implements UserInterface
{
    private string $uuid;
    private string $name;
    private UserEmail $email;
    private iterable $roles;
    private string $password;

    public function __construct(string $uuid, string $name, UserEmail $email, string $password, iterable $roles)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email->value();
    }

    public function setEmail(UserEmail $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        //return $this->getEmail();
        return $this->getUuid();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->getRolesNamesArrayFromRoles();
    }

    private function getRolesNamesArrayFromRoles(): array
    {
        $roles = $this->roles;
        $rolesArray = [];
        /** @var Role $rol */
        foreach ($roles as $rol) {
            $rolesArray[]= $rol->getName()->value();
        }
        return $rolesArray;
    }

    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getUserIdentifier(): string
    {
        //return $this->getEmail();
        return $this->getUuid();
    }
}
