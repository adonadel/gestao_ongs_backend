<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case INTERNAL = 'INTERNAL';
    case EXTERNAL = 'EXTERNAL';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
