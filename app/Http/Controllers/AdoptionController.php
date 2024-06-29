<?php

namespace App\Http\Controllers;

use App\Enums\AdoptionsStatusEnum;
use App\Http\Services\Adoption\ChangeAdoptionStatusService;
use App\Http\Services\Adoption\CreateAdoptionService;
use App\Http\Services\Adoption\DeleteAdoptionService;
use App\Http\Services\Adoption\QueryAdoptionService;
use App\Http\Services\Adoption\UpdateAdoptionService;
use App\Models\Adoption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdoptionController extends Controller
{
    public function create(Request $request)
    {
        Gate::authorize('create', Adoption::class);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'user_id' => 'required|int|exists:users,id',
            'animal_id' => 'required|int|exists:animals,id'
        ]);

        try {
            DB::beginTransaction();

            $service = new CreateAdoptionService();

            $adoption = $service->create($validated);

            DB::commit();

            return $adoption;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        Gate::authorize('update', Adoption::class);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'user_id' => 'required|int|exists:users,id',
            'animal_id' => 'required|int|exists:animals,id'
        ]);

        try {
            DB::beginTransaction();

            $service = new UpdateAdoptionService();

            $updated = $service->update($validated, $id);

            DB::commit();

            return $updated;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        Gate::authorize('delete', Adoption::class);

        try {
            DB::beginTransaction();

            $service = new DeleteAdoptionService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção excluída com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function getAdoptions(Request $request)
    {
        Gate::authorize('view', Adoption::class);

        $service = new QueryAdoptionService();

        return $service->getAdoptions($request->all());
    }

    public function getAdoptionById(int $id)
    {
        Gate::authorize('view', Adoption::class);

        $service = new QueryAdoptionService();

        return $service->getAdoptionById($id);
    }

    public function confirmAdoption(int $id)
    {
        Gate::authorize('updateAdoptionStatus', Adoption::class);

        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::ADOPTED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção confirmada com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function denyAdoption(int $id)
    {
        Gate::authorize('updateAdoptionStatus', Adoption::class);

        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::DENIED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção negada com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function cancelAdoption(int $id)
    {
        Gate::authorize('updateAdoptionStatus', Adoption::class);

        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::CANCELLED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção cancelada com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function processAdoption(int $id)
    {
        Gate::authorize('updateAdoptionStatus', Adoption::class);

        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::PROCESSING);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção processada com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }
}
