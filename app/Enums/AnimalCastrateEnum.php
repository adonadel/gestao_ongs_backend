<?php

namespace App\Enums;

enum AnimalCastrateEnum: string
{
    case CASTRATED = 'CASTRATED';
    case NOT_CASTRATED = 'NOT_CASTRATED';
    case AWAITING_CASTRATION = 'AWAITING_CASTRATION';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
