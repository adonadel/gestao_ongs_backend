<?php

namespace App\Http\Services\User;


use App\Enums\UserTypeEnum;
use App\Repositories\AddressRepository;
use App\Repositories\PeopleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

class CreateUserService
{
    public function create(array $data, ?UserTypeEnum $type)
    {
        $userRepository = new UserRepository();
        $personRepository = new PeopleRepository();

        $personData = data_get($data, 'person');

        if($addressData = data_get($personData, 'address')) {
            $address = $this->handleAddress($addressData);
            $personData['address_id'] = $address->id;
            unset($personData['address']);
        }

        $person = $personRepository->create($personData);

        $data['people_id'] = $person->id;

        if ($type !== null) {
            $data['type'] = $type;
        }

        if (data_get($data, 'type') === UserTypeEnum::EXTERNAL) {
            $data['role_id'] = (new RoleRepository())->newQuery()->where('name', 'user')->first()->id;
        }

        return $userRepository->create($data)->load('person', 'person.address');
    }

    private function handleAddress(array $addressData)
    {
        $addressRepository = new AddressRepository();

        if ($id = data_get($addressData, 'id')) {
            $address = $addressRepository->getById($id);
            $addressRepository->update($address, $addressData);
        }else {
            $address = $addressRepository->create($addressData);
        }

        return $address;
    }
}
