<?php

namespace App\Http\Services\Event;

use App\Http\Services\Media\CreateMediaService;
use App\Repositories\EventRepository;

class UpdateEventService
{

    public function update(array $data, int $id)
    {
        $repository = new EventRepository();

        $event = $repository->getById($id);

        $repository->update($event, $data);

        if (data_get($data, 'medias')) {
            foreach (data_get($data, 'medias') as $media){
                $createMediaService = new CreateMediaService();

                $createMediaService->create($media);
            }
        }

        return $event;
    }
}
