<?php

namespace Database\Factories;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'gender' => fake()->randomElement(AnimalGenderEnum::cases()),
            'size' => fake()->randomElement(AnimalSizeEnum::cases()),
            'age_type' => fake()->randomElement(AnimalAgeTypeEnum::cases()),
            'description' => fake()->text(100),
            'tags' => str_replace('', ',', fake()->text(50)),
        ];
    }
}
