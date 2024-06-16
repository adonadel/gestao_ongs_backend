<?php

namespace App\Repositories;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Enums\AnimalTypeEnum;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Builder;

class AnimalRepository extends Repository
{
    protected $table = 'animals';

    protected function getModelClass()
    {
        return Animal::class;
    }

    public function getAnimals(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'search');
        $ageType = data_get($filters, 'age_type');
        $size = data_get($filters, 'size');
        $gender = data_get($filters, 'gender');
        $animal_type = data_get($filters, 'animal_type');

        $ageTypeValidated = in_array($ageType, AnimalAgeTypeEnum::toArrayWithString(), true);
        $sizeValidated = in_array($size, AnimalSizeEnum::toArrayWithString(), true);
        $genderValidated = in_array($gender, AnimalGenderEnum::toArrayWithString(), true);
        $animalTypeValidated = in_array($animal_type, AnimalTypeEnum::toArrayWithString(), true);

        $query = $this->newQuery();

        $query
            ->with('medias')
            ->when($search, function(Builder $query, $search){
                $query
                    ->whereRaw('unaccent(name) ilike unaccent(?)', ["%{$search}%"])
                    ->orWhereRaw('unaccent(description) ilike unaccent(?)', ["%{$search}%"])
                    ->orWhereRaw('unaccent(tags) ilike unaccent(?)', ["%{$search}%"]);
            })
            ->when($ageTypeValidated, function (Builder $query) use ($ageType){
                $query
                    ->where('age_type', $ageType);
            })
            ->when($sizeValidated, function (Builder $query) use ($size){
                $query
                    ->where('size', $size);
            })
            ->when($genderValidated, function (Builder $query) use ($gender){
                $query
                    ->where('gender', $gender);
            })
            ->when($animalTypeValidated, function (Builder $query) use ($animalTypeValidated){
                $query
                    ->where('animal_type', $animalTypeValidated);
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
