<?php

namespace App\Http\Services\Finance;

use App\Enums\FinancePaymentStatusEnum;
use App\Repositories\FinanceRepository;

class CancelFinanceService
{

    public function cancel(int $id)
    {
        $financeRepository = new FinanceRepository();

        $finance = $financeRepository->getById($id);

        if ($finance->status === FinancePaymentStatusEnum::PAID) {
            throw new \Exception('Pagamento já realizado anteriormente');
        }

        $financeRepository->update($finance, [
            'status' => FinancePaymentStatusEnum::CANCELED
        ]);

        return true;
    }
}
