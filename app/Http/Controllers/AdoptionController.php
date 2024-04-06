<?php

namespace App\Http\Controllers;

use App\Enums\AdoptionsStatusEnum;
use App\Http\Requests\AdoptionRequest;
use App\Http\Services\Adoption\ChangeAdoptionStatusService;
use App\Http\Services\Adoption\CreateAdoptionService;
use App\Http\Services\Adoption\DeleteAdoptionService;
use App\Http\Services\Adoption\QueryAdoptionService;
use App\Http\Services\Adoption\UpdateAdoptionService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    public function create(AdoptionRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateAdoptionService();

            $adoption = $service->create($request->all());

            DB::commit();

            return $adoption;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();

            $service = new UpdateAdoptionService();

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

            $service = new DeleteAdoptionService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção excluída com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function getAdoptions(Request $request)
    {
        $service = new QueryAdoptionService();

        return $service->getAdoptions($request->all());
    }

    public function getAdoptionById(int $id)
    {
        $service = new QueryAdoptionService();

        return $service->getAdoptionById($id);
    }

    public function confirmAdotpion(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::ADOPTED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção confirmada com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function denyAdotpion(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::DENIED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção negada com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function cancelAdotpion(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new ChangeAdoptionStatusService(AdoptionsStatusEnum::CANCELLED);

            $service->changeStatus($id);

            DB::commit();

            return response()->json([
                'message' => 'Adoção cancelada com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
