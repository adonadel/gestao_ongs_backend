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

    public static function validateCPF($cpf)
    {
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
