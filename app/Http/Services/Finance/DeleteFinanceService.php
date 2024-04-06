<?php

namespace App\Http\Services\Finance;

use App\Repositories\FinanceRepository;

class DeleteFinanceService
{

    public function delete(int $id)
    {
        $repository = new FinanceRepository();

        $finance = $repository->getById($id);

        return $repository->delete($finance);
    }
}
