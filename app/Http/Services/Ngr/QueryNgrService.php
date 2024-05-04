<?php

namespace App\Http\Services\Ngr;

use App\Repositories\NgrRepository;

class QueryNgrService
{

    public function getById(int $id)
    {
        return (new NgrRepository())->getById($id);
    }
}
