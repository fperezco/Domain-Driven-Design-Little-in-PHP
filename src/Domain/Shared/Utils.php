<?php


namespace App\Domain\Shared;

final class Utils
{
    /**
     * @throws \Exception
     */
    public static function generateRandomToken($length = 50): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateRandomPassword(): string
    {
        $charactersMin = "abcdefghijklmnopqrstuvwxyz";
        $charactersMay = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "0123456789";
        $special = "≠´“”‚<>ç´¨`¿?!@#œåΩæ∫∑€∂©®ƒ√†ß¥¶µ§„ø…π~–≤ÀÁÄÂÃÅĄÆŠĐÉÈËÊĘĖĒÚÜÙŪÛÍÏÌÎĮĪÓÒÖÔÕØŒŌĆČÑŃ=$%^&*+ºª|";
        $password = "";
        for ($i = 0; $i < 5; $i++) {
            $password .= $charactersMin[rand(0, strlen($charactersMin) - 1)];
            $password .= $charactersMay[rand(0, strlen($charactersMay) - 1)];
            $password .= $numbers[rand(0, strlen($numbers) - 1)];
            $password .= $special[rand(0, strlen($special) - 1)];
        }
        return $password;
    }

}
