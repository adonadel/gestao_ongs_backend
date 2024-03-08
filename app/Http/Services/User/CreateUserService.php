<?php

namespace App\Http\Services\User;

use App\Models\User;

class CreateUserService
{
    function create(array $data)
    {
        $user = User::query()->create($data);

        return $user;
    }
}
