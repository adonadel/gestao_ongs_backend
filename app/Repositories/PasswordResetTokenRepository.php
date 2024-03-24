<?php

namespace App\Repositories;

use App\Models\PasswordResetToken;

class PasswordResetTokenRepository extends Repository
{
    protected function getModelClass(): string
    {
        return PasswordResetToken::class;
    }
}
