<?php

namespace App\Repositories;

use App\Models\Animal;

class AnimalRepository extends Repository
{
    protected $table = 'animals';

    protected function getModelClass()
    {
        return Animal::class;
    }
}
