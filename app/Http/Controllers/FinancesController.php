<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinanceRequest;
use App\Http\Services\Finance\CreateFinanceService;
use App\Http\Services\Finance\DeleteFinanceService;
use App\Http\Services\Finance\QueryFinanceService;
use App\Http\Services\Finance\UpdateFinanceService;
use App\Models\Finance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FinancesController extends Controller
{
    public function create(FinanceRequest $request)
    {
        Gate::authorize('create', Finance::class);

        try {
            DB::beginTransaction();

            $service = new CreateFinanceService();

            $finance = $service->create($request->all());

            DB::commit();

            return $finance;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function update(FinanceRequest $request, int $id)
    {
        Gate::authorize('update', Finance::class);

        try {
            DB::beginTransaction();

            $service = new UpdateFinanceService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        Gate::authorize('delete', Finance::class);

        try {
            DB::beginTransaction();

            $service = new DeleteFinanceService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Finança excluída com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function getFinances(Request $request)
    {
        Gate::authorize('view', Finance::class);

        $service = new QueryFinanceService();

        return $service->getFinances($request->all());
    }

    public function getFinanceById(int $id)
    {
        Gate::authorize('view', Finance::class);

        $service = new QueryFinanceService();

        return $service->getFinanceById($id);
    }
}
