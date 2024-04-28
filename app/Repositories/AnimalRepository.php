<?php

namespace App\Repositories;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
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

        $ageTypeValidated = in_array($ageType, AnimalAgeTypeEnum::toArrayWithString(), true);
        $sizeValidated = in_array($size, AnimalSizeEnum::toArrayWithString(), true);
        $genderValidated = in_array($gender, AnimalGenderEnum::toArrayWithString(), true);

        $query = $this->newQuery();

        $query
            ->with('medias')
            ->when($search, function(Builder $query, $search){
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('tags', 'ilike', "%{$search}%");
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
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
