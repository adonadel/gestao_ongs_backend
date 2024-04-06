<?php

namespace App\Http\Services\User;

use App\Repositories\UserRepository;

class QueryUserService
{
    public function getUsers(array $filters)
    {
        $repository = new UserRepository();

        return $repository->getUsers($filters);
    }

    public function getUserById(int $id)
    {
        return (new UserRepository())->getById($id);
    }
}
