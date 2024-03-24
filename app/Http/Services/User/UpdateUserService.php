<?php

namespace App\Http\Services\User;


use App\Repositories\UserRepository;

class UpdateUserService
{
    function update(array $data, int $id)
    {
        $repository = new UserRepository();
        $user = $repository->getById($id);

        return $repository->update($user, $data);
    }
}
