<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends Repository
{
    protected $table = 'events';

    protected function getModelClass()
    {
        return Event::class;
    }

    public function getEvents(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'name');

        $query = $this->newQuery();

        $query
            ->with('medias')
            ->when($search, function(Builder $query, $search){
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
