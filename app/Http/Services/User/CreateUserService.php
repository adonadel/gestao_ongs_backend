<?php

namespace App\Http\Services\User;


use App\Exceptions\ExternalUserCantCreateAdminUserException;
use App\Repositories\PeopleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

class CreateUserService
{
    public function create(array $data, bool $isExternal = false)
    {
        $userRepository = new UserRepository();
        $personRepository = new PeopleRepository();

        $role = (new RoleRepository())->getById(data_get($data, 'role_id'));

        if ($isExternal && (str_contains($role->name, 'admin'))) {
            throw new ExternalUserCantCreateAdminUserException(
                'Usuário criado externamente não pode ser um administrador'
            );
        }

        $person = $personRepository->create(data_get($data, 'person'));

        $data['people_id'] = $person->id;

        return $userRepository->create($data);
    }
}
