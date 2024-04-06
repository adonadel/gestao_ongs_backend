<?php

namespace App\Http\Services\Ngr;

use App\Repositories\NgrRepository;

class UpdateNgrService
{

    public function update(array $data, int $id)
    {
        $repository = new NgrRepository();

        $ngr = $repository->getById($id);

        return $repository->update($ngr, $data);
    }
}
