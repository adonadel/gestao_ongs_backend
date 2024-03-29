<?php

namespace App\Http\Controllers;


use App\Enums\AgeTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\SizeEnum;
use App\Http\Services\Animal\CreateAnimalService;
use App\Http\Services\Animal\DeleteAnimalService;
use App\Http\Services\Animal\UpdateAnimalService;
use App\Http\Services\Media\QueryAnimalService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AnimalController extends Controller
{
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string',
                'gender' => ['required', Rule::in(GenderEnum::cases())],
                'size' => ['required', Rule::in(SizeEnum::cases())],
                'age_type' => ['required', Rule::in(AgeTypeEnum::cases())],
                'description' => 'nullable|string',
                'tags' => 'nullable|string',
                'medias' => 'array|required',
                'medias.*.media' => [
                    'required', File::types(['jpg', 'jpeg', 'png'])
                ],
                'medias.*.display_name' => 'nullable|string',
                'medias.*.description' => 'nullable|string',
            ]);

            $service = new CreateAnimalService();

            $animal = $service->create($validated);

            DB::commit();

            return $animal;
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string',
                'gender' => ['required', Rule::in(GenderEnum::cases())],
                'size' => ['required', Rule::in(SizeEnum::cases())],
                'age_type' => ['required', Rule::in(AgeTypeEnum::cases())],
                'description' => 'nullable|string',
                'tags' => 'nullable|string',
                'medias' => 'array|required',
                'medias.*.media' => [
                    'required', File::types(['jpg', 'jpeg', 'png'])
                ],
                'medias.*.display_name' => 'nullable|string',
                'medias.*.description' => 'nullable|string',
            ]);

            $service = new UpdateAnimalService();

            $updated = $service->update($validated, $id);

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
    }
}
