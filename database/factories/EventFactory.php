<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{

    public function definition(): array
    {
        $address = Address::factory()->create();

        return [
            'name' => fake()->name,
            'description' => fake()->text(100),
            'event_date' => fake()->date,
            'location' => fake()->name,
            'address_id' => $address->id,
        ];
    }
}
