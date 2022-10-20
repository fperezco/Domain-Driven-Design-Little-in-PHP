<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Environment;

use App\Infrastructure\Environment\EnvironmentVariables;
use Monolog\Test\TestCase;

class EnvironmentVariablesTest extends TestCase
{
    public function test_that_can_access_env_variables()
    {
        //GIVEN
        $environmentVariables = new EnvironmentVariables();
        //WHEN
        $frontUrl = $environmentVariables->getVariable("FRONTEND_URL");
        //THEN
        $this->assertEquals($frontUrl,'http://localhost:4200');
    }


}