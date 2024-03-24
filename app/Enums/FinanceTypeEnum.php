<?php

namespace App\Enums;

enum FinanceTypeEnum: string
{
    case INCOME = 'INCOME';
    case EXPENSE = 'EXPENSE';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
