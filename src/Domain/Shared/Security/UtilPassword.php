<?php
declare(strict_types=1);

namespace App\Domain\Shared\Security;


class UtilPassword
{
    public static function encodePassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public static function equalPlainPasswordToEncodedPassword(string $plainPassword, string $encodedPassword): bool
    {
        if (password_verify($plainPassword, $encodedPassword)) {
            return true;
        }
        return false;
    }


}