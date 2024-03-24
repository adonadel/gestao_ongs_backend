<?php

namespace App\Utils;

abstract class CPFUtils
{
    public static function mask(string $cpf)
    {
        return vsprintf("%s%s%s.%s%s%s.%s%s%s-%s%s", str_split($cpf));
    }

    public static function hideChars(string $cpf)
    {
        return substr($cpf, 0, 3).'.xxx.xxx-'.substr($cpf, -2);
    }

    public static function removeNonAlphaNumericFromString($string)
    {
        return preg_replace('/[^\da-zA-Z]/', '', $string);
    }
}
