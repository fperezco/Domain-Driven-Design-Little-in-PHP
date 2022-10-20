<?php
declare(strict_types=1);

namespace App\Domain\Shared\Exception;

class ExceptionsFactory
{
    private const MISSING_INVALID_ARGUMENTS = 400;
    private const UNAUTHORIZED = 401;
    private const FORBIDDEN = 403;
    private const RESOURCE_CONFLICT_DOMAIN_RULES = 409;
    private const RESOURCE_NOT_FOUND = 404;
    private const INVALID_MEDIA_FILE = 415;
    private const SERVER_ERROR = 500;

    public static function wrongArgument(string $message): DomainException
    {
        return new DomainException(["error" => $message], self::MISSING_INVALID_ARGUMENTS);
    }

    public static function userNotFound(string $message = "user.notfound"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_NOT_FOUND);
    }

    public static function deliveryCompanyNotFound(string $message = "deliveryCompany.notfound"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_NOT_FOUND);
    }

    public static function taskNotFound(string $message = "task.notfound"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_NOT_FOUND);
    }

    public static function maxNumberOfTaskAllowedReached(string $message = "max.number.of.tasks.reached"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_CONFLICT_DOMAIN_RULES);
    }

    public static function maxNumberOfActionsAllowedReached(string $message = "max.number.of.actions.reached"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_CONFLICT_DOMAIN_RULES);
    }

    public static function emailExists(string $message = "error.email.alreadyExits"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_CONFLICT_DOMAIN_RULES);
    }

    public static function invalidEmail(): DomainException
    {
        return new DomainException(["error" => "Invalid Email"], self::MISSING_INVALID_ARGUMENTS);
    }

    public static function itemNotFound(string $message = "item.notfound"): DomainException
    {
        return new DomainException(["error" => $message], self::RESOURCE_NOT_FOUND);
    }

}