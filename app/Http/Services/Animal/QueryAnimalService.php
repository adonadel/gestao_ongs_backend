<?php

namespace App\Http\Services\Animal;

use App\Repositories\AnimalRepository;

class QueryAnimalService
{
    public function getAnimals(array $filters)
    {
        return (new AnimalRepository())->getAnimals($filters);
    }

    public function getAnimalById(int $id)
    {
        $animal = (new AnimalRepository())->getById($id)->load('medias');

        return [
            ...$animal->toArray(),
            'medias' => $animal->medias->map(function ($media) {
                return [
                    ...$media->toArray(),
                    'is_cover' => (bool) $media->pivot->is_cover
                ];
            })
        ];
    }
}
