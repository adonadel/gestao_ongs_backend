<?php

namespace Database\Factories;

use App\Enums\FinanceTypeEnum;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceFactory extends Factory
{

    public function definition(): array
    {
        $user = User::factory()->create();
        $animal = Animal::factory()->create();

        return [
            'description' => fake()->text(100),
            'date' => fake()->date,
            'value' => fake()->numberBetween(1, 10000),
            'type' => fake()->randomElement(FinanceTypeEnum::cases()),
            'user_id' => $user->id,
            'animal_id' => $animal->id,
        ];
    }
}
