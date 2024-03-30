<?php

namespace App\Http\Controllers;

use App\Http\Services\Nrg\UpdateNrgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NrgController extends Controller
{
    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string',
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

            $service = new UpdateNrgService();

            $updated = $service->update($validated, $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
