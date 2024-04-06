<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinanceRequest;
use App\Http\Services\Finance\CreateFinanceService;
use App\Http\Services\Finance\DeleteFinanceService;
use App\Http\Services\Finance\QueryFinanceService;
use App\Http\Services\Finance\UpdateFinanceService;
use Illuminate\Support\Facades\DB;

class FinancesController extends Controller
{
    public function create(FinanceRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateFinanceService();

            $finance = $service->create($request->all());

            DB::commit();

            return $finance;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(FinanceRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $service = new UpdateFinanceService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new DeleteFinanceService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Finança excluída com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function getFinances(Request $request)
    {
        $service = new QueryFinanceService();

        return $service->getFinances($request->all());
    }

    public function getFinanceById(int $id)
    {
        $service = new QueryFinanceService();

        return $service->getFinanceById($id);
    }
}
