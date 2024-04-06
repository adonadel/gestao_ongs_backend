<?php

namespace App\Http\Services\Adoption;

use App\Repositories\AdoptionRepository;

class CreateAdoptionService
{

    public function create(array $data)
    {
        $repository = new AdoptionRepository();

        $adoption = $repository->create($data);

        return $adoption;
    }
}
