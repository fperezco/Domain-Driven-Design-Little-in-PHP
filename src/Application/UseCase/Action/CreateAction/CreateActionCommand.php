<?php

declare(strict_types=1);

namespace App\Application\UseCase\Action\CreateAction;

use App\Application\Shared\Command\CommandInterface;

class CreateActionCommand implements CommandInterface
{
    private string $actionTitle;
    private string $userUuid;

    public function __construct(string $userUuid,string $actionTitle)
    {
        $this->userUuid = $userUuid;
        $this->actionTitle = $actionTitle;
    }

    /**
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }


    /**
     * @return string
     */
    public function getActionTitle(): string
    {
        return $this->actionTitle;
    }

}