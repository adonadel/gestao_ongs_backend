<?php

namespace App\Http\Controllers;

use App\Http\Services\Ngr\QueryNgrService;
use App\Http\Services\Ngr\UpdateNgrService;
use App\Models\Ngr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class NgrController extends Controller
{
    public function update(Request $request, int $id)
    {
        Gate::authorize('update', Ngr::class);

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string',
                'phone' => 'nullable|string',
                'cnpj' => 'required|string',
                'description' => 'nullable|string',
                'address' => 'array|nullable',
                'address.zip' => 'string|nullable',
                'address.state' => 'string|nullable',
                'address.city' => 'string|nullable',
                'address.neighborhood' => 'string|nullable',
                'address.street' => 'string|nullable',
                'address.number' => 'string|nullable',
                'address.complement' => 'string|nullable',
                'address.latitude' => 'decimal:8,6|nullable',
                'address.longitude' => 'decimal:9,6|nullable',
            ]);

            $service = new UpdateNgrService();

            $updated = $service->update($validated, $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function getById(int $id)
    {
        Gate::authorize('view', Ngr::class);

        $service = new QueryNgrService();

        return $service->getById($id);
    }
}
