<?php

namespace App\Repositories;

use App\Models\Ngr;

class NgrRepository extends Repository
{
    protected function getModelClass(): string
    {
        return Ngr::class;
    }


}
