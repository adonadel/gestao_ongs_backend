<?php

namespace Database\Factories;

use App\Enums\AgeTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\SizeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'gender' => fake()->randomElement(GenderEnum::cases()),
            'size' => fake()->randomElement(SizeEnum::cases()),
            'age_type' => fake()->randomElement(AgeTypeEnum::cases()),
            'description' => fake()->text(100),
            'tags' => str_replace('', ',', fake()->text(50)),
        ];
    }
}
