<?php

namespace App\Http\Services\User;

use App\Models\User;

class UpdateUserService
{
    function update(array $data, int $id)
    {
        $user = User::query()->find($id);

        $updated = $user->update($data);

        return $updated;
    }
}
