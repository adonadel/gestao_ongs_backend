<?php

namespace App\Enums;

enum AgeTypeEnum: string
{
    case CUB = 'CUB';
    case TEEN = 'TEEN';
    case ADULT = 'ADULT';
    case ELDERLY = 'ELDERLY';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
