<?php
declare(strict_types=1);

namespace App\Infrastructure\Environment;

use App\Application\Shared\Environment\EnvironmentVariablesInterface;

class EnvironmentVariables implements EnvironmentVariablesInterface
{

    public function getVariable(string $key): string
    {
        return $_ENV[$key];
    }
}