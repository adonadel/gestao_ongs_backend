<?php

namespace App\Repositories;

use App\Models\EventMedia;

class EventMediaRepository extends Repository
{
    protected function getModelClass(): string
    {
        return EventMedia::class;
    }


}
