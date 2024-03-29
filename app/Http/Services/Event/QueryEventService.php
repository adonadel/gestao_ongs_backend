<?php

namespace App\Http\Services\Event;

use App\Repositories\EventRepository;

class QueryEventService
{
    public function getEvents(array $filters)
    {
        return (new EventRepository())->getEvents($filters);
    }

    public function getEventById(int $id)
    {
        return (new EventRepository())->getById($id);
    }
}
