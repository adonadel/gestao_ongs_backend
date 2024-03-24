<?php

namespace App\Repositories;

use App\Models\People;

class PeopleRepository extends Repository
{
    protected function getModelClass(): string
    {
        return People::class;
    }
}
