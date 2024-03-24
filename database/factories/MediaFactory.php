<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{

    public function definition(): array
    {
        return [
            'filename' => fake()->file,
            'display_name' => fake()->name,
            'size' => fake()->numberBetween(1, 100000),
            'extension' => fake()->fileExtension(),
            'description' => fake()->text(100),
        ];
    }
}
