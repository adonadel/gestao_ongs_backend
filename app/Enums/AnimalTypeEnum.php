<?php

namespace App\Enums;

enum AnimalTypeEnum: string
{
    case CAT = 'CAT';
    case DOG = 'DOG';
    case OTHER = 'OTHER';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
