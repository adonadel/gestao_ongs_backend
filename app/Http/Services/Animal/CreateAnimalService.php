<?php

namespace App\Http\Services\Animal;

use App\Http\Services\Media\CreateMediaService;
use App\Repositories\AnimalRepository;

class CreateAnimalService
{

    public function create(array $data)
    {
        $repository = new AnimalRepository();

        $mediasIds = data_get($data, 'medias');

        $animal = $repository->create($data);

        if ($mediasIds && explode(",", trim($mediasIds))) {
            $animal->medias()->sync($mediasIds);
        }

        return $animal;
    }

    public function createWithMedias(array $data)
    {
        $repository = new AnimalRepository();

        $animal = $repository->create($data);

        if (data_get($data, 'medias')) {
            foreach (data_get($data, 'medias') as $media){
                $createMediaService = new CreateMediaService();

                $createMediaService->create($media);
            }
        }

        return $animal;
    }
}
