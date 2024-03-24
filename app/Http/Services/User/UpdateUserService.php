<?php

namespace App\Http\Services\User;


use App\Repositories\PeopleRepository;
use App\Repositories\UserRepository;

class UpdateUserService
{
    function update(array $data, int $id)
    {
        $userRepository = new UserRepository();
        $personRepository = new PeopleRepository();

        $personData = data_get($data, 'person');
        $person = $personRepository->getById(data_get($personData, 'id'));

        $personRepository->update($person, $personData);

        $user = $userRepository->getById($id);

        return $userRepository->update($user, $data);
    }
}
