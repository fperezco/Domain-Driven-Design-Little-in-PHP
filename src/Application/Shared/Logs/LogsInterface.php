<?php
declare(strict_types=1);

namespace App\Application\Shared\Logs;

interface LogsInterface
{
    public function info(string $message);
    public function error(string $message);
    public function warning(string $message);
}