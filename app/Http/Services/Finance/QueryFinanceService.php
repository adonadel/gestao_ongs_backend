<?php

namespace App\Http\Services\Finance;

use App\Repositories\FinanceRepository;

class QueryFinanceService
{
    public function getFinances(array $filters)
    {
        return (new FinanceRepository())->getFinances($filters);
    }

    public function getFinanceById(int $id)
    {
        return (new FinanceRepository())->getById($id);
    }
}
