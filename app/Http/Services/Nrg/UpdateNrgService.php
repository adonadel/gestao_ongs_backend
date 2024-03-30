<?php

namespace App\Http\Services\Nrg;

use App\Repositories\NrgRepository;

class UpdateNrgService
{

    public function update(array $data, int $id)
    {
        $repository = new NrgRepository();

        $nrg = $repository->getById($id);

        return $repository->update($nrg, $data);
    }
}
