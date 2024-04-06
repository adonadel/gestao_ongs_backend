<?php

namespace App\Http\Services\User;


use App\Repositories\UserRepository;

class DeleteUserService
{
    public function delete(int $id)
    {
        $repository = new UserRepository();

        $user = $repository->getById($id);

        return $repository->delete($user);
    }
}
