<?php

namespace App\Http\Services\Event;

use App\Http\Services\Media\CreateMediaService;
use App\Repositories\EventRepository;

class CreateEventService
{

    public function create(array $data)
    {
        $repository = new EventRepository();

        $mediasIds = collect($data['medias'])
            ->pluck('id')
            ->toArray();

        $event = $repository->create($data);

        if ($mediasIds) {
            $event->medias()->sync($mediasIds);
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
