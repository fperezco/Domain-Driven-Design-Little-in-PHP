<?php
declare(strict_types=1);

namespace App\Domain\Shared\Exception;

use Throwable;

class DomainException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }
}