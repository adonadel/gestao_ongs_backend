<?php

namespace App\Http\Services\Event;

use App\Repositories\EventRepository;

class DeleteEventService
{

    public function delete(int $id)
    {
        $repository = new EventRepository();

        $event = $repository->getById($id);

        return $repository->delete($event);
    }
}
