<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\People>
 */
class PeopleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $address = Address::factory()->create();
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf_cnpj' => fake()->unique()->numerify('###.###.###-##'),
            'address_id' => $address->id
        ];
    }
}
