<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\DeliveryCompany;


final class DeliveryCompanyResponse
{
    public string $uuid;
    public string $name;
    public int $tasksInTodo;
    public string $vipLevel;

    public function __construct(
        string $uuid,
        string $name,
        int $tasksInTodo,
        string $vipLevel
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->tasksInTodo = $tasksInTodo;
        $this->vipLevel = $vipLevel;
    }
}
