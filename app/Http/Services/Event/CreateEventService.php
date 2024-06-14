<?php

namespace App\Http\Services\Event;

use App\Http\Services\Media\CreateMediaService;
use App\Repositories\AddressRepository;
use App\Repositories\EventRepository;

class CreateEventService
{

    public function create(array $data)
    {
        $repository = new EventRepository();

        $mediasIds = data_get($data, 'medias');

        $event = $repository->create($data);

        if ($mediasIds && $exploded = explode(",", trim($mediasIds))) {
            $event->medias()->sync($exploded);
        }

        return $event;
    }

    public function createWithMedias(array &$data)
    {
        $repository = new EventRepository();

        if($addressData = data_get($data, 'address')) {
            $address = $this->handleAddress($addressData);
            $data['address_id'] = $address->id;
            unset($data['address']);
        }

        $event = $repository->create($data);

        if (data_get($data, 'medias')) {
            foreach (data_get($data, 'medias') as $media){
                $createMediaService = new CreateMediaService();

                $createMediaService->create($media);
            }
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
