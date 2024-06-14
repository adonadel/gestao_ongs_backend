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
        $event = (new EventRepository())->getById($id)->load('medias', 'address');

        return [
            ...$event->toArray(),
            'medias' => $event->medias->map(function ($media) {
                return [
                    ...$media->toArray(),
                    'is_cover' => (bool) $media->pivot->is_cover
                ];
            })
        ];
    }
}
