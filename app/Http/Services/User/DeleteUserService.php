<?php

namespace App\Http\Services\User;


use App\Repositories\UserRepository;

class DeleteUserService
{
    public function delete(int $id)
    {
        $repository = new UserRepository();

        $user = $repository->getById($id);

        $repository->delete($user);

        return true;
    }
}
