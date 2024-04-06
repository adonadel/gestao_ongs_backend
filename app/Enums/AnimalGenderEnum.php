<?php

namespace App\Enums;

enum AnimalGenderEnum: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
