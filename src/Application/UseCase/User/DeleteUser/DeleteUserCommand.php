<?php
declare(strict_types=1);

namespace App\Application\UseCase\User\DeleteUser;

use App\Application\Shared\Command\CommandInterface;

final class DeleteUserCommand implements CommandInterface
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

}