<?php

namespace App\Http\Controllers;


use App\Http\Requests\AnimalRequest;
use App\Http\Services\Animal\CreateAnimalService;
use App\Http\Services\Animal\DeleteAnimalService;
use App\Http\Services\Animal\QueryAnimalService;
use App\Http\Services\Animal\UpdateAnimalService;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AnimalController extends Controller
{
    public function create(AnimalRequest $request)
    {
        Gate::authorize('create', Animal::class);

        try {
            DB::beginTransaction();

            $service = new CreateAnimalService();

            $animal = $service->create($request->all());

            DB::commit();

            return $animal;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function update(AnimalRequest $request, int $id)
    {
        Gate::authorize('update', Animal::class);

        try {
            DB::beginTransaction();

            $service = new UpdateAnimalService();

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
        Gate::authorize('delete', Animal::class);

        try {
            DB::beginTransaction();

            $service = new DeleteAnimalService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Animal excluído com sucesso!'
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }

    public function getAnimals(Request $request)
    {
        Gate::authorize('view', Animal::class);

        $service = new QueryAnimalService();

        return $service->getAnimals($request->all());
    }

    public function getAnimalById(int $id)
    {
        Gate::authorize('view', Animal::class);

        $service = new QueryAnimalService();

        return $service->getAnimalById($id);
    }
}
