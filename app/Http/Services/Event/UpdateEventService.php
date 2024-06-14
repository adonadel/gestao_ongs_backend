<?php

namespace App\Http\Services\Event;

use App\Repositories\AddressRepository;
use App\Repositories\EventRepository;

class UpdateEventService
{

    public function update(array &$data, int $id)
    {
        $repository = new EventRepository();

        $event = $repository->getById($id);

        $mediasIds = data_get($data, 'medias');

        if($addressData = data_get($data, 'address')) {
            $address = $this->handleAddress($addressData);
            $data['address_id'] = $address->id;
            unset($data['address']);
        }

        $repository->update($event, $data);

        if ($mediasIds && $exploded = explode(",", trim($mediasIds))) {
            $event->medias()->sync($exploded);
        }

        return $event;
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
