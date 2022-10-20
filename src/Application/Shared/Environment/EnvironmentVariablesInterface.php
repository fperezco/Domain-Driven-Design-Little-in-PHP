<?php


namespace App\Application\Shared\Environment;


interface EnvironmentVariablesInterface
{
    public function getVariable(string $key): string;
}