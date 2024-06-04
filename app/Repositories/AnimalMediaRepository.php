<?php

namespace App\Repositories;

use App\Models\AnimalMedia;

class AnimalMediaRepository extends Repository
{
    protected function getModelClass(): string
    {
        return AnimalMedia::class;
    }


}
