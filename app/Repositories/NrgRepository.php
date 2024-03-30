<?php

namespace App\Repositories;

use App\Models\Nrg;

class NrgRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Nrg::class;
    }


}
