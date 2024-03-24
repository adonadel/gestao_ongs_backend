<?php

namespace App\Utils;

abstract class StringUtils
{
    public static function checkIfStringStartWithNumber($string) {
        return preg_match('/^\d/', $string);
    }
}
