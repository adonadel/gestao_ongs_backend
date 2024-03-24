<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ENABLED = 'ENABLED';
    case DISABLED = 'DISABLED';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
