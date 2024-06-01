<?php

namespace App\Http\Controllers;


use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Http\Requests\AnimalRequest;
use App\Http\Services\Animal\CreateAnimalService;
use App\Http\Services\Animal\DeleteAnimalService;
use App\Http\Services\Animal\QueryAnimalService;
use App\Http\Services\Animal\UpdateAnimalService;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AnimalController extends Controller
{
    public function create(Request $request)
    {
        Gate::authorize('create', Animal::class);

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'gender' => ['required', Rule::in(AnimalGenderEnum::cases())],
                'size' => ['required', Rule::in(AnimalSizeEnum::cases())],
                'age_type' => ['required', Rule::in(AnimalAgeTypeEnum::cases())],
                'castrate_type' => ['required', Rule::in(AnimalCastrateEnum::cases())],
                'description' => 'nullable|string',
                'location' => 'nullable|string',
                'tags' => 'nullable|string',
                'medias' => 'array|required',
                'medias.*.id' => 'required|exists:medias,id',
            ]);

            DB::beginTransaction();

            $service = new CreateAnimalService();

            $animal = $service->create($validated);

            DB::commit();

            return $animal;
        }catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception($exception->getMessage());
        }
    }
    
    public function createWithMedias(AnimalRequest $request)
    {
        Gate::authorize('create', Animal::class);

        try {
            DB::beginTransaction();

            $service = new CreateAnimalService();

            $animal = $service->createWithMedias($request->all());

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
