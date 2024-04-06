<?php

namespace App\Http\Services\Finance;

use App\Repositories\FinanceRepository;

class CreateFinanceService
{

    public function create(array $data)
    {
        $repository = new FinanceRepository();

        $finance = $repository->create($data);

        return $finance;
    }
}
