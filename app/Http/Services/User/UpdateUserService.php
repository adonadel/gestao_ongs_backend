<?php

namespace App\Http\Services\User;


use App\Repositories\AddressRepository;
use App\Repositories\PeopleRepository;
use App\Repositories\UserRepository;

class UpdateUserService
{
    public function update(array $data, int $id)
    {
        $userRepository = new UserRepository();
        $personRepository = new PeopleRepository();

        $personData = data_get($data, 'person');
        $person = $personRepository->getById(data_get($personData, 'id'));

        if($addressData = data_get($personData, 'address')) {
            $address = $this->handleAddress($addressData);
            $personData['address_id'] = $address->id;
            unset($personData['address']);
        }

        $personRepository->update($person, $personData);

        $user = $userRepository->getById($id);

        return $userRepository->update($user, $data);
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
