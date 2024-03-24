<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class NgrFactory extends Factory
{

    public function definition(): array
    {
        $address = Address::factory()->create();
        $media = Media::factory()->create();

        return [
            'name' => fake()->name,
            'cnpj' => fake()->text(100),
            'event_date' => fake()->date,
            'description' => fake()->text(100),
            'address_id' => $address->id,
            'profile_picture_id' => $media->id,
        ];
    }
}
