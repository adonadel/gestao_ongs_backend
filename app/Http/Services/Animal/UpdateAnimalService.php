<?php

namespace App\Http\Services\Animal;

use App\Repositories\AnimalRepository;

class UpdateAnimalService
{

    public function update(array $data, int $id)
    {
        $repository = new AnimalRepository();

        $animal = $repository->getById($id);

        $repository->update($animal, $data);

        $mediasIds = data_get($data, 'medias');
        
        if ($mediasIds && $exploded = explode(",", trim($mediasIds))) {
            $animal->medias()->sync($exploded);
        }

        return $animal;
    }
}
