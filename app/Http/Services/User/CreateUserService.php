<?php

namespace App\Http\Services\User;


use App\Repositories\PeopleRepository;
use App\Repositories\UserRepository;

class CreateUserService
{
    function create(array $data)
    {
        $userRepository = new UserRepository();
        $personRepository = new PeopleRepository();

        $person = $personRepository->create(data_get($data, 'person'));

        $data['people_id'] = $person->id;

        return $userRepository->create($data)->fresh('person', 'person.address', 'role');
    }
}
