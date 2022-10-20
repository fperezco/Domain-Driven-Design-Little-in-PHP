<?php

namespace App\Domain\User\Traits;

use App\Domain\Task\Entity\Task;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\Task\ValueObject\TaskTitle;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Domain\User\Event\TaskDeletedEvent;

trait UserTaskTrait
{
    /**
     * @return iterable | Task[]
     */
    public function getTasks(): iterable{
        return $this->tasks;
    }

    /**
     * @param Uuid $taskUuid
     * @param TaskTitle $title
     * @param TaskPriority $priority
     * @return void
     * @throws \Exception
     */
    public function receiveANewTask(Uuid $taskUuid,TaskTitle $title,TaskPriority $priority): void{

        if(count($this->getTasks()) >= self::MAX_TASKS){
            throw ExceptionsFactory::maxNumberOfTaskAllowedReached();
        }
        $task = new Task($this,$taskUuid,$title,$priority);
        $this->tasks[] = $task;
        $this->recordEvent(new TaskCreatedEvent($this->getUuid()->value(),$taskUuid->value()));
    }

    /**
     * @param Uuid $taskUuid
     * @return void
     * @throws DomainException
     */
    public function removeTask(Uuid $taskUuid): void{
        $find = false;
        foreach($this->tasks as $key => $task)
        {
            if($task->getUuid()->equals($taskUuid)){
                unset($this->tasks[$key]);
                $find = true;
            }
        }
        if(!$find){
            throw ExceptionsFactory::taskNotFound();
        }
        $this->recordEvent(new TaskDeletedEvent($this->getUuid()->value(),$taskUuid->value()));
    }

    /**
     * @param Uuid $taskUuid
     * @param TaskStatus $taskStatus
     * @return void
     * @throws DomainException
     */
    public function updateTaskStatus(Uuid $taskUuid,TaskStatus $taskStatus):void{
        $taskToUpdate = $this->retrieveTaskByUuid($taskUuid);
        $taskToUpdate->setStatus($taskStatus);
    }

    /**
     * @param Uuid $taskUuid
     * @param TaskTitle $newTitle
     * @return void
     * @throws DomainException
     */
    public function updateTaskTitle(Uuid $taskUuid,TaskTitle $newTitle):void{
        $taskToUpdate = $this->retrieveTaskByUuid($taskUuid);
        $taskToUpdate->setTitle($newTitle);
    }

    /**
     * @param Uuid $taskUuid
     * @return Task|mixed
     * @throws DomainException
     */
    private function retrieveTaskByUuid(Uuid $taskUuid): Task
    {
        $targetTask = null;
        foreach ($this->tasks as $task) {
            if ($task->getUuid()->equals($taskUuid)) {
                $targetTask = $task;
            }
        }
        if (!$targetTask) {
            throw ExceptionsFactory::taskNotFound();
        }
        return $targetTask;
    }

}