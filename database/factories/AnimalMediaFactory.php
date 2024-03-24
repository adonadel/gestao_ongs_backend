<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalMediaFactory extends Factory
{

    public function definition(): array
    {
        $animal = Animal::factory()->create();
        $media = Media::factory()->create();

        return [
            'animal_id' => $animal->id,
            'media_id' => $media->id,
            'order' => fake()->unique()->randomNumber(),
        ];
    }
}
