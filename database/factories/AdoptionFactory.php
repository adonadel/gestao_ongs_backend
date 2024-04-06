<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdoptionFactory extends Factory
{

    public function definition(): array
    {
        $user = User::factory()->create();
        $animal = Animal::factory()->create();

        return [
            'description' => fake()->text(100),
            'adoption_date' => fake()->date,
            'user_id' => $user->id,
            'animal_id' => $animal->id,
        ];
    }
}
