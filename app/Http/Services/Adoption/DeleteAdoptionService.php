<?php

namespace App\Http\Services\Adoption;

use App\Repositories\AnimalRepository;

class DeleteAdoptionService
{

    public function delete(int $id)
    {
        $repository = new AnimalRepository();

        $animal = $repository->getById($id);

        return $repository->delete($animal);
    }
}
