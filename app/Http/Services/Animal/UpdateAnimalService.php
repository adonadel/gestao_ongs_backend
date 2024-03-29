<?php

namespace App\Http\Services\Animal;

use App\Http\Services\Media\CreateMediaService;
use App\Repositories\AnimalRepository;

class UpdateAnimalService
{

    public function update(array $data, int $id)
    {
        $repository = new AnimalRepository();

        $animal = $repository->getById($id);

        $repository->update($animal, $data);

        if (data_get($data, 'medias')) {
            foreach (data_get($data, 'medias') as $media){
                $createMediaService = new CreateMediaService();

                $createMediaService->create($media);
            }
        }

        return $animal;
    }
}
