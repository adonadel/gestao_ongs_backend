<?php

namespace App\Http\Services\Animal;

use App\Repositories\AnimalRepository;

class DeleteAnimalService
{

    public function delete(int $id)
    {
        $repository = new AnimalRepository();

        $animal = $repository->getById($id);

        return $repository->delete($animal);
    }
}
