<?php

namespace App\Enums;

enum FinancePaymentStatusEnum: string
{
    case UNPAID = 'UNPAID';
    case PAID = 'PAID';
    case CANCELED = 'CANCELED';

    public static function toArrayWithString(): array
    {
        return collect(self::cases())->map( function($case) {
            return $case->value;
        })->toArray();
    }
}
