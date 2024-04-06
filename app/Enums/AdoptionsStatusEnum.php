<?php

namespace App\Enums;

enum AdoptionsStatusEnum: string
{
    case OPENED = 'OPENED';
    case PROCESSING = 'PROCESSING';
    case ADOPTED = 'ADOPTED';
    case CANCELLED = 'CANCELLED';
    case DENIED = 'DENIED';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
