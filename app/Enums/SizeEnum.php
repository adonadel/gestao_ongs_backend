<?php

namespace App\Enums;

enum SizeEnum: string
{
    case SMALL = 'SMALL';
    case MEDIUM = 'MEDIUM';
    case LARGE = 'LARGE';
    case VERY_LARGE = 'VERY_LARGE';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
