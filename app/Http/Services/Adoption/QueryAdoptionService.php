<?php

namespace App\Http\Services\Adoption;

use App\Repositories\AdoptionRepository;

class QueryAdoptionService
{
    public function getAdoptions(array $filters)
    {
        return (new AdoptionRepository())->getAdoptions($filters);
    }

    public function getAdoptionById(int $id)
    {
        return (new AdoptionRepository())->getById($id);
    }
}
