<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    protected function getModelClass(): string
    {
        return User::class;
    }
}
