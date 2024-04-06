<?php

namespace App\Http\Controllers;


use App\Http\Requests\AnimalRequest;
use App\Http\Services\Animal\CreateAnimalService;
use App\Http\Services\Animal\DeleteAnimalService;
use App\Http\Services\Animal\QueryAnimalService;
use App\Http\Services\Animal\UpdateAnimalService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{
    public function create(AnimalRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateAnimalService();

            $animal = $service->create($request->all());

            DB::commit();

            return $animal;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(AnimalRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $service = new UpdateAnimalService();

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

            $service = new DeleteAnimalService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Animal excluído com sucesso!'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function getAnimals(Request $request)
    {
        $service = new QueryAnimalService();

        return $service->getAnimals($request->all());
    }

    public function getAnimalById(int $id)
    {
        $service = new QueryAnimalService();

        return $service->getAnimalById($id);
    }
}
