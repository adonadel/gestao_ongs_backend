<?php

namespace App\Http\Services\Finance;

use App\Enums\FinancePaymentStatusEnum;
use App\Repositories\FinanceRepository;

class CompleteFinanceService
{

    public function complete(int $id)
    {
        $financeRepository = new FinanceRepository();

        $finance = $financeRepository->getById($id);

        switch ($finance->status) {
            case FinancePaymentStatusEnum::PAID:
                throw new \Exception('Pagamento já realizado anteriormente');
            case FinancePaymentStatusEnum::CANCELED:
                throw new \Exception('Pagamento cancelado');
        }

        $financeRepository->update($finance, [
            'status' => FinancePaymentStatusEnum::PAID
        ]);

        return true;
    }
}
