<?php

declare(strict_types=1);

namespace App\Domain\Task\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\Task\ValueObject\TaskTitle;
use App\Domain\User\Entity\User;

class Task
{
    private User $responsible;
    private Uuid $uuid;
    private TaskTitle $title;
    private TaskPriority $priority;
    private TaskStatus $status;

    public function __construct(User $responsible,Uuid $uuid,TaskTitle $title,TaskPriority $priority)
    {
        $this->setResponsible($responsible);
        $this->setUuid($uuid);
        $this->setTitle($title);
        $this->setPriority($priority);
        $this->setInitialStatus();
    }

    /**
     * @return User|null
     */
    public function getResponsible(): ?User
    {
        return $this->responsible;
    }

    /**
     * @param User $user
     */
    private function setResponsible(User $user): void
    {
        $this->responsible = $user;
    }
    

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @param Uuid $uuid
     */
    private function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return TaskTitle
     */
    public function getTitle(): TaskTitle
    {
        return $this->title;
    }

    /**
     * @param TaskTitle $title
     */
    public function setTitle(TaskTitle $title): void
    {
        $this->title = $title;
    }

    /**
     * @return TaskPriority
     */
    public function getPriority(): TaskPriority
    {
        return $this->priority;
    }

    /**
     * @param TaskPriority $priority
     */
    public function setPriority(TaskPriority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * @param TaskStatus $status
     */
    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return void
     */
    private function setInitialStatus(): void
    {
        $this->setStatus(new TaskStatus(TaskStatus::TODO));
    }

}