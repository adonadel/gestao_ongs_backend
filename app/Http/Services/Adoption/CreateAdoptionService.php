<?php

namespace App\Http\Services\Adoption;

use App\Enums\AdoptionsStatusEnum;
use App\Exceptions\AnimalAlreadyAdoptedException;
use App\Repositories\AdoptionRepository;
use App\Repositories\AnimalRepository;

class CreateAdoptionService
{

    public function create(array $data)
    {
        $repository = new AdoptionRepository();

        $exists = (new AnimalRepository())->newQuery()
            ->join('adoptions', 'animals.id', '=', 'adoptions.animal_id')
            ->where('animals.id', data_get($data, 'animal_id'))
            ->whereIn('adoptions.status', [
                AdoptionsStatusEnum::ADOPTED,
                AdoptionsStatusEnum::PROCESSING,
                AdoptionsStatusEnum::OPENED
            ])
            ->exists();

        if ($exists) {
            throw new AnimalAlreadyAdoptedException('Animal já adotado ou em processo de adoção');
        }

        $adoption = $repository->create($data);

        return $adoption;
    }
}
