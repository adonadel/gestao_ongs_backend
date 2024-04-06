<?php

namespace App\Http\Services\Finance;

use App\Repositories\FinanceRepository;

class UpdateFinanceService
{

    public function update(array $data, int $id)
    {
        $repository = new FinanceRepository();

        $finance = $repository->getById($id);

        $repository->update($finance, $data);

        return $finance;
    }
}
