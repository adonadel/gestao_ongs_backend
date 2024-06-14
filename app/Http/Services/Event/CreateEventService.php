<?php

namespace App\Http\Services\Event;

use App\Http\Services\Media\CreateMediaService;
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

    public function createWithMedias(array $data)
    {
        $repository = new EventRepository();

        $event = $repository->create($data);

        if (data_get($data, 'medias')) {
            foreach (data_get($data, 'medias') as $media){
                $createMediaService = new CreateMediaService();

                $createMediaService->create($media);
            }
        }

        return $event;
    }
}
