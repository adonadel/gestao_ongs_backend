<?php

namespace App\Http\Services\Animal;

use App\Repositories\AnimalRepository;

class QueryAnimalService
{
    public function getAnimals(array $filters)
    {
        return (new AnimalRepository())->getAnimals($filters);
    }

    public function getAnimalById(int $id)
    {
        return (new AnimalRepository())->getById($id);
    }
}
