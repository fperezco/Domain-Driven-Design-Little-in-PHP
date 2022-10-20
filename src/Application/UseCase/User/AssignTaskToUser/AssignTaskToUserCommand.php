<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignTaskToUser;

use App\Application\Shared\Command\CommandInterface;

class AssignTaskToUserCommand implements CommandInterface
{
    private string $taskTitle;
    private int $taskPriority;
    private string $userUuid;

    public function __construct(string $userUuid,string $taskTitle,int $taskPriority)
    {
        $this->userUuid = $userUuid;
        $this->taskTitle = $taskTitle;
        $this->taskPriority = $taskPriority;
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
    public function getTaskTitle(): string
    {
        return $this->taskTitle;
    }

    /**
     * @return int
     */
    public function getTaskPriority(): int
    {
        return $this->taskPriority;
    }

}