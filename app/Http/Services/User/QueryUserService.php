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
        return (new UserRepository())->getById($id)->load([
            'person.address',
            'role',
            'role.permissions',
            'person.profilePicture'
        ]);
    }

    public function getUserByIdExternal(int $id)
    {
        $user = (new UserRepository())->getById($id);
        
        return [
            'name' => $user->person->name,
            'email' => $user->person->email,
        ];
    }
}
